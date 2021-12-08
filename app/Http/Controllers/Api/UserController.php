<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        if(count($users) > 0)
        {
            return response([
                'message' => 'Retreive All User Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $users = User::find($id);
        
        if(!is_null($users))
        {
            return response([
                'message' => 'Retreive User Success',
                'data' => $users
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 404);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required|max:60',
            'email'=> 'required|email:rfc,dns|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required',
            'is_admin' => 'required|numeric',
            'alamat' => 'required',
            'no_telp'=> 'required|numeric|unique:users|digits_between:10,13',
            'tanggal_lahir' => 'required|date|before: -18 years'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $storeData['password'] = bcrypt($request->password);
        $user = User::create($storeData);
        return response([
            'message' => 'Add User Success',
            'data' => $user
        ], 200);
    }

    public function destroy($id)
    {
        $users = User::find($id);
        
        if(is_null($users))
        {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        if($users->delete())
        {
            return response([
                'message' => 'Delete User Success',
                'data' => $users
            ], 200);
        }
        
        return response([
            'message' => 'Delete User Failed',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if(is_null($user))
        {
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'nama' => 'required|max:60',
            'email'=> ['required', 'email:rfc,dns', Rule::unique('users')->ignore($user)],
            'username'=> ['required', Rule::unique('users')->ignore($user)],
            'alamat' => 'required',
            'no_telp' => ['required', 'numeric', 'digits_between:10,13', Rule::unique('users')->ignore($user)],
            'tanggal_lahir' => 'required|date|before: -18 years'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        $user->nama = $updateData['nama'];
        $user->email = $updateData['email'];
        $user->username = $updateData['username'];
        $user->alamat = $updateData['alamat'];
        $user->no_telp = $updateData['no_telp'];
        $user->tanggal_lahir = $updateData['tanggal_lahir'];
        
        if($user->save())
        {
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }
        
        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
        
    }
}
