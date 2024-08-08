<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    public function getMenuItems($menu_id)
    {

        $validator = Validator::make(["menu_id"=>$menu_id], [
            'menu_id' => 'required|integer|exists:menus,id',
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 400);


        $menuItems = MenuItem::where('menu_id', $menu_id)->get();
        return response()->json($menuItems);
    }

    public function getSingleMenuItem($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        if (!$menuItem)
            return response()->json(['error' => 'Menu item not found'], 404);

        return response()->json($menuItem);
    }

    public function createMenuItem(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'menu_id' => 'required|integer|exists:menus,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 400);

        // Authorization
        $menu = Menu::find($request->input('menu_id'));
        $restaurant_id = $menu->restaurant_id;
        if ($restaurant_id != $request['user']->restaurant_id)
            return response()->json(['error' => 'Menu item must belong to the same restaurant as the user'], 400);

        $menuItem = MenuItem::create($request->all());
        return response()->json($menuItem, 201);
    }

    public function updateMenuItem(Request $request, $id)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string|max:255',
            'price' => 'numeric',
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 400);

        //authorization

        $menuItem = MenuItem::find($id);
        $menu = Menu::findOrFail($menuItem->menu_id);
        if($request['user']->restaurant_id != $menu->restaurant_id)
            return response()->json(['error' => 'Menu item must belong to the same restaurant as the user'], 400);

        if (!$menuItem)
            return response()->json(['error' => 'Menu item not found'], 404);

        $menuItem->update($request->all());
        return response()->json($menuItem);
    }

    public function deleteMenuItem(Request $request, $id)
    {
        //authorization
        $menuItem = MenuItem::findOrFail($id);
        $menu = Menu::findOrFail($menuItem->menu_id);

        if($request['user']->role != 'admin' && $request['user']->restaurant_id != $menu->restaurant_id)
            return response()->json(['error' => 'Menu item must belong to the same restaurant as the user'], 400);

        if (!$menuItem)
            return response()->json(['error' => 'Menu item not found'], 404);

        $menuItem->delete();
        return response()->json(['message' => 'Menu item deleted successfully']);
    }
}
