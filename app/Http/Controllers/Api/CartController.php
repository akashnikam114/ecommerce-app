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
    /**
     * GET /api/cart
     * Returns all items in user's cart plus the grand total.
     */
    public function index()
    {
        // Eager load product to avoid N+1 issues
        $cart = Cart::with('items.product')->firstOrCreate(['user_id' => auth()->id()]);

        $items = $cart->items;
        $total = $items->sum(fn($item) => $item->qty * $item->price_at_time);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items,
                'cart_total' => round($total, 2)
            ]
        ], 200);
    }

    /**
     * POST /api/cart/items
     * Adds an item to the cart or increments quantity if it exists.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock before adding
        if ($product->stock < $request->qty) {
            return response()->json(['message' => 'Insufficient stock available.'], 422);
        }

        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Logic to merge duplicates: update existing or create new
        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $request->qty);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price_at_time' => $product->price // Capture price at the moment of adding
            ]);
        }

        return response()->json(['message' => 'Item added to cart successfully.'], 201);
    }

    /**
     * PATCH /api/cart/items/{product_id}
     * Updates the quantity of a specific item in the cart.
     */
    public function update(Request $request, $productId)
    {
        $request->validate(['qty' => 'required|integer|min:1']);

        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        $item = $cart->items()->where('product_id', $productId)->firstOrFail();

        // Validate stock for the new quantity
        if ($item->product->stock < $request->qty) {
            return response()->json(['message' => 'Requested quantity exceeds stock.'], 422);
        }

        $item->update(['qty' => $request->qty]);

        return response()->json(['message' => 'Cart quantity updated.']);
    }

    /**
     * DELETE /api/cart/items/{product_id}
     * Removes a specific product from the cart.
     */
    public function destroy($productId)
    {
        $cart = Cart::where('user_id', auth()->id())->firstOrFail();
        $deleted = $cart->items()->where('product_id', $productId)->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Item not found in cart.'], 404);
        }

        return response()->json(['message' => 'Item removed from cart.']);
    }

    public function checkout()
    {
        return DB::transaction(function () {
            // 1. Get user cart with items and product details
            $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

            // Check if cart exists and has items
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your cart is empty.'
                ], 400);
            }

            // 2. Main Filter: Validate Stock for every item in the cart
            foreach ($cart->items as $item) {
                if ($item->qty > $item->product->stock) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Insufficient stock for: {$item->product->name}. Only {$item->product->stock} remaining.",
                        'product_id' => $item->product_id
                    ], 422);
                }
            }

            // 3. Deduct Stock and Log Price
            foreach ($cart->items as $item) {
                // Use decrement to ensure atomic database update
                $item->product->decrement('stock', $item->qty);
            }

            // 4. Clear the Cart items
            $cart->items()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout successful. Thank you for your order!'
            ], 200);
        });
    }
}
