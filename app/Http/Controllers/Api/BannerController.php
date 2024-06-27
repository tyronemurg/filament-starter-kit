<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Banner::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'banner_category_id' => 'required|exists:banner_categories,id', // Assuming you have a `banner_categories` table
            'sort' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'click_url' => 'nullable|url',
            'click_url_target' => 'nullable|string|in:_self,_blank', // Assuming these are the possible values
            'is_visible' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $banner = Banner::create($validatedData);

        return response()->json($banner, 201);
    }

    public function show(Banner $banner)
    {
        return $banner;
    }

    public function update(Request $request, Banner $banner)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'stock' => 'sometimes|integer',
        ]);

        $banner->update($validatedData);

        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json(null, 204);
    }
}
