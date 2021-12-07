<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Peminjaman;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::all();

        if(count($peminjaman) > 0)
        {
            return response([
                'message' => 'Seluruh Data Peminjaman Berhasil Diambil',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Data Peminjaman Kosong',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::find($id);
        
        if(!is_null($peminjaman))
        {
            return response([
                'message' => 'Peminjaman Ditemukan',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Peminjaman Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function showByUser($id)
    {
        $peminjaman = Peminjaman::where("id_user", $id)->get();
        if(count($peminjaman) > 0)
        {
            return response([
                'message' => 'Peminjaman Ditemukan',
                'data' => $peminjaman
            ], 200);
        }

        return response([
            'message' => 'Peminjaman Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'tanggal_pinjam'=> 'required|date|after_or_equal:today',
            'tanggal_kembali'=> 'required|date|after:tanggal_pinjam',
            'id_user',
            'id_buku'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $peminjaman = Peminjaman::create($storeData);
        return response([
            'message' => 'Tambah Peminjaman Sukses',
            'data' => $peminjaman
        ], 200);
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);
        
        if(is_null($peminjaman))
        {
            return response([
                'message' => 'Peminjaman Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        if($peminjaman->delete())
        {
            return response([
                'message' => 'Hapus Peminjaman Sukses',
                'data' => $peminjaman
            ], 200);
        }
        
        return response([
            'message' => 'Hapus Peminjaman Gagal',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::find($id);
        
        if(is_null($peminjaman))
        {
            return response([
                'message' => 'Peminjaman Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'tanggal_pinjam'=> 'required|date|after_or_equal:today',
            'tanggal_kembali'=> 'required|date|after:tanggal_pinjam',
            'id_user',
            'id_buku'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $peminjaman->tanggal_pinjam = $updateData['tanggal_pinjam'];
        $peminjaman->tanggal_kembali = $updateData['tanggal_kembali'];
        $peminjaman->id_user = $updateData['id_user'];
        $peminjaman->id_buku = $updateData['id_buku'];
        
        if($peminjaman->save())
        {
            return response([
                'message' => 'Update Peminjaman Sukses',
                'data' => $peminjaman
            ], 200);
        }
        
        return response([
            'message' => 'Update Peminjaman Gagal',
            'data' => null
        ], 400);
        
    }
}
