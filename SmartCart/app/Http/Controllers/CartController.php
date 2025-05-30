<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    /**
     * Display the cart contents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        
        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Add an item to the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if the product is available
        if ($product->status !== 'active') {
            return redirect()->back()->with('error', 'This product is not available.');
        }
        
        // Check if we have enough stock
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Add the item to the cart
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->effective_price,
            'quantity' => $request->quantity,
            'attributes' => [
                'image' => $product->primaryImage ? $product->primaryImage->url : null,
                'slug' => $product->slug,
            ],
            'associatedModel' => $product,
        ]);

        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }

    /**
     * Update item quantities in the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        foreach ($request->quantities as $itemId => $quantity) {
            // Get the cart item to check associated product stock
            $item = Cart::get($itemId);
            if (!$item) continue;
            
            $product = Product::find($item->id);
            if (!$product) continue;
            
            // Check if we have enough stock
            if ($product->stock_quantity < $quantity) {
                return redirect()->back()->with('error', "Not enough stock available for {$product->name}.");
            }
            
            Cart::update($itemId, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the cart.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        Cart::remove($id);
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    /**
     * Clear all items from the cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        Cart::clear();
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
} 