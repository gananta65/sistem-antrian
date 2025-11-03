<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Service;
use App\Models\Staff;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request)
    {
        $period = $request->query('period', 'today'); // today, month, lifetime

        // Active queues
        $waitingQueues = Queue::waiting()->get();
        $calledQueues = Queue::called()->get();

        // Active staff
        $activeStaffCount = Staff::where('is_active', true)->count();

        // Top staff - CLEAN SOLUTION
        $topStaffQuery = Staff::withCount(['queues as queues_served_count' => function ($query) use ($period) {
            $query->where('status', 'done');

            switch ($period) {
                case 'today':
                    $query->whereDate('completed_at', today());
                    break;
                case 'month':
                    $query->whereMonth('completed_at', now()->month)
                        ->whereYear('completed_at', now()->year);
                    break;
                case 'lifetime':
                    // no additional filter
                    break;
            }
        }])
            // Hapus ->having('queues_served_count', '>', 0) dari sini
            ->orderBy('queues_served_count', 'desc')
            ->limit(3)
            ->get(); // Eksekusi query untuk mendapatkan koleksi

        // Filter koleksi setelah query dieksekusi
        $topStaff = $topStaffQuery->filter(function ($staff) {
            return $staff->queues_served_count > 0;
        })->values()->map(function ($staff, $index) use ($period) {
            // Get average service time
            $serviceQuery = Service::where('staff_id', $staff->id)
                ->whereNotNull('duration_seconds');

            switch ($period) {
                case 'today':
                    $serviceQuery->whereDate('created_at', today());
                    break;
                case 'month':
                    $serviceQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'lifetime':
                    // no additional filter
                    break;
            }

            $avgServiceTime = $serviceQuery->avg('duration_seconds');

            return [
                'rank' => $index + 1,
                'name' => $staff->name,
                'counter_number' => $staff->counter_number ? "Loket {$staff->counter_number}" : 'Not assigned',
                'total_served' => $staff->queues_served_count,
                'avg_service_time_seconds' => $avgServiceTime ? round($avgServiceTime) : null,
                'avg_service_time_formatted' => $avgServiceTime
                    ? $this->formatDuration(round($avgServiceTime))
                    : '-',
            ];
        });

        // ... (bagian kode selanjutnya tetap sama)
        $totalServedQuery = Queue::where('status', 'done');
        $avgServiceQuery = Service::whereNotNull('duration_seconds');

        switch ($period) {
            case 'today':
                $totalServedQuery->whereDate('completed_at', today());
                $avgServiceQuery->whereDate('created_at', today());
                break;
            case 'month':
                $totalServedQuery->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year);
                $avgServiceQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
                break;
            case 'lifetime':
                // no additional filter
                break;
        }

        $totalServed = $totalServedQuery->count();
        $avgServiceTime = $avgServiceQuery->avg('duration_seconds');

        return response()->json([
            'period' => $period,
            'active_queues' => [
                'waiting' => $waitingQueues->count(),
                'called' => $calledQueues->count(),
                'by_type' => [
                    'reservation' => $waitingQueues->where('type', 'reservation')->count(),
                    'walkin' => $waitingQueues->where('type', 'walkin')->count(),
                ],
            ],
            'active_staff_count' => $activeStaffCount,
            'top_staff' => $topStaff,
            'overall_stats' => [
                'total_served' => $totalServed,
                'avg_service_time_seconds' => $avgServiceTime ? round($avgServiceTime) : null,
                'avg_service_time_formatted' => $avgServiceTime
                    ? $this->formatDuration(round($avgServiceTime))
                    : '-',
            ],
        ]);
    }

    /**
     * Format durasi detik menjadi string
     */
    private function formatDuration(int $seconds): string
    {
        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0 && $remainingSeconds > 0) {
            return "{$minutes} menit {$remainingSeconds} detik";
        } elseif ($minutes > 0) {
            return "{$minutes} menit";
        } else {
            return "{$remainingSeconds} detik";
        }
    }
}
