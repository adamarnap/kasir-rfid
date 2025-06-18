<?php

namespace App\Http\Services\Master;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class CategoriesService
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description'
    ];

    /* Get All Data Categories */
    public function getDataAllCategories(){
        return Categories::all();
    }

    /* Process Store Category */
    public function store($request)
    {
        try{
            Categories::create([
                'name' => $request->name,
                'description' => $request->description
            ]);
                return redirect()->back()->with('success', 'Sukses melakukan penambahan data.');
        }catch(\Exception $e){
                return redirect()->back()->with('error', 'Gagal melakukan penambahan data ' . $e->getMessage());
        }

    }
}