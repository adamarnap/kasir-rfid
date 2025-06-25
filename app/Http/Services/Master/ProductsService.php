<?php

namespace App\Http\Services\Master;

use App\Models\Products;
use Illuminate\Support\Facades\Storage;

class ProductsService
{

    /**
     * Get all products with their categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDataAllProducts()
    {
        $products = Products::with('category')
            ->orderBy('created_at', 'desc')
            ->get();
        return $products;
    }

    /* Process Store the data Products */
    public function store($request)
    {
        try {
            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            Products::create($data);
            return redirect()->back()->with('success', 'Sukses melakukan penambahan data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan penambahan data: ' . $e->getMessage());
        }
    }

    /* Process Update the data product */
    public function update($request, $id)
    {
        try {
            $product = Products::findOrFail($id);
            $data = [
                'category_id' => $request->category_id,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            $product->update($data);
            return redirect()->back()->with('success', 'Sukses melakukan perubahan data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan perubahan data: ' . $e->getMessage());
        }
    }

    /* Process Delete the data product */
    public function destroy($id)
    {
        try {
            $product = Products::findOrFail($id);
            $product->delete();
            return redirect()->back()->with('success', 'Sukses menghapus data.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        } 
    }
}