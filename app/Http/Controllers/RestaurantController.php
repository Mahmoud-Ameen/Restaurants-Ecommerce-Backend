<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return response()->json(['restaurants' => $restaurants], 200);
    }

    public function search(Request $request)
    {

        // If searching by id
        $id = $request->input('id');
        if ($id) {
            $restaurant = Restaurant::find($id);
            if(!$restaurant){
                return response()->json(['error' => 'Restaurant not found'], 404);
            }
            return response()->json([$restaurant], 200);
        }

        // If Searching by name
        $searchName = $request->input('name');

        // If Searching by address
        $searchAddressId = $request->input('address_id');
        $searchRange = $request->input('range', 10); // default value 10km radius

        $searchAddress = $searchAddressId ? Address::find($searchAddressId) : null;

        $restaurants = Restaurant::where(function ($query) use ($searchName, $searchAddress, $searchRange) {
            if ($searchName) {
                $query->where('name', 'like', '%' . $searchName . '%');
            }


            if ($searchAddress) {
                $lat1 = $searchAddress['latitude'];
                $lon1 = $searchAddress['longitude'];

                $query->where(function ($q) use ($lat1, $lon1, $searchRange) {
                    $q->whereHas('address', function ($q) use ($lat1, $lon1, $searchRange) {
                        $q->select('addresses.*')
                            ->selectRaw("6371 * acos(sin(radians(latitude)) * sin(radians($lat1)) + cos(radians(latitude)) * cos(radians($lat1)) * cos(radians(longitude) - radians($lon1))) AS distance")
                            ->having('distance', '<=', $searchRange);
                    });
                });

            }
        })->get();

        return response()->json(['restaurants' => $restaurants], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'description' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|string|email|unique:restaurants',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
            'address.latitude' => 'required|numeric',
            'address.longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create the address
        $address = new Address();
        $address->street = $request->input('address.street');
        $address->city = $request->input('address.city');
        $address->country = $request->input('address.country');
        $address->latitude = $request->input('address.latitude');
        $address->longitude = $request->input('address.longitude');
        $address->save();

        // Create the restaurant
        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->description = $request->description;
        $restaurant->phone = $request->phone;
        $restaurant->email = $request->email;
        $restaurant->address_id = $address->id;
        $restaurant->save();

        return response()->json(['message' => 'Restaurant created successfully'], 201);
    }
}
