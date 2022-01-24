<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Product;
use App\Models\Order;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function __construct() {
        $this->middleware('api');
    }

    public function order(Request $request) {
        $user = $this->guard()->user();

        $input = $request->json()->all();
        $productId = $input['product_id'];
        $quantity = $input['quantity'];

        $product = Product::find($productId);
        if(!$product) {
            return response()->json([
                'message' => 'Product does not exists'
            ], 404);
        }
        if($product->available_stock < $quantity) {
            return response()->json([
                'message' => 'Failed to order this product due to unavailability of the stock',
            ], 400);
        }
        
        $order = Order::create([
            'user_id' => $user->getKey(),
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
        $product->available_stock -= $quantity;
        
        $order->save();
        $product->save();

        return response()->json([
            'message' => 'You have successfully ordered this product',
        ], 201);
    }

    protected function guard()
    {
        return Auth::guard();

    }

}