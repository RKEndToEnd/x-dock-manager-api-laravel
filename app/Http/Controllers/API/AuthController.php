<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Wpisz imię.',
            'name.max' => 'Imię nie może być dłuższe niż 255 znaków.',
            'email.required' => 'Wpisz adres email.',
            'email.email' => 'Niewłaściwy format adresu email.',
            'email.max' => 'Adres email nie może być dłuiższy niż 255 znaków.',
            'email.unique' => 'Adres email istnieje w systemie. Skorzystaj z innego adresu email',
            'password.required' => 'Wpisz hasło. Hasło musi zawierać min. 8 znaków.',
            'password.min' => 'Hasło musi zawierać min. 8 znaków.'
        ]);
        if ($validator->fails()) {
            return response()->json(['val_errors' => $validator->messages()
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request['password'],
                )
            ]);
            $token = $user->createToken($user->email . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Użytkownk zarejestrowany.',
            ]);
        }
    }
}
