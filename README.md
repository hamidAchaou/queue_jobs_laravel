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

9.  Max exceptions, backoff, faild function
    
```php
 public $maxExceptions = 3; // Max number of exceptions before failing
 public $backoff = [2, 4]; // Delay in seconds before retrying

 public function failed(\Exception $exception): void
 {
    Log::error("Podcast processing failed for ID: {$this->podcastId}. Error: {$exception->getMessage()}");
 }
```

10. 🔁 Release and Retry After
You can use the release() method within the handle() method to retry a job manually after a delay if a certain condition is not met (for example, a service is unavailable or an external API failed temporarily).

🧪 Example:
```php
    public function handle(): void
    {
        try {
            // Simulate condition, e.g. external service not ready
            if (!$this->externalServiceAvailable()) {
                Log::warning("Service unavailable. Retrying podcast ID: {$this->podcastId}...");
                $this->release(30); // Retry after 30 seconds
                return;
            }

            // Process the podcast
            Log::info("Processing podcast: {$this->podcastId}");
            // ... your logic

        } catch (\Exception $e) {
            Log::error("Error processing podcast {$this->podcastId}: " . $e->getMessage());
            throw $e; // This will allow Laravel to retry based on $tries and $backoff
        }
    }

    protected function externalServiceAvailable(): bool
    {
        // Simulate API check or DB readiness
        return rand(0, 1) === 1; // 50% chance to fail for demo purposes
    }
```

✅ When to use release():
Waiting for an API to become available.

Waiting for a database lock to release.

Dealing with race conditions.

Retrying tasks without immediately failing the job.

11. 🥇 Priorities in Queue
Laravel allows you to assign priorities to your jobs by specifying different queue names, then processing them in priority order.

🛠️ Dispatch Jobs with Priority
You can send jobs to different queues using onQueue():
🔄 Example Use Case
You have:

emails queue → High priority

reports queue → Low priority
```php
 SendNewsletter::dispatch()->onQueue('emails');
 GenerateMonthlyReport::dispatch()->onQueue('reports');
```
🏃 Run Worker with Multiple Queues (Priority Order)
```bash
 php artisan queue:work --queue=emails,reports
```
This ensures time-sensitive jobs are processed first, improving app responsiveness.


12. bus cachinis and bus batch
13. Finaly, then and catch
14. chaisns in batchs and vice versa
15. cach lock

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
