<?php

namespace App\Console\Commands;

use App\Events\SaveNotification;
use App\Models\JobLog;
use App\Models\Notification;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TrackOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $insertedCount = 0;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');

        $orders = Order::whereNotNull('dcs_order_no')->whereNull('tracking_number')->get();
        Log::info("Order tracking job started at " . now() . " Total tracked orders " . $orders->count());
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
                Log::info("Tracking for " . $order->dcs_order_no);

                if (isset($tracking_info['orderinfo']['trackingNumbers']['item']) || isset($tracking_info['orderinfo'][0]['trackingNumbers']['item'])) {
                    $tracking_number = $tracking_info['orderinfo']['trackingNumbers']['item'] ?? $tracking_info['orderinfo'][0]['trackingNumbers']['item'];
                    if (!is_array($tracking_number)) {
                        $tracking_number = [$tracking_number]; // Convert string or number to an array
                    }
                    $carrier = $tracking_info['orderinfo']['carrier'] ?? $tracking_info['orderinfo'][0]['carrier'] ?? null; // Use null coalescing operator in case 'carrier' is missing.
                    Order::find($order->id)->update([
                        'tracking_number' => $tracking_number,
                        'carrier' => $carrier,
                        'order_status' => 7,
                        'last_updated_at' => now(),
                    ]);
                    $this->insertedCount++;
                } else {
                    Log::warning("Tracking number not found for order " . $order->dcs_order_no);
                }
                // Send email notification
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
            'view_file' => 'mail.order.layouts',
            'to_email' => 'order@tellecto.se',
            'to_name' => "Tellecto Admin",
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => Order::find($order->id)
        ];
        sendMail($tellecto_mail_data);
        // Send mail to agent
        $mail_data = [
            'activity_type' => 'order_tracking_update_to_admin',
            'view_file' => 'mail.order.layouts',
            'to_email' => $order->customer_email,
            'to_name' => $order->customer_name,
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => Order::find($order->id)
        ];
        sendMail($mail_data);
        // Send mail to business
        $mail_data = [
            'activity_type' => 'order_tracking_update_to_admin',
            'view_file' => 'mail.order.layouts',
            'to_email' => $order->business->email,
            'to_name' => $order->business->name,
            'subject' => 'Order #' . $order->tellecto_order_no . ' Tracking Update - Tellecto.se',
            'order' => Order::find($order->id)
        ];
        sendMail($mail_data);
    }
}
