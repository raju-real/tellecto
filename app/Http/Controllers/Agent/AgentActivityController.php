<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\OrderRequest;
use App\Services\Agent\AgentActivityService;

class AgentActivityController extends Controller
{
    protected AgentActivityService $agentActivityService;

    public function __construct(AgentActivityService $agentActivityService)
    {
        $this->agentActivityService = $agentActivityService;
    }

    public function agentProduct()
    {
        try {
            return $this->agentActivityService->agentProduct();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function vatPolicy()
    {
        try {
            return $this->agentActivityService->vatPolicy();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function deliveryCharges()
    {
        try {
            return $this->agentActivityService->deliveryCharges();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function nearestServiceByAddress()
    {
        try {
            return $this->agentActivityService->nearestServiceByAddress();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function billingAddress()
    {
        try {
            return $this->agentActivityService->billingAddress();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function submitOrder(OrderRequest $request)
    {
        try {
            return $this->agentActivityService->submitOrder($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderList()
    {
        try {
            return $this->agentActivityService->orderList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderDetails($order_id)
    {
        try {
            return $this->agentActivityService->orderDetails($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadInvoice($order_id)
    {
        try {
            return $this->agentActivityService->downloadInvoice($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
