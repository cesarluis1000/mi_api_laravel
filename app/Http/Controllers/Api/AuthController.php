<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    //Inicio de Sesion
    public function login(Request $request){
        
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = request(['email','password']);

        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Correo o contraseña invalido'
            ], 401);
        }

        $user = $request->user();
        $token = $user->createToken('Access Token');
        $user->access_token = $token->accessToken;

        return response()->json([
            'user' => $user
        ], 200);
    }
   
    public function signup(Request $request){

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            'message' => 'Usuario registrado correctamente'
        ],201);
        
    }
    //Terminar Sesion
    public function logout(Request $request){
        
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Sesión cerrada'
        ], 200);

    }

    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user
        ], 200);
    }

    public function index(){
        echo "Hola Mundo";
    }
}
