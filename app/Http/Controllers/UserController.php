<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_outlet' => 'required|numeric|max:20',
            'nama' => 'required|string|max:255',

            // unique:Users digunakan untuk cek apakah username unik dan belum digunakan apa belum

            'username' => 'required|string|max:50|unique:Users',
            'password' => 'required|string|min:6',
            'role' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors(),
            ]);
        }

        $user = new User();
        $user->id_outlet = $request->id_outlet;
        $user->nama     = $request->nama;
        $user->username = $request->username;
        $user->role     = $request->role;
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
                    'message' => 'Invalid Username or Password'
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Generate Token Failed'
            ]);
        }

        $data = [
            'token' => $token,
            'user' => JWTAuth::user()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Authentication Success',
            'data' => $data
        ]);
    }

    public function loginCheck()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->response->errorResponse('Invalid Token!');
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Expired!'
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token Invalid!'
            ]);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization Token Not Found!'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Authentication Success',
            'data' => $user
        ]);
    }

    public function logout(Request $request)
    {
        if (JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                'success' => true,
                'message' => 'You Are Logged Out!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Logged Out'
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_outlet' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
        }

        $user = User::where('id', $id)->first();
        $user->id_outlet = $request->id_outlet;
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->role = $request->role;

        if ($request->password != NULL) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Data User Berhasil Disunting!'
        ]);
    }

    public function delete($id)
    {
        $delete = User::where('id', $id)->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data User Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data User Gagal Dihapus'
            ]);
        }
    }

    public function getAll()
    {
        $data["count"] = User::count();
        $data["users"] = User::with('outlet')->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data["users"] = User::where('id', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}