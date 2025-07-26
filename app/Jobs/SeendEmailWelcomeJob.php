<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SeendEmailWelcomeJob implements ShouldQueue
{
    use Queueable;

    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Send welcome email
        try {
            Mail::to($this->user->email)->send(new \App\Mail\WelcomeEmail($this->user));
        } catch (\Exception $e) {
            dd('Mail sending failed: ' . $e->getMessage());
        }
    }
}