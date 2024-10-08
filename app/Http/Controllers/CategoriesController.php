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

    public function deleteCategory($id){
        $category = Categories::find($id);
        $category->delete();
        return response()->json(['message' => 'Kategorija uspješno obrisana'], 200);
    }

    public function updateCategory(Request $request, $id){
        $data = $request->validate([
            'name' => 'required|string',
        ],
        [
            'name.required' => 'Ime kategorije je obavezno',
        ]);


        $data['updated_at'] = now();

        $category = Categories::find($id);
        $category->update($data);
        return response()->json(['message' => 'Kategorija uspješno ažurirana'], 200);
    }
}
