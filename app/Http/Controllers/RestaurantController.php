<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{

    public function listRestaurants(){
        $restaurants = Restaurant::all();

        return response()->json([
            "status" => 200,
            "message" => "successfully retrieved",
            "data" => $restaurants
        ]);
    }

    public function createRestaurant(Request $request){
        $request->validate([
            "icon" => "required|mime:png,jpg"
        ]);

        Restaurant::create([
            "owner_id" => $request->user()->id,
            "name" => $request->name,
            "address" => $request->address,
            "icon" => $request->file('icon')->store('public/images')
        ]);

        return response()->json([
            "status" => 201,
            "message" => "successfully created",
            "data" => [
                "owner" => $request->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "icon" => $request->icon
            ]
        ]);
    }

    public function restaurantDetail($id){
        $restaurant = Restaurant::find($id);

        if ($restaurant){
            return response()->json([
                "status" => 200,
                "message" => "successfully retreived",
                "data" => $restaurant
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "retrieved unsuccessfully",
            "data" => []
        ]);
    }

    public function updateRestaurant(Request $request, $id){
        $restaurant = Restaurant::find($id);

        $request->validate([
            "icon" => "mime:png,jpg"
        ]);

        if ($restaurant){
            $restaurant->update([
                "owner_id" => $request->user()->id,
                "name" => $request->name,
                "address" => $request->address,
                "icon" => $request->file('icon')->store('public/images')
            ]);

            return response()->json([
                "status" => 200,
                "message" => "successfully updated",
                "data" => $restaurant
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "could not be updated",
            "data" => []
        ]);

    }

    public function deleteRestaurant($id){
        $restaurant = Restaurant::find($id);

        if($restaurant){
            $restaurant->delete();

            return response()->json([
                "status" => 200,
                "message" => "successfully deleted",
                "data" => []
            ]);
        }

        return response()->json([
            "status" => 404,
            "message" => "could not be deleted",
            "data" => []
        ]);
    }
}