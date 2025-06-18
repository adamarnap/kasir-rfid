<?php

namespace App\Http\Services\Master;

use App\Models\Categories;
use Illuminate\Support\Facades\Storage;

class CategoriesService
{

    /* Get All Data Categories */
    public function getDataAllCategories(){
        return Categories::all();
    }

}