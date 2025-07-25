<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPodcast implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 5; // Max time in seconds before job is killed

    public $tries = 3; // Number of attempts before failing
    public $maxExceptions = 3; // Max number of exceptions before failing
    public $backoff = [2, 4]; // Delay in seconds before retrying
    
    public $podcastId;
    
    public function __construct($podcastId)
    {
        $this->podcastId = $podcastId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(10); // Simulate a delay longer than the timeout
        Log::info("Processing podcast : $this->podcastId");
    }

    public function failed(\Exception $exception): void
    {
        Log::error("Podcast processing failed for ID: {$this->podcastId}. Error: {$exception->getMessage()}");
    }
}