<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Services\Business\OrderService;

class OrderController extends Controller
{
    protected  OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function orderList() {
        try {
            return $this->orderService->orderList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderDetails($order_id) {
        try {
            return $this->orderService->orderDetails($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function confirmOrder($order_id) {
        try {
            return $this->orderService->confirmOrder($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function cancelOrder($order_id) {
        try {
            return $this->orderService->cancelOrder($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadInvoice($order_id) {
        try {
            $invoice_type = request()->get('invoice_type') ?? 'order';
            return $this->orderService->downloadInvoice($order_id,$invoice_type);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderStatusList() {
        try {
            return $this->orderService->orderStatusList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

}
