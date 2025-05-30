<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Session Key
    |--------------------------------------------------------------------------
    |
    | This value is the key used in the session to store the cart data.
    |
    */
    'session_key' => 'shopping_cart',

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Database Connection
    |--------------------------------------------------------------------------
    |
    | This value overrides the default database connection used when storing
    | cart data to the database. You can override this value if you need
    | to use a different connection for storing cart data.
    |
    */
    'database' => [
        'connection' => null,
        'table' => 'cart_storage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Model for Users
    |--------------------------------------------------------------------------
    |
    | This value is the namespace of the user model in your project.
    |
    */
    'user_model' => 'App\Models\User',

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Format
    |--------------------------------------------------------------------------
    |
    | This value determines the format of the cart when it's converted to 
    | different formats such as array or JSON.
    |
    */
    'format' => [
        'decimals' => 2,
        'decimal_separator' => '.',
        'thousand_separator' => ',',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Persistent Storage
    |--------------------------------------------------------------------------
    |
    | Set to true if you want to store the cart data in the database
    | after the user logs out.
    |
    */
    'persistent_storage' => false,
];