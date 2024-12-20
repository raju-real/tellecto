<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;
    protected $table = "order_logs";

    public function order() {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function agent() {
        return $this->belongsTo(Agent::class,'user_id','id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public static function saveOrderLog($order_id,$user_id,$order_status) {
        $order_log = new OrderLog();
        $order_log->order_id = $order_id;
        $order_log->user_id = $user_id;
        $order_log->order_status = $order_status;
        $order_log->activity_name = $order_status == 0 ? "Placed by Agent" : Order::orderStatusTitle($order_status);
        $order_log->save();
    }
}
