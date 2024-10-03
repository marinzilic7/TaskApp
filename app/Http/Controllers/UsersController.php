<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'image' => 'nullable',
        ],
        [
            'firstName.required' => 'Ime je obavezno',
            'lastName.required' => 'Prezime je obavezno',
            'email.required' => 'Email adresa je obavezna',
            'email.email' => 'Email adresa nije validna',
            'email.unique' => 'Email adresa je zauzeta',
            'password.required' => 'Lozinka je obavezna',
            'password.min' => 'Lozinka mora imati najmanje 5 znakova',
        ]);

        $data['password'] = bcrypt($data['password']);
        $user = new ModelsUser($data);
        $user->create($data);
        return response()->json(['message' => 'Uspješna registracija'], 200);

    }

    public function loginUser(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email adresa je obavezna',
            'email.email' => 'Email adresa nije validna',
            'password.required' => 'Lozinka je obavezna',
        ]);

        if (Auth::attempt($data)) {
            $user = Auth::user();
            return response()->json(['message' => 'Uspješna prijava', 'user' => $user]);
        }else{
            return response()->json(['message' => 'Pogrešan email ili lozinka'], 401);
        }
    }

    public function isLogged()
    {

        $user = Auth::user();
        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(null);
        }
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Uspješna odjava']);
    }
}
