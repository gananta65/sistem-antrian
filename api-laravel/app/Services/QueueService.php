<?php

namespace App\Services;

use App\Models\Queue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class QueueService
{
    /**
     * Generate next queue number for given type
     */
    public function generateQueueNumber(string $type): string
    {
        $prefix = $type === 'reservation' ? 'R' : 'W';

        $lastQueue = Queue::where('type', $type)
            ->whereDate('created_at', today())
            ->latest('id')
            ->first();

        $nextNumber = $lastQueue ? ((int) substr($lastQueue->queue_number, 1)) + 1 : 1;

        return $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create new queue
     */
    public function createQueue(string $type): Queue
    {
        return Queue::create([
            'queue_number' => $this->generateQueueNumber($type),
            'type' => $type,
            'status' => 'waiting',
        ]);
    }

    /**
     * Get next queue based on 2:1 algorithm
     */
    public function getNextQueue(Collection $waitingQueues): ?Queue
    {
        $reservations = $waitingQueues->where('type', 'reservation')->values();
        $walkins = $waitingQueues->where('type', 'walkin')->values();

        if ($reservations->isEmpty()) return $walkins->first();
        if ($walkins->isEmpty()) return $reservations->first();

        // Hitung posisi dalam pola 2R:1W hari ini
        $calledCount = Queue::whereIn('status', ['called', 'done'])
            ->whereDate('created_at', today())
            ->count();

        return ($calledCount % 3 < 2) ? $reservations->first() : $walkins->first();
    }

    /**
     * Hitung posisi antrean untuk queue tertentu
     */
    public function getQueuesAhead(Queue $queue): int
    {
        if ($queue->status !== 'waiting') return 0;

        $todayQueues = Queue::waiting()
            ->whereDate('created_at', today())
            ->orderBy('created_at')
            ->get();

        $position = 0;
        $reservations = $todayQueues->where('type', 'reservation')->values();
        $walkins = $todayQueues->where('type', 'walkin')->values();

        foreach ($todayQueues as $q) {
            if ($q->id === $queue->id) break;

            $position++;
        }

        return $position;
    }

    /**
     * Estimasi waktu tunggu
     */
    public function estimateWaitTime(int $queuesAhead): int
    {
        $avgTime = DB::table('services')
            ->whereDate('created_at', today())
            ->whereNotNull('duration_seconds')
            ->avg('duration_seconds');

        $avgMinutes = $avgTime ? ceil($avgTime / 60) : 3;

        return $queuesAhead * $avgMinutes;
    }
}
