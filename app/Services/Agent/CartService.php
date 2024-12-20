<?php

namespace App\Services\Agent;

use Illuminate\Support\Str;
use App\Models\BusinessProductPrice;
use Illuminate\Support\Facades\Cache;

/**
 * Class CartService.
 */
class CartService
{
    public function cartListsOld()
    {
        $cartItems = Cache::get(cartKey(), []);
        $cartItemsArray = array_values($cartItems);
        $itemTotal = 0;
        foreach ($cartItemsArray as &$item) {
            $condition = [
                'business_id' => authAgentInfo()['business_id'],
                'product_id' => $item['product_id']
            ];
            $productPrice = BusinessProductPrice::where($condition)
                ->value('sale_price');

            if ($productPrice !== null) {
                $item['item_price'] = $productPrice;
//                $item['order_price'] = $productPrice * $item['quantity'];
                $productPrice = is_numeric($productPrice) ? (float)$productPrice : 0;
                $quantity = is_numeric($item['quantity']) ? (int)$item['quantity'] : 0;
                $item['order_price'] = $productPrice * $quantity;
                $itemTotal += $item['order_price'];
            } else {
                $item['item_price'] = 0;
                $item['order_price'] = 0;
            }
        }

        return response()->json([
            'item_total' => $itemTotal,
            'items' => $cartItemsArray,
        ]);
    }

    public function cartLists()
    {
        return agentCartItem();
    }

    protected function productExistsInCart($productId)
    {
        $cartItems = Cache::get(cartKey(), []);
        foreach ($cartItems as $item) {
            if ($item['product_id'] == $productId) {
                return $item;
            }
        }
        return false;
    }

    public function addToCart($request)
    {
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $size_id = $request->size_id ?? null;
        $color_id = $request->color_id ?? null;
        $existingItem = $this->productExistsInCart($product_id);
        if ($existingItem) {
            return $this->updateCartItem($existingItem['product_id'], $existingItem['quantity'] + 1);
        } else {
            return $this->addCartItem($product_id, $quantity, $size_id, $color_id);
        }
    }

    protected function addCartItem($product_id, $quantity, $size_id, $color_id)
    {
        $cartItems = Cache::get(cartKey(), []);
        $item = [
            'item_key' => Str::uuid(),
            'product_id' => $product_id,
            'quantity' => $quantity,
            'size_id' => $size_id,
            'color_id' => $color_id
        ];
        $cartItems[$product_id] = $item;
        //Cache::put(cartKey(), $cartItems, now()->addDays(15)); // Cache for 360 hours 15 days
        Cache::forever(cartKey(),$cartItems);
        return response()->json([
            'status' => 'success',
            'message' => 'Product has been added to your cart successfully!',
        ]);
    }

    public function updateCartQuantity()
    {
        $action_type = request()->get('action_type') ?? 'increment';
        $productId = request()->get('product_id');
        $existingItem = $this->productExistsInCart($productId);
        if (!$existingItem) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid cart item',
            ]);
        }
        $newQuantity = 0;
        if ($action_type === "increment") {
            $newQuantity = $existingItem['quantity'] + 1;
        } elseif ($action_type === "decrement") {
            if ($existingItem['quantity'] == 1) {
                return $this->removeItem($productId);
//                return response()->json([
//                    'status' => 'error',
//                    'message' => 'Invalid cart quantity!',
//                ]);
            }
            $newQuantity = $existingItem['quantity'] - 1;
        }
        $this->updateCartItem($productId, $newQuantity);
        return true;
    }

    protected function updateCartItem($productId, $newQuantity)
    {
        $cartItems = Cache::get(cartKey(), []);
        if (isset($cartItems[$productId])) {
            $cartItems[$productId]['quantity'] = $newQuantity;
            //Cache::put(cartKey(), $cartItems, now()->addDays(15));
            Cache::forever(cartKey(),$cartItems);
            return response()->json([
                'status' => 'success',
                'message' => 'Item has been added successfully!',
            ]);
        }
    }

    function removeItemFromCart()
    {
        $productId = request()->get('product_id');
        return $this->removeItem($productId);
    }

    protected function removeItem($productId)
    {
        $cartItems = Cache::get(cartKey(), []);
        if (isset($cartItems[$productId])) {
            unset($cartItems[$productId]);
            //Cache::put(cartKey(), $cartItems, now()->addDays(15));
            Cache::forever(cartKey(),$cartItems);
            return response()->json([
                'status' => 'success',
                'message' => 'Item has been removed from cart',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid cart item',
            ]);
        }
    }

    function clearCart()
    {
        Cache::forget(cartKey());
        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared successfully!',
        ]);
    }
}
