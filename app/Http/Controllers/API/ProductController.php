<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SaveProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ResponseFormatter;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ResponseFormatter;

    public function index(Request $request)
    {
        //
    }

    public function store(SaveProductRequest $request)
    {
        $product = Product::create($request->validated());

        return $this->formatResponse(code: 201, message: 'Created', data: ProductResource::make($product));
    }

    public function show(Product $product)
    {
        return $this->formatResponse(data: ProductResource::make($product));
    }

    public function update(SaveProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return $this->formatResponse(message: 'Updated', data: ProductResource::make($product));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return $this->formatResponse(code: 204, message: 'Deleted');
    }
}
