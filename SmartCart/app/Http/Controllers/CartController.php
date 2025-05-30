<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = null;
        
        if (Auth::check()) {
            $cart = Auth::user()->cart;
        } elseif (session()->has('cart_id')) {
            $cart = Cart::find(session('cart_id'));
        }
        
        return view('cart.index', compact('cart'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is in stock
        if ($product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', 'Sorry, the requested quantity is not available.');
        }
        
        // Get or create cart
        $cart = $this->getOrCreateCart();
        
        // Check if product is already in cart
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock_quantity) {
                $newQuantity = $product->stock_quantity;
            }
            
            $cartItem->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            // Add new item
            $price = $product->discount_price ?? $product->price;
            
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }
        
        return redirect()->back()->with('success', 'Product added to cart successfully.');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Check if the cart belongs to the current user or session
        $cart = $this->getCart();
        
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return redirect()->route('cart.index')->with('error', 'Invalid cart item.');
        }
        
        // Check if product is in stock
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return redirect()->route('cart.index')->with('error', 'Sorry, the requested quantity is not available.');
        }
        
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);
        
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);
        
        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        // Check if the cart belongs to the current user or session
        $cart = $this->getCart();
        
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            return redirect()->route('cart.index')->with('error', 'Invalid cart item.');
        }
        
        $cartItem->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
    
    public function clear()
    {
        $cart = $this->getCart();
        
        if ($cart) {
            $cart->items()->delete();
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }
    
    private function getCart()
    {
        if (Auth::check()) {
            return Auth::user()->cart;
        } elseif (session()->has('cart_id')) {
            return Cart::find(session('cart_id'));
        }
        
        return null;
    }
    
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            
            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => Auth::id(),
                    'session_id' => session()->getId(),
                ]);
                
                // If there was a session cart, merge it with the user cart
                if (session()->has('cart_id')) {
                    $sessionCart = Cart::find(session('cart_id'));
                    
                    if ($sessionCart && $sessionCart->items->count() > 0) {
                        foreach ($sessionCart->items as $item) {
                            $existingItem = $cart->items()->where('product_id', $item->product_id)->first();
                            
                            if ($existingItem) {
                                $existingItem->update([
                                    'quantity' => $existingItem->quantity + $item->quantity,
                                ]);
                            } else {
                                $cart->items()->create([
                                    'product_id' => $item->product_id,
                                    'quantity' => $item->quantity,
                                    'price' => $item->price,
                                ]);
                            }
                        }
                        
                        $sessionCart->delete();
                    }
                }
            }
            
            return $cart;
        } else {
            // For guests, use session ID to track cart
            $sessionId = session()->getId();
            
            if (session()->has('cart_id')) {
                $cart = Cart::find(session('cart_id'));
                
                if ($cart) {
                    // Update session ID if it has changed
                    if ($cart->session_id !== $sessionId) {
                        $cart->update(['session_id' => $sessionId]);
                    }
                    
                    return $cart;
                }
            }
            
            // Create new cart
            $cart = Cart::create([
                'session_id' => $sessionId,
            ]);
            
            session(['cart_id' => $cart->id]);
            
            return $cart;
        }
    }
} 