<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function uploadProfileImage(Request $request)
{


    $user = Auth::user();
    if (!$user instanceof \App\Models\User) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    $imageName = time().'.'.$request->image->extension();


    $request->image->move(public_path('images'), $imageName);


    $user->image = $imageName;
    $user->save();

    return response()->json(['message' => 'Profile image uploaded successfully.']);
}
}
