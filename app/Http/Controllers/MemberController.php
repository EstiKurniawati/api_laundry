<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;

class MemberController extends Controller
{
  public function insert(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'nama' => 'required|string',
          'alamat' => 'required|string',
          'jenis_kelamin' => 'required|string',
          'telp' => 'required|numeric',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => 'false',
              'message' => $validator->errors(),
          ]);
      }

      $member = new Member();
      $member->nama = $request->nama;
      $member->alamat = $request->alamat;
      $member->jenis_kelamin = $request->jenis_kelamin;
      $member->telp = $request->telp;
      $member->save();

      $data = Member::where('id_member', '=', $member->id_member)->first();

      return response()->json([
          'success' => 'true',
          'message' => 'Data member Berhasil Ditambahkan',
          'data' => $data,
      ]);
  }

  public function update(Request $request, $id_member)
  {
      $validator = Validator::make($request->all(), [
//          'id_member' => 'required|string',
          'nama' => 'required|string',
          'alamat' => 'required|string',
          'jenis_kelamin' => 'required|string',
          'telp' => 'required|numeric',
      ]);

      if ($validator->fails()) {
          return response()->json([
              'success' => 'false',
              'message' => $validator->errors(),
          ]);
      }

      $member = Member::where('id_member', $id_member)->first();
      $member->nama = $request->nama;
      $member->alamat = $request->alamat;
      $member->jenis_kelamin = $request->jenis_kelamin;
      $member->telp = $request->telp;
      $member->save();

      return response()->json([
          'success' => 'true',
          'message' => 'Data member Berhasil Disunting',
        ]);
  }

  public function delete($id)
  {
      $delete = Member::where('id_member', $id)->delete();

      if ($delete) {
          return response()->json([
              'success' => true,
              'message' => 'Data member Berhasil Dihapus'
          ]);
      } else {
          return response()->json([
              'success' => false,
              'message' => 'Data member Gagal Dihapus'
          ]);
      }
  }

  public function getAll($limit = NULL, $offset = NULL)
  {
      $data["count"] = Member::count();
          $data["member"] = Member::get();

      return response()->json([
          'success' => true,
          'data' => $data
      ]);
  }

  public function getById($id_member)
  {
      $data["member"] = Member::where('id_member', $id_member)->get();

      return response()->json([
          'success' => true,
          'data' => $data
      ]);
  }
}
