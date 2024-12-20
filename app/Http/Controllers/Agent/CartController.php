<?php

namespace App\Http\Controllers\Agent;

use App\Services\Agent\CartService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Agent\CartRequest;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(cartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function cartLists()
    {
        try {
            return $this->cartService->cartLists();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function addToCart(CartRequest $request)
    {
        try {
            return $this->cartService->addToCart($request);
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function removeItemFromCart()
    {
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'errors' => $validator->messages(),
            ]);
        }
        try {
            return $this->cartService->removeItemFromCart();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function updateCartQuantity()
    {
        $validator = Validator::make(request()->all(), [
            'action_type' => 'required|in:increment,decrement',
            'product_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'errors',
                'errors' => $validator->messages(),
            ]);
        }
        try {
            return $this->cartService->updateCartQuantity();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }

    public function clearCart()
    {
        try {
            return $this->cartService->clearCart();
        } catch (\Exception $exception) {
            return exceptionResponse($exception->getMessage());
        }
    }
}
