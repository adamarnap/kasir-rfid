<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Master\CategoriesService;

class CategoriesController extends Controller
{

    public function __construct(protected CategoriesService $categoriesService)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('categories.read');
        // Get data categories
        $categories = $this->categoriesService->getDataAllCategories();
        // Load View
        return view('master.categories.index', compact('categories'));
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
        $this->setRule('categories.create');
        // Validation
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);
        return $this->categoriesService->store($request);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
