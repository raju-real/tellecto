<?php

namespace App\Services\Agent;

use App\Models\BusinessProductPrice;
use App\Models\Cart;
use Illuminate\Support\Str;

/**
 * Class TableCartService.
 */
class TableCartService
{
    public function cartLists()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get(); // Assuming authenticated users

        $itemTotal = 0;
        foreach ($cartItems as &$item) {
            $productPrice = BusinessProductPrice::where('product_id', $item->product_id)
                ->where('business_id', authAgentInfo()['business_id'])
                ->value('sale_price');

            if ($productPrice !== null) {
                $item->item_price = $productPrice;
                $item->order_price = $productPrice * $item->quantity;
                $itemTotal += $item->order_price;
            } else {
                $item->item_price = 0;
                $item->order_price = 0;
            }
        }

        return response()->json([
            'item_total' => $itemTotal,
            'items' => $cartItems,
        ]);
    }

    protected function addCartItem($product_id, $quantity, $size_id, $color_id)
    {
        $item = Cart::create([
            'item_key' => Str::uuid(),
            'product_id' => $product_id,
            'quantity' => $quantity,
            'size_id' => $size_id,
            'color_id' => $color_id,
            'user_id' => auth()->id(), // for authenticated users
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product has been added to your cart successfully!',
        ]);
    }

    protected function updateCartItem($productId, $newQuantity)
    {
        Cart::where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->update(['quantity' => $newQuantity]);

        return response()->json([
            'status' => 'success',
            'message' => 'Quantity updated successfully!',
        ]);
    }

    protected function removeItem($productId)
    {
        Cart::where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Item has been removed from the cart',
        ]);
    }

    function clearCart()
    {
        Cart::where('user_id', auth()->id())->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Cart cleared successfully!',
        ]);
    }


}
