<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Outlet;

class OutletController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors()
            ]);
        }

        $outlet = new Outlet();

        $outlet->nama_outlet = $request->nama_outlet;
        $outlet->save();

        $data = Outlet::where('id_outlet', '=', $outlet->id_outlet)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data Outlet Berhasil Ditambahkan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors(),
            ]);
        }

        $data = Outlet::where('id_outlet', $id)->first();
        $data->nama_outlet = $request->nama_outlet;
        $data->save();

        return response()->json([
            'success' => 'true',
            'message' => 'Data Outlet Berhasil Disunting'
        ]);
    }

    public function delete($id)
    {
        $delete = Outlet::where('id_outlet', $id)->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data Outlet Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Data Outlet Gagal Dihapus'
            ]);
        }
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        $data['count'] = Outlet::count();

        if ($limit == NULL && $offset == NULL) {
            $data['outlet'] = Outlet::get();
        } else {
            $data['outlet'] = Outlet::take($limit)->skip($offset)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data['outlet'] = Outlet::where('id_outlet', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}