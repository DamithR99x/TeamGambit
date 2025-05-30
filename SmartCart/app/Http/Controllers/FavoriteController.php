<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('product')->latest()->get();
        
        return view('favorites.index', compact('favorites'));
    }
    
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        $user = Auth::user();
        $productId = $request->product_id;
        
        $existing = $user->favorites()->where('product_id', $productId)->first();
        
        if ($existing) {
            $existing->delete();
            $message = 'Product removed from favorites.';
        } else {
            $user->favorites()->create([
                'product_id' => $productId,
            ]);
            $message = 'Product added to favorites.';
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    public function remove($id)
    {
        $favorite = Favorite::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $favorite->delete();
        
        return redirect()->route('favorites.index')->with('success', 'Product removed from favorites.');
    }
} 