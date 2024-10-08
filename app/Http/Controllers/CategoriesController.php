<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function addCategory(Request $request){
        $data = $request->validate([
            'name' => 'required|string',

        ],
        [
            'name.required' => 'Ime kategorije je obavezno',
        ]);

        $category = new Categories($data);
        $category->user_id = Auth::id();
        $category->save();
        return response()->json(['message' => 'Kategorija uspješno dodana'], 200);
    }
    public function getCategories(){
        $categories = Categories::with('user')->get();
        // Vrati sve kategorije kao JSON odgovor
        return response()->json($categories, 200);

    }
}
