# 🧵 Laravel Queue Jobs — Complete Guide

This guide explains how to set up and manage queue jobs in Laravel using the `database` driver.

---

## ✅ 1. Configure `.env`

Update your `.env` file to use the database queue:

```bash
    QUEUE_CONNECTION=database
```
2. 🏗️ Set Up the Queue Table
Run the following commands:

```bash
php artisan queue:table  # Generate the migration for the jobs table
php artisan migrate         # Apply migrations
```
3. 🧱 Create a Job
Use Artisan to create a new job class:

```bash
php artisan make:job ProcessPodcast
```
4. ✍️ Dispatch a Job
In your controller or route file:

```php
use App\Jobs\ProcessPodcast;

// Dispatch immediately
ProcessPodcast::dispatch(1);

// Dispatch with delay
ProcessPodcast::dispatch(2)->delay(now()->addMinutes(1));
```
5. ⚙️ Run the Queue Worker
Start processing jobs in a terminal:

```bash
php artisan queue:work
Keep this running while working locally or use Supervisor in production.
```
6. ⏳ Job Timeout
Set the timeout property in your job to limit max execution time (in seconds):

```php
class ProcessPodcast implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 5; // seconds
    public $podcastId;

    public function __construct($podcastId)
    {
        $this->podcastId = $podcastId;
        Log::info("Constructor podcastId = $podcastId");
    }

    public function handle(): void
    {
        sleep(10); // Simulated long task
        Log::info("Processing podcast : $this->podcastId");
    }
}
```
7. ♻️ Retry & Failure Handling
🔁 Retry Attempts
Set the tries property to control how many times a job is retried:

```php
public $tries = 3;
```
❌ Handle Failures
Add a failed() method to handle exceptions:

```php
public function failed(\Throwable $exception)
{
    Log::error("Failed processing podcast {$this->podcastId}: " . $exception->getMessage());
}
```
8. 🔄 Retry Failed Jobs
To retry failed jobs from the failed_jobs table:

```php
php artisan queue:retry all
```
To delete failed jobs:

```php
php artisan queue:flush
```
📌 Best Practices
✅ Always use tries and timeout in jobs.

✅ Use delay() to reduce server load during peak traffic.

✅ Use the failed() method to log or notify on failures.

✅ Use php artisan queue:restart after deployment.

✅ Use Supervisor to manage queue workers in production.

📎 Useful Commands
```bash
php artisan queue:table     # Create jobs table
php artisan make:job MyJob  # Create new job
php artisan queue:work      # Start worker
php artisan queue:restart   # Restart workers (after deploy)
php artisan queue:retry all # Retry failed jobs
php artisan queue:flush     # Clear failed jobs
```
