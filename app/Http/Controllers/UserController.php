<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_outlet' => 'required|string|max:20',
            'nama' => 'required|string|max:255',

            // unique:Users digunakan untuk cek apakah username unik dan belum digunakan apa belum
            'username' => 'required|string|max:50|unique:Users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors(),
            ]);
        }

        $user = new User();
        $user->id_outlet = $request->id_outlet;
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->role = 'admin';
        $user->password = Hash::make($request->password);
        $user->save();

        $data = User::where('username', '=', $request->username)->first();

        return response()->json([
            'success' => 'true',
            'message' => 'Registrasi User Berhasil',
            'data' => $data,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau Password Invalid',
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Generasi Token Gagal'
            ]);
        }

        $data = [
            'token' => $token,
            'user' => JWTAuth::user(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Login User Berhasil',
            'data' => $data,
        ]);
    }

    public function loginCheck(){
        try {
            if(!$user = JWTAuth::parseToken()->authenticate()){
                return $this->response->errorResponse('Invalid token!');
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
            return response()->json([
                'success' => false,
                'message' => 'Token Expired.',
            ]);
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
            return response()->json([
                'success' => false,
                'message' => 'Token invalid.',
            ]);
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e){
            return response()->json([
                'success' => false,
                'message' => 'Authorization token not found.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Authentication success',
            'data' => $user
        ]);

    }

    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                'success' => true,
                'message' => 'You are logged out.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logged out failed.',
            ]);
        }
    }
    
    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = User::count();
        $data['user'] = User::with('outlet')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data["user"] = User::where('id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    
}
