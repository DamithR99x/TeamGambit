<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cart items for the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate the subtotal of the cart.
     */
    public function getSubtotal()
    {
        return $this->items->sum(function ($item) {
            return $item->getSubtotal();
        });
    }

    /**
     * Get the total number of items in the cart.
     */
    public function getTotalItems()
    {
        return $this->items->sum('quantity');
    }

    public function getTotal()
    {
        $subtotal = $this->getSubtotal();
        $tax = $subtotal * 0.07; // 7% tax
        $shipping = 0; // Free shipping for now
        
        return $subtotal + $tax + $shipping;
    }
}
