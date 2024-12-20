<?php

namespace App\Jobs;

use App\Mail\SendNotificationEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // Increase the timeout to 1 hour
    protected $mail_data = [];


    /**
     * Create a new job instance.
     */
    public function __construct($mail_data)
    {
        ini_set('memory_limit', '2048M'); // Increase memory limit if needed
        set_time_limit(0); // Remove the execution time limit
        $this->mail_data = $mail_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info($this->mail_data);
        Mail::to($this->mail_data['to_email'])->send(new SendNotificationEmail($this->mail_data));
        Log::info("Mail call from job...");
    }
}
