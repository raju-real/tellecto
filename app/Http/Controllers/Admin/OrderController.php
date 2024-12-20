<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Services\Admin\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function orderStatusList() {
        try {
            return $this->orderService->orderStatusList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderList()
    {
        try {
            return $this->orderService->orderList();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderDetails($order_id)
    {
        try {
            return $this->orderService->orderDetails($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function changeDeliveryOption(OrderUpdateRequest $request, $order_id)
    {
        try {
            return $this->orderService->changeDeliveryOption($request, $order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function approveOrder($order_id)
    {
        try {
            return $this->orderService->approveOrder($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function declineOrder($order_id)
    {
        try {
            return $this->orderService->declineOrder($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function orderLogs($order_id)
    {
        try {
            return $this->orderService->orderLogs($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function reserveOrder()
    {
        try {
            return $this->orderService->reserveOrder();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function trackOrder()
    {
        try {
            return $this->orderService->trackOrder();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadAdminInvoice($order_id) {
        try {
            $invoice_type = request()->get('invoice_type');
            return $this->orderService->downloadAdminInvoice($order_id,$invoice_type);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadBusinessInvoice($order_id) {
        try {
            return $this->orderService->downloadBusinessInvoice($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function downloadAgentInvoice($order_id) {
        try {
            return $this->orderService->downloadAgentInvoice($order_id);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function shippingMethods() {
        try {
            return $this->orderService->shippingMethods();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
