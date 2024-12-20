<?php

namespace App\Jobs;

use App\Events\SaveNotification;
use App\Models\JobLog;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderTracking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $insertedCount = 0;
    public $timeout = 3600; // Increase the timeout to 1 hour

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        ini_set('memory_limit', '2048M'); // Increase memory limit if needed
        set_time_limit(0); // Remove the execution time limit
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = Order::whereNotNull('dcs_order_no')->whereNull('tracking_number')->get();
        Log::info("Order tracking job started at " . now() . " Total orders" . $orders->count());
        $this->jobLog = JobLog::create([
            'job_name' => 'Order tracking',
            'started_at' => now(),
            'status' => 'started'
        ]);
        foreach ($orders as $order) {
            if ($order->dcs_order_no == null) {
                continue;
            }
            $tracking_info = Order::trackOrder($order->id);
            $res_code = $tracking_info['code'] ?? null;
            if (isset($res_code) && $res_code == 225) {
                $error_message = $tracking_info['errorTekst'];
                Log::error("Result of order tracking : $error_message");
            } else {
                $tracking_number = $tracking_info['orderinfo']['trackingNumbers']['item'];
                $carrier = $tracking_info['orderinfo']['carrier'];
                Order::find($order->id)->update(['tracking_number' => $tracking_number, 'carrier' => $carrier, 'order_status' => 7, 'last_updated_at' => now()]);
                $this->insertedCount++;
                $this->sendEmailNotification($order);
            }
        }
        Log::info("Order tracking job end at " . now());
        $this->jobLog->update([
            'ended_at' => now(),
            'status' => 'completed'
        ]);
        $notification = new Notification();
        $notification->notification_for = 'admin';
        $notification->event_type = 'order-tracking';
        $notification->user_id = null;
        $notification->message = "Total " . $this->insertedCount . " orders has been tracked successfully!";
        $notification->seen_status = false;
        $notification->save();
        Log::info("Order Tracking job result event with: $notification");
        event(new SaveNotification($notification));
    }

    protected function sendEmailNotification($order)
    {
        // Send mail to tellecto
        $tellecto_mail_data = [
            'activity_type' => 'order_tracking_update_to_admin',
            'to_email' => 'order@tellecto.se',
            'to_name' => "Tellecto Admin",
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => $order
        ];
        sendMail($tellecto_mail_data);
        // Send mail to agent
        $mail_data = [
            'activity_type' => 'order_tracking_update_to_agent',
            'to_email' => $order->customer_email,
            'to_name' => $order->customer_name,
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => $order
        ];
        sendMail($mail_data);
        // Send mail to business
        $mail_data = [
            'activity_type' => 'order_tracking_update_to_business',
            'to_email' => $order->business->email,
            'to_name' => $order->business->name,
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => $order
        ];
        sendMail($mail_data);
    }
}
