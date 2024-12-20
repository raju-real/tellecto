<?php

namespace App\Services\Business;

/**
 * Class DashboardService.
 */
class DashboardService
{
    public function dashboardData()
    {
        $response = [];
        return response()->json([
            'status' => 'success',
            'data' => $response
        ]);
    }
}
