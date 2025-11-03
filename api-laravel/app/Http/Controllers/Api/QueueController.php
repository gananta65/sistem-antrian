<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Services\QueueService;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * Create reservation queue
     */
    public function createReservation()
    {
        $queue = $this->queueService->createQueue('reservation');
        $queuesAhead = $this->queueService->getQueuesAhead($queue);
        $estimatedWait = $this->queueService->estimateWaitTime($queuesAhead);

        return response()->json([
            'queue_number' => $queue->queue_number,
            'type' => $queue->type,
            'status' => $queue->status,
            'queues_ahead' => $queuesAhead,
            'estimated_wait_minutes' => $estimatedWait,
            'created_at' => $queue->created_at,
        ], 201);
    }

    /**
     * Create walk-in queue
     */
    public function createWalkin()
    {
        $queue = $this->queueService->createQueue('walkin');
        $queuesAhead = $this->queueService->getQueuesAhead($queue);
        $estimatedWait = $this->queueService->estimateWaitTime($queuesAhead);

        return response()->json([
            'queue_number' => $queue->queue_number,
            'type' => $queue->type,
            'status' => $queue->status,
            'queues_ahead' => $queuesAhead,
            'estimated_wait_minutes' => $estimatedWait,
            'created_at' => $queue->created_at,
        ], 201);
    }

    /**
     * Get all waiting queues
     */
    public function getWaiting()
    {
        $queues = Queue::waiting()
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'queues' => $queues,
            'total' => $queues->count(),
            'reservation_count' => $queues->where('type', 'reservation')->count(),
            'walkin_count' => $queues->where('type', 'walkin')->count(),
        ]);
    }

    /**
     * Get queue by number
     */
    public function getByNumber(string $queueNumber)
    {
        $queue = Queue::where('queue_number', $queueNumber)
            ->with('staff')
            ->first();

        if (!$queue) {
            return response()->json([
                'message' => 'Queue not found',
            ], 404);
        }

        $queuesAhead = $this->queueService->getQueuesAhead($queue);
        $estimatedWait = $this->queueService->estimateWaitTime($queuesAhead);

        return response()->json([
            'queue' => $queue,
            'queues_ahead' => $queuesAhead,
            'estimated_wait_minutes' => $estimatedWait,
        ]);
    }
}