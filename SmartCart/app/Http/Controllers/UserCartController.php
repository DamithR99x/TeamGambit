<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class UserCartController extends Controller
{
    /**
     * Display the user's cart contents.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get cart for the current user
        $userId = Auth::id();
        $cartItems = Cart::session($userId)->getContent();
        $cartTotal = Cart::session($userId)->getTotal();
        
        return view('cart.user', compact('cartItems', 'cartTotal'));
    }

    /**
     * Add an item to the user's cart.
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
        $userId = Auth::id();
        
        // Check if the product is available
        if ($product->status !== 'active') {
            return redirect()->back()->with('error', 'This product is not available.');
        }
        
        // Check if we have enough stock
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Not enough stock available.');
        }

        // Add the item to the user's cart
        Cart::session($userId)->add([
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
     * Update item quantities in the user's cart.
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

        $userId = Auth::id();

        foreach ($request->quantities as $itemId => $quantity) {
            // Get the cart item to check associated product stock
            $item = Cart::session($userId)->get($itemId);
            if (!$item) continue;
            
            $product = Product::find($item->id);
            if (!$product) continue;
            
            // Check if we have enough stock
            if ($product->stock_quantity < $quantity) {
                return redirect()->back()->with('error', "Not enough stock available for {$product->name}.");
            }
            
            Cart::session($userId)->update($itemId, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ]);
        }

        return redirect()->route('user.cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove an item from the user's cart.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        $userId = Auth::id();
        Cart::session($userId)->remove($id);
        
        return redirect()->route('user.cart.index')->with('success', 'Item removed from cart.');
    }

    /**
     * Clear all items from the user's cart.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear()
    {
        $userId = Auth::id();
        Cart::session($userId)->clear();
        
        return redirect()->route('user.cart.index')->with('success', 'Cart cleared successfully.');
    }

    /**
     * Transfer the guest cart to the user cart after login.
     *
     * @return void
     */
    public static function transferGuestCart()
    {
        if (!Auth::check()) return;
        
        $userId = Auth::id();
        $guestCartItems = Cart::getContent();
        
        if ($guestCartItems->isEmpty()) return;
        
        foreach ($guestCartItems as $item) {
            // Add each guest cart item to the user cart
            Cart::session($userId)->add([
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'attributes' => $item->attributes,
                'associatedModel' => $item->associatedModel,
            ]);
        }
        
        // Clear the guest cart
        Cart::clear();
    }
} 