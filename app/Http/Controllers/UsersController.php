<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;


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
        $user = new User($data);
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
            $user->last_online = Carbon::now('Europe/Zagreb');
            $user->is_online = true;
            $user->save();
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
        $user = Auth::user();
        $user->last_online = Carbon::now('Europe/Zagreb');
        $user->is_online = false; // Postavljanje statusa neaktivan
        $user->save();
        Auth::logout();

        return response()->json(['message' => 'Uspješna odjava']);
    }

    public function uploadProfileImage(Request $request)
{
    $data = $request->validate([
        'image' => 'mimes:jpeg,jpg,png|max:2048', // Dodano ograničenje veličine slike (npr. 2 MB)
    ]);

    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageSize = getimagesize($image); // Dobivanje dimenzija slike

        // Provjera dimenzija (npr. profilne slike trebaju biti kvadratne ili unutar određenog omjera)
        $maxWidth = 1200; // Maksimalna dozvoljena širina slike
        $maxHeight = 1200; // Maksimalna dozvoljena visina slike

        if ($imageSize[0] > $maxWidth || $imageSize[1] > $maxHeight) {
            return response()->json(['error' => 'Dimenzije slike nisu dozvoljene. Maksimalne dimenzije su 1200x1200px.'], 400);
        }

        $extension = $image->getClientOriginalExtension(); // Dobivanje originalne ekstenzije slike
        $image_name = time() . '1.' . $extension; // Generiranje imena slike s ispravnom ekstenzijom

        // Pohranjivanje slike
        $image->move(public_path('profile_images'), $image_name);

        // Ažuriranje korisnikovog profila sa slikom
        $user->image = $image_name;
        $user->save();

        return response()->json(['message' => 'Profilna slika uspješno dodana'], 201);
    }

    return response()->json(['error' => 'Slika nije uploadana'], 400);
}

public function getUsers(){
    $users = User::all();
    return response()->json($users);
}

public function deleteUser($id){
    $user = User::find($id);
    $user->delete();
    return response()->json(['message' => 'Korisnik uspješno obrisan']);
}

public function promoteUser($id){
    $user = User::find($id);
    $user->role = 'admin';
    $user->save();
    return response()->json(['message' => 'Korisnik uspješno promoviran']);
}

public function changePassword(Request $request)
{
    $data = $request->validate([
        'currentPassword' => 'required|min:5',  // Validacija za staru lozinku
        'newPassword' => 'required|min:5',      // Validacija za novu lozinku
    ]);

    $user = Auth::user();

    // Provjeri je li stara lozinka točna
    if (!password_verify($data['currentPassword'], $user->password)) {
        return response()->json(['error' => 'Pogrešna stara lozinka'], 400);
    }

    // Ažuriraj lozinku
    $user->password = bcrypt($data['newPassword']); // Sva lozinka se mijenja s novom
    $user->save();

    return response()->json(['message' => 'Lozinka je uspješno promijenjena']);
}


}
