<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'data' => $products
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
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|boolean',

            'quantity' => 'required|integer',
            'sku' => 'required|string|unique:products,sku'
        ], [
            'name.required' => 'Product name is required',
            'price.required' => 'Product price is required',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'status.required' => 'Status is required',
            'status.boolean' => 'Status must be true or false',
            'quantity.required' => 'Quantity is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'SKU must be unique'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->quantity = $request->quantity;
        $product->sku = $request->sku;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/products/', $filename);
            $product->image = $filename;
        }

        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|boolean',

            'quantity' => 'required|integer',
            'sku' => 'required|string|unique:products,sku'
        ], [
            'name.required' => 'Product name is required',
            'price.required' => 'Product price is required',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'status.required' => 'Status is required',
            'status.boolean' => 'Status must be true or false',
            'quantity.required' => 'Quantity is required',
            'sku.required' => 'SKU is required',
            'sku.unique' => 'SKU must be unique'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->quantity = $request->quantity;
        $product->sku = $request->sku;

        if ($request->hasFile('image')) {
            $destiantion = 'uploads/products/' . $product->image;
            if (File::exists($destiantion)) {
                File::delete($destiantion);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('uploads/products/', $filename);
            $product->image = $filename;
        }

        $product->save();

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product not found'
            ], 404);
        }

        $destiantion = 'uploads/products/' . $product->image;
        if (File::exists($destiantion)) {
            File::delete($destiantion);
        }
        $product->delete();
        return response()->json([
            'message' => 'Product Deleted successfully',
            'data' => $product
        ], 201);
    }
}
