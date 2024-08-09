<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $customer_id = $request['user']->id;
        $restaurant_id = $request->input('restaurant_id');

        $cart = Cart::where('user_id', $customer_id)
                    ->where('restaurant_id', $restaurant_id)
                    ->first();

        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
            return response()->json($cartItems);
        } else {
            $cart = new Cart();
            $cart->user_id = $customer_id;
            $cart->restaurant_id = $restaurant_id;
            $cart->save();
            return response()->json([]);
        }
    }

    /**
     * Update or add a cart item.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateCartItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $customer_id = $request['user']['id'];
        $restaurant_id = $request->input('restaurant_id');

        $menu_item_id = $request->input('menu_item_id');
        $menuItem = MenuItem::findOrFail($menu_item_id);
        $menu = Menu::findOrFail($menuItem->menu_id);

        $price = $menuItem->price;
        $quantity = $request->input('quantity');

        // Check if menu item belongs to restaurant
        if ($menu->restaurant_id != $restaurant_id) {
            return response()->json(['error' => 'Menu Item doesn\'t belong to this restaurant'], 400);
        }

        $cart = Cart::firstOrCreate([
            'user_id' => $customer_id,
            'restaurant_id' => $restaurant_id,
        ]);

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('menu_item_id', $menu_item_id)
                            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $quantity,
                'item_price' => $price,
                'total_price' => $price * $quantity,
            ]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => $menu_item_id,
                'quantity' => $quantity,
                'item_price' => $price,
                'total_price' => $price * $quantity,
            ]);
        }

        return response()->json(['message' => 'Cart updated successfully']);
    }
}
