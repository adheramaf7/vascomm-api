<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\GetProductRequest;
use App\Http\Requests\API\SaveProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ResponseFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use ResponseFormatter;

    public function index(GetProductRequest $request)
    {
        $data = Product::query()
            ->when($request->query('search'), fn (Builder $query) => $query->where(DB::raw('LOWER(name)'), 'LIKE', '%' . strtolower($request->query('search')) . '%'))
            ->when(!is_null($request->query('skip')) && !is_null($request->query('take')), fn (Builder $query) => $query->skip($request->query('skip'))->take($request->query('take')))
            ->get();

        return $this->formatResponse(data: ProductResource::collection($data));
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
