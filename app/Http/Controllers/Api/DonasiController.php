<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Donasi;

class DonasiController extends Controller
{
    public function index()
    {
        $donasi = Donasi::all();

        if(count($donasi) > 0)
        {
            return response([
                'message' => 'Seluruh Data Donasi Berhasil Diambil',
                'data' => $donasi
            ], 200);
        }

        return response([
            'message' => 'Data Donasi Kosong',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $donasi = Donasi::find($id);
        
        if(!is_null($donasi))
        {
            return response([
                'message' => 'Donasi Ditemukan',
                'data' => $donasi
            ], 200);
        }

        return response([
            'message' => 'Donasi Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function showByUser($id)
    {
        $donasi = Donasi::where('id_user', $id)->get();
        
        if(count($donasi) > 0)
        {
            return response([
                'message' => 'Donasi Ditemukan',
                'data' => $donasi
            ], 200);
        }

        return response([
            'message' => 'Donasi Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'judul'=> 'required|unique:donasi',
            'penulis' => 'required',
            'jumlah_halaman' => 'required|numeric',
            'tahun_terbit' => 'required|numeric',
            'id_user'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $donasi = Donasi::create($storeData);
        return response([
            'message' => 'Add Donasi Success',
            'data' => $donasi
        ], 200);
    }

    public function destroy($id)
    {
        $donasi = Donasi::find($id);
        
        if(is_null($donasi))
        {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }

        if($donasi->delete())
        {
            return response([
                'message' => 'Delete Donasi Success',
                'data' => $donasi
            ], 200);
        }
        
        return response([
            'message' => 'Delete Donasi Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $donasi = Donasi::find($id);
        
        if(is_null($donasi))
        {
            return response([
                'message' => 'Donasi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'judul'=> ['required', Rule::unique('donasi')->ignore($donasi)],
            'penulis' => 'required',
            'jumlah_halaman' => 'required|numeric',
            'tahun_terbit' => 'required|numeric',
            'id_user'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $donasi->judul = $updateData['judul'];
        $donasi->penulis = $updateData['penulis'];
        $donasi->jumlah_halaman = $updateData['jumlah_halaman'];
        $donasi->tahun_terbit = $updateData['tahun_terbit'];
        $donasi->id_user = $updateData['id_user'];
        
        if($donasi->save())
        {
            return response([
                'message' => 'Update Donasi Success',
                'data' => $donasi
            ], 200);
        }
        
        return response([
            'message' => 'Update Donasi Failed',
            'data' => null
        ], 400);
        
    }
}
