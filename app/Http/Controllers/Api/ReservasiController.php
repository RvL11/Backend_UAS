<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi;
use Carbon\Carbon;

class ReservasiController extends Controller
{
    public function index()
    {
        $reservasi = Reservasi::all();

        if(count($reservasi) > 0)
        {
            return response([
                'message' => 'Seluruh Data Reservasi Berhasil Diambil',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Data Reservasi Kosong',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $reservasi = Reservasi::find($id);
        
        if(!is_null($reservasi))
        {
            return response([
                'message' => 'Reservasi Ditemukan',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Reservasi Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function showByUser($id)
    {
        $reservasi = Reservasi::where('id_user', $id)->get();
        
        if(count($reservasi) > 0)
        {
            return response([
                'message' => 'Reservasi Ditemukan',
                'data' => $reservasi
            ], 200);
        }

        return response([
            'message' => 'Reservasi Tidak Ditemukan',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'tanggal_reservasi'=> 'required|date|after:tomorrow',
            'waktu_reservasi' => 'required',
            'jumlah_orang' => 'required|numeric',
            'id_user',
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $reservasi = Reservasi::create($storeData);
        return response([
            'message' => 'Add Reservasi Success',
            'data' => $reservasi
        ], 200);
    }

    public function destroy($id)
    {
        $reservasi = Reservasi::find($id);
        
        if(is_null($reservasi))
        {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ], 404);
        }

        if($reservasi->delete())
        {
            return response([
                'message' => 'Delete Reservasi Success',
                'data' => $reservasi
            ], 200);
        }
        
        return response([
            'message' => 'Delete Reservasi Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $reservasi = Reservasi::find($id);
        
        if(is_null($reservasi))
        {
            return response([
                'message' => 'Reservasi Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'tanggal_reservasi'=> 'required|date|after:tomorrow',
            'waktu_reservasi' => 'required',
            'jumlah_orang' => 'required|numeric',
            'id_user',
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $reservasi->tanggal_reservasi = $updateData['tanggal_reservasi'];
        $reservasi->waktu_reservasi = $updateData['waktu_reservasi'];
        $reservasi->jumlah_orang = $updateData['jumlah_orang'];
        $reservasi->id_user = $updateData['id_user'];
        
        if($reservasi->save())
        {
            return response([
                'message' => 'Update Reservasi Success',
                'data' => $reservasi
            ], 200);
        }
        
        return response([
            'message' => 'Update Reservasi Failed',
            'data' => null
        ], 400);
        
    }
}
