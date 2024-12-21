<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products', function (Request $request) {
    $request->validate([
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'radius' => 'nullable|numeric|min:1',
    ]);

    $latitude = $request->input('latitude');
    $longitude = $request->input('longitude');
    $radius = $request->input('radius', 10); // Default radius 10 km

    $products = Product::select('*')
        ->selectRaw(
            "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$latitude, $longitude, $latitude]
        )
        ->having('distance', '<=', $radius)
        ->orderBy('distance', 'asc')
        ->get();

    return response()->json($products);
});
