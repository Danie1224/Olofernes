<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechstoreController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => 'ok',
            'resources' => [
                'customers' => url('/api/techstore/customers'),
                'products' => url('/api/techstore/products'),
                'orders' => url('/api/techstore/orders'),
                'order_items' => url('/api/techstore/order-items'),
                'shippings' => url('/api/techstore/shippings'),
                'vouchers' => url('/api/techstore/vouchers'),
                'add-to-cart' => url('/api/techstore/add-to-cart'),
                'user' => url('/api/techstore/user'),
            ],
        ]);
    }

    public function user()  
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();   
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'remember_token' => $user->remember_token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);
    }
}

