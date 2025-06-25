<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Services\Master\CategoriesService;
use App\Http\Services\Master\ProductsService;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function __construct(protected ProductsService $productsService, protected CategoriesService $categoriesService)
    {
        // You can set any middleware or services here if needed
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('products.read');
        //
        $products = $this->productsService->getDataAllProducts();
        $categories = $this->categoriesService->getDataAllCategories();
        return view('master.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->setRule('products.create');
        // Validation
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        // Process the store logic here, e.g., using a service class
        return $this->productsService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->setRule('products.update');
        //
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        // Process update logic here, e.g., using a service class
        return $this->productsService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->setRule('products.delete');
        return $this->productsService->destroy($id);
            
    }
}
