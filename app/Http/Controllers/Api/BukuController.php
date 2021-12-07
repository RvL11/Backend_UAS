<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::all();

        if(count($buku) > 0)
        {
            return response([
                'message' => 'Seluruh Data Buku Berhasil Diambil',
                'data' => $buku
            ], 200);
        }

        return response([
            'message' => 'Data Buku Kosong',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $buku = Buku::find($id);
        
        if(!is_null($buku))
        {
            return response([
                'message' => 'Buku Ditemukan',
                'data' => $buku
            ], 200);
        }

        return response([
            'message' => 'Buku Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'judul'=> 'required|unique:buku',
            'penulis' => 'required',
            'jumlah_halaman' => 'required|numeric',
            'tahun_terbit' => 'required|numeric'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $buku = Buku::create($storeData);
        return response([
            'message' => 'Add Buku Success',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);
        
        if(is_null($buku))
        {
            return response([
                'message' => 'Buku Not Found',
                'data' => null
            ], 404);
        }

        if($buku->delete())
        {
            return response([
                'message' => 'Delete Buku Success',
                'data' => $buku
            ], 200);
        }
        
        return response([
            'message' => 'Delete Buku Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);
        
        if(is_null($buku))
        {
            return response([
                'message' => 'Buku Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'judul'=> ['required', Rule::unique('buku')->ignore($buku)],
            'penulis' => 'required',
            'jumlah_halaman' => 'required|numeric',
            'tahun_terbit' => 'required|numeric'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $buku->judul = $updateData['judul'];
        $buku->penulis = $updateData['penulis'];
        $buku->jumlah_halaman = $updateData['jumlah_halaman'];
        $buku->tahun_terbit = $updateData['tahun_terbit'];
        
        if($buku->save())
        {
            return response([
                'message' => 'Update Buku Success',
                'data' => $buku
            ], 200);
        }
        
        return response([
            'message' => 'Update Buku Failed',
            'data' => null
        ], 400);
        
    }
}
