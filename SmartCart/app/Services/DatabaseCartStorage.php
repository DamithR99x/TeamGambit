<?php

namespace App\Services;

use App\Models\CartStorage;
use Darryldecode\Cart\CartCollection;

class DatabaseCartStorage
{
    /**
     * Get cart data from the database.
     *
     * @param string $id
     * @return CartCollection
     */
    public function get($id)
    {
        $cart = CartStorage::find($id);
        
        return $cart ? new CartCollection(json_decode($cart->cart_data, true)) : new CartCollection();
    }

    /**
     * Store cart data in the database.
     *
     * @param string $id
     * @param CartCollection $cart
     * @return bool
     */
    public function put($id, $cart)
    {
        CartStorage::updateOrCreate(
            ['id' => $id],
            ['cart_data' => json_encode($cart)]
        );
        
        return true;
    }
}