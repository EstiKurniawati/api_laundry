<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Paket;

class PaketController extends Controller
{
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors(),
            ]);
        }

        $paket = new Paket();

        $paket->jenis = $request->jenis;
        $paket->harga = $request->harga;
        $paket->save();

        $data = Paket::where('id_paket', '=', $paket->id_paket)->first();

        return response()->json([
            'success' => 'true',
            'message' => 'Data Paket Berhasil Ditambahkan',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 'false',
                'message' => $validator->errors(),
            ]);
        }

        $data = Paket::where('id_paket', $id)->first();
        $data->jenis = $request->jenis;
        $data->harga = $request->harga;
        $data->save();

        return response()->json([
            'success' => 'true',
            'message' => 'Data Paket Berhasil Disunting',
        ]);
    }

    public function delete($id)
    {
        $delete = Paket::where('id_paket', $id)->delete();

        if ($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data Paket Berhasil Dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Paket Gagal Dihapus'
            ]);
        }
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        $data["count"] = Paket::count();

        if ($limit == NULL && $offset == NULL) {
            $data["paket"] = Paket::get();
        } else {
            $data["paket"] = Paket::take($limit)->skip($offset)->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getById($id)
    {
        $data["paket"] = Paket::where('id_paket', $id)->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
