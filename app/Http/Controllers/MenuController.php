<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function getMenus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $restaurantId = $request->input('restaurant_id');
        $menus = Menu::where('restaurant_id', $restaurantId)->get();
        return response()->json($menus);
    }

    public function getMenu($id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json(['error' => 'Menu not found'], 404);
        }
        return response()->json($menu);
    }

    public function createMenu(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required|exists:restaurants,id',
            'title' => 'string|required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $menu = new Menu();
        $menu->title = $request->input('title');
        $menu->description = $request->input('description');
        $menu->restaurant_id = $request->input('restaurant_id');

        if($request['user']->restaurant_id != $request->input('restaurant_id')){
            abort(403);
        }

        $menu->save();
        return response()->json($menu, 201);
    }

    public function updateMenu(Request $request, $id)
    {

        $menu = Menu::findOrFail($id);
        if($request['user']->restaurant_id != $menu->restaurant_id){
            abort(403);
        }


        if($request->input('title'))
            $menu->title = $request->input('title');
        if($request->input('description'))
            $menu->description = $request->input('description');

        $menu->save();
        return response()->json($menu);
    }

    public function deleteMenu(Request $request , $id)
    {
        $menu = Menu::find($id);
        if (!$menu) {
            return response()->json(['error' => 'Menu not found'], 404);
        }
        if($request['user']->restaurant_id != $menu->restaurant_id){
            abort(403);
        }

        $menu->delete();
        return response()->json(['message' => 'Menu deleted successfully']);
    }}
