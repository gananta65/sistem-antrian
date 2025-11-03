<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;

class DisplayController extends Controller
{
    /**
     * Get current display information
     */
    public function current()
    {
        // Get currently called queues
        $currentQueues = Queue::called()
            ->with('staff')
            ->orderBy('called_at', 'desc')
            ->get()
            ->map(function ($queue) {
                return [
                    'queue_number' => $queue->queue_number,
                    'type' => $queue->type,
                    'staff' => [
                        'name' => $queue->staff->name,
                        'counter_number' => $queue->staff->counter_number,
                    ],
                    'counter_display' => $queue->staff->counter_number 
                        ? "Loket {$queue->staff->counter_number}" 
                        : 'Counter',
                    'called_at' => $queue->called_at,
                ];
            });

        // Get the most recent called queue for TTS
        $latestQueue = $currentQueues->first();

        $ttsMessage = $latestQueue 
            ? "Nomor antrian {$latestQueue['queue_number']}, silakan menuju {$latestQueue['counter_display']}"
            : null;

        // Recently called (last 5)
        $recentlyCalled = Queue::where('status', 'called')
            ->orWhere(function ($query) {
                $query->where('status', 'done')
                    ->where('completed_at', '>=', now()->subMinutes(5));
            })
            ->with('staff')
            ->orderBy('called_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($queue) {
                return [
                    'queue_number' => $queue->queue_number,
                    'counter_number' => $queue->staff->counter_number 
                        ? "Loket {$queue->staff->counter_number}" 
                        : 'Counter',
                    'called_at' => $queue->called_at,
                    'status' => $queue->status,
                ];
            });

        return response()->json([
            'current_queues' => $currentQueues,
            'recently_called' => $recentlyCalled,
            'tts_message' => $ttsMessage,
            'timestamp' => now(),
        ]);
    }

    /**
     * Get latest called queue (for polling)
     */
    public function latest()
    {
        $queue = Queue::called()
            ->with('staff')
            ->orderBy('called_at', 'desc')
            ->first();

        if (!$queue) {
            return response()->json([
                'queue' => null,
            ]);
        }

        return response()->json([
            'queue' => [
                'queue_number' => $queue->queue_number,
                'type' => $queue->type,
                'counter_number' => $queue->staff->counter_number,
                'counter_display' => $queue->staff->counter_number 
                    ? "Loket {$queue->staff->counter_number}" 
                    : 'Counter',
                'called_at' => $queue->called_at,
                'tts_message' => "Nomor antrian {$queue->queue_number}, silakan menuju Loket {$queue->staff->counter_number}",
            ],
        ]);
    }
}