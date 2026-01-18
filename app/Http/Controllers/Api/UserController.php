<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function register(RegisterRequest $request)
    {
        $userData = $request->validated();
        $userData['password'] = Hash::make($userData['password']);
        $pattern = "/admin\.lapor\.ac\.id$/";

        if (preg_match($pattern, $userData['email'])) {
            $userData['role'] = User::ROLE_ADMIN;
        }

        User::create($userData);

        return response()->json([
            'success' => true,
            'message' => "Data Berhasil Disimpan",
        ], 200);
    }


    public function login(LoginRequest $request){
        $userData=$request->validated();
        $user=User::where('email',$userData['email'])->first();

        // Cek apakah user ada dan password benar
         if($user&&(Hash::check($userData['password'],$user->password))){
            $token = $user->createToken('auth_token')->plainTextToken;       
         return response()->json([
                    'success' => true,
                    'message' => "Berhasil Login",
                    'access_token'=>$token,
                    'token_type'=>'Bearer',
                    'user'=>$user
        ], 200);

        }else{
            return response()->json([
                'success' => false,
                'message' => "Email atau Password anda salah",            
                ], 401);
        }



    }


    // public function login($email, $password)
    // { {
    //         // Hanya untuk testing
    //         $user = User::where('email', $email)->first();

    //         if ($user && $user->password) {
    //             return response()->json(['message' => 'Login berhasil', 'user' => $user]);
    //         }
    //         $token = $user->createToken('auth_token')->plainTextToken;
    //              return response()->json([
    //                         'success' => true,
    //                         'message' => "Berhasil Login",
    //                         'access_token'=>$token,
    //                         'token_type'=>'Bearer',
    //                         'user'=>$user
    //             ], 200);
    //         // return response()->json(['message' => 'Email / password salah'], 401);
    //     }


    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
    public function logout(Request $request)
    {
        
        if(!$request->user()){
            return response()->json([
                'success'=>'false',
                'message'=>'Anda belum login',
            ],401);
        }    
    
    $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout semua perangkat berhasil'
        ]);
    }


}
