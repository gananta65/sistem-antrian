<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Service;
use App\Models\Staff;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * Mulai On Duty dengan memilih loket
     */
    public function startDuty(Request $request)
    {
        $staff = $request->user();

        $request->validate([
            'counter_number' => 'required|integer|min:1|max:3',
        ]);

        $counter = $request->input('counter_number');

        // Cek apakah loket sedang dipakai
        $occupied = Staff::where('is_active', true)
            ->where('counter_number', $counter)
            ->exists();

        if ($occupied) {
            return response()->json([
                'message' => "Loket {$counter} sudah terpakai. Silakan pilih loket lain.",
            ], 400);
        }

        // Update staff jadi on duty
        $staff->update([
            'is_active' => true,
            'counter_number' => $counter,
        ]);

        return response()->json([
            'message' => "Staff {$staff->name} sekarang on duty di Loket {$counter}.",
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'counter_number' => $staff->counter_number,
                'is_active' => $staff->is_active,
            ],
        ]);
    }

    /**
     * Off Duty (logout dari loket)
     */
    public function endDuty(Request $request)
    {
        $staff = $request->user();

        $staff->update([
            'is_active' => false,
            'counter_number' => null,
            'current_queue_id' => null,
        ]);

        return response()->json([
            'message' => "Staff {$staff->name} sudah off duty.",
            'staff' => [
                'id' => $staff->id,
                'name' => $staff->name,
                'counter_number' => $staff->counter_number,
                'is_active' => $staff->is_active,
            ],
        ]);
    }

    /**
     * Cek status semua loket
     */
    public function checkCounters()
    {
        $counters = [];
        for ($i = 1; $i <= 3; $i++) {
            $occupied = Staff::where('is_active', true)
                ->where('counter_number', $i)
                ->exists();
            $counters[$i] = $occupied ? 'occupied' : 'available';
        }

        return response()->json([
            'counters' => $counters,
        ]);
    }

    /**
     * Call next queue (hanya jika staff on duty)
     */
    public function callNext(Request $request)
    {
        $staff = $request->user();

        if (! $staff->is_active || ! $staff->counter_number) {
            return response()->json(['message' => 'Anda harus on duty dan memilih loket terlebih dahulu.'], 403);
        }

        if ($staff->current_queue_id) {
            return response()->json([
                'message' => 'Anda harus menyelesaikan antrian saat ini dulu',
                'current_queue' => Queue::find($staff->current_queue_id),
            ], 400);
        }

        $queue = DB::transaction(function () use ($staff) {
            // Ambil queue yang waiting pakai raw query biar bisa skipLocked
            $waitingQueues = DB::table('queues')
                ->where('status', 'waiting')
                ->orderBy('created_at')
                ->lockForUpdate()
                ->get();

            // NOTE: PostgreSQL otomatis skip locked dengan perintah di bawah
            // tapi Laravel 12.36 belum bisa, jadi kita pakai raw SQL.
            $waitingQueues = DB::select("
                SELECT * FROM queues
                WHERE status = 'waiting'
                ORDER BY created_at
                FOR UPDATE SKIP LOCKED
            ");

            if (empty($waitingQueues)) {
                return null;
            }

            // Convert hasil query ke koleksi model Queue agar kompatibel dengan service
            $waitingQueuesCollection = Queue::hydrate($waitingQueues);

            $nextQueue = $this->queueService->getNextQueue($waitingQueuesCollection);

            if ($nextQueue) {
                $nextQueue->update([
                    'status' => 'called',
                    'staff_id' => $staff->id,
                    'called_at' => now(),
                ]);

                $staff->update(['current_queue_id' => $nextQueue->id]);

                Service::create([
                    'queue_id' => $nextQueue->id,
                    'staff_id' => $staff->id,
                    'started_at' => now(),
                ]);
            }

            return $nextQueue;
        });

        if (! $queue) {
            return response()->json(['message' => 'Tidak ada antrian yang menunggu'], 404);
        }

        $queue->load('staff');

        return response()->json([
            'message' => 'Antrian dipanggil berhasil',
            'queue' => $queue,
            'staff' => [
                'name' => $staff->name,
                'counter_number' => $staff->counter_number,
            ],
        ]);
    }

    /**
     * Complete current queue (hanya jika staff on duty)
     */
    public function completeCurrent(Request $request)
    {
        $staff = $request->user();

        if (! $staff->is_active || ! $staff->counter_number) {
            return response()->json([
                'message' => 'Anda harus on duty terlebih dahulu.',
            ], 403);
        }

        if (! $staff->current_queue_id) {
            return response()->json([
                'message' => 'Tidak ada antrian aktif untuk diselesaikan',
            ], 400);
        }

        $queue = Queue::find($staff->current_queue_id);

        if (! $queue || $queue->status !== 'called') {
            return response()->json([
                'message' => 'Status antrian tidak valid',
            ], 400);
        }

        $queue->update([
            'status' => 'done',
            'completed_at' => now(),
        ]);

        $service = Service::where('queue_id', $queue->id)
            ->whereNull('finished_at')
            ->first();

        if ($service) {
            $finishedAt = now();

            $duration = 0;

            if ($service->started_at) {
                try {
                    // Real-time diff, pastikan positif dan integer
                    $duration = abs((int) round($finishedAt->diffInRealSeconds($service->started_at)));
                } catch (\Exception $e) {
                    $duration = 0; // fallback kalau waktu rusak
                }
            }

            $service->update([
                'finished_at' => $finishedAt,
                'duration_seconds' => $duration,
            ]);
        }

        $staff->update([
            'current_queue_id' => null,
            'total_served' => $staff->total_served + 1,
        ]);

        return response()->json([
            'message' => 'Antrian selesai',
            'queue' => $queue,
            'service_duration_seconds' => $service?->duration_seconds,
        ]);
    }
}
