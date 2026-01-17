<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')->firstOrCreate(['user_id' => auth()->id()]);

        $items = $cart->items;
        $total = $cart->items->sum(function($item) {
            return $item->qty * $item->price_at_time;
        });

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => [
                'items' => $items,
                'cart_total' => (string) number_format($total, 2, '.', '')
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->qty) {
            return response()->json(['status' => false, 'message' => 'Insufficient stock available.'], 422);
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $request->qty);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price_at_time' => $product->price
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Item added to cart successfully.'], 201);
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        if (!$cart) {
            return response()->json(['status' => false, 'message' => 'Cart not found'], 404);
        }

        $item = $cart->items()->where('product_id', $productId)->firstOrFail();
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'This product does not exist in your cart.'], 404);
        }

        if ($item->product->stock < $request->qty) {
            return response()->json(['status' => false, 'message' => 'Requested quantity exceeds stock.'], 422);
        }

        $item->update(['qty' => $request->qty]);

        return response()->json(['status' => true, 'message' => 'Cart quantity updated.']);
    }

    public function destroy($productId)
    {
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        $deleted = $cart->items()->where('product_id', $productId)->delete();

        if (!$deleted) {
            return response()->json(['status' => false, 'message' => 'Item not found in cart.'], 404);
        }

        return response()->json(['status' => true, 'message' => 'Item removed from cart.']);
    }

    public function checkout()
    {
        return DB::transaction(function () {
            $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your cart is empty.'
                ], 400);
            }

            foreach ($cart->items as $item) {
                if ($item->qty > $item->product->stock) {
                    return response()->json([
                        'status' => false,
                        'message' => "Insufficient stock for: {$item->product->name}. Only {$item->product->stock} remaining.",
                        'data' => [
                            'product_id' => $item->product_id
                        ]
                    ], 422);
                }
            }

            foreach ($cart->items as $item) {
                $item->product->decrement('stock', $item->qty);
            }

            $cart->items()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Checkout successful. Thank you for your order!'
            ], 200);
        });
    }
}
