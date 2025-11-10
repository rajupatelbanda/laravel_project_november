<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'data' => $categories

        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Category name is required',
            'description.required' => 'Category Description is required',
            'status.required' => 'Status is   required',
            'status.boolean' => 'Status must be true or false'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validator->errors()
            ], 403);
        }

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status;

        $category->save();

        return response()->json([
            'status' => 'success',
            'message' => "Category Addded Successfully",
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'status' => 'failed',
                'message' => "Catregory not found"
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'failed',
                'message' => "Catregory not found"
            ], 404);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ], [
            'name.required' => 'Category name is required',
            'description.required' => 'Category Description is required',
            'status.required' => 'Status is   required',
            'status.boolean' => 'Status must be true or false'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validator->errors()
            ], 403);
        }
        $category->name = $request->name;
        $category->description = $request->description;
        $category->status = $request->status;

        $category->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'status' => 'failed',
                'message' => "Category Can't Find"
            ], 404);
        }

        $category->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Category Deleted Successfully"
        ], 200);
    }
}
