<?php

namespace App\Services\Admin;

use App\Models\Agent;
use App\Models\Category;
use App\Models\Order;

/**
 * Class DashboardService.
 */
class DashboardService
{
    public function dashboardData()
    {
        $response = [];
        $response['total_order_amount_this_month'] = round(Order::notPending()->thisYear()->thisMonth()->sum('total_excluding_vat_admin'));
        $response['new_orders'] = Order::confirmed()->count();
        $response['total_agents'] = Agent::whereStatus(1)->count();
        $total_sale_amount = round(Order::notPending()->sum('total_excluding_vat_admin'));
        $response['total_sale_amount'] = $total_sale_amount;
        $response['average_sales'] =
        $response['valuable_agents'] = Agent::whereStatus(1)->select('first_name','last_name','email','image')->get();
        $response['top_categories'] = Category::where('name','!=',"Uncategorized")->select('name','thumbnail')->get();
        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }
}
