<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\Business\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    public function dashboard()
    {
        try {
            return $this->dashboardService->dashboardData();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
