<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use Validator;
use Carbon\Carbon;

class AuthController extends Controller
{
    use VerifiesEmails;

    public function register(Request $request)
    {
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
           'nama' => 'required|max:60',
           'email'=> 'required|email:rfc,dns|unique:users',
           'no_telp'=> 'required|numeric|unique:users|digits_between:10,13',
           'alamat' => 'required',
           'tanggal_lahir' => 'required|date|before: -18 years',
           'username' => 'required|unique:users',
           'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $registrationData['password'] = bcrypt($request->password);
        $user = User:: create($registrationData);
        $user->sendApiEmailVerificationNotification();

        return response([
            'message' => 'Register Berhasil! Harap segera aktivasi akun melalui email yang telah dikirim.',
            'user' => $user
        ], 200);
    } 

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'username'=> 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData)) return response(['message' => 'Username / Password Salah'], 401);
        
        $user = Auth::user();

        if($user->email_verified_at !== NULL)
        {
            $token = $user->createToken('Authentication Token')->accessToken;
            
            return response([
                'message' => 'Login Berhasil',
                'user' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ]);
        }
        else
        {
            return response(['message' => 'Silahkan Verifikasi Akun'], 401);
        }
    }

    public function loginAdmin(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'username'=> 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) return response(['message' => $validate->errors()], 400);
        
        if(!Auth::attempt($loginData)) return response(['message' => 'Username / Password Salah'], 401);
        
        $admin = Auth::admin();
        $token = $admin->createToken('Authentication Token')->accessToken;
        
        return response([
            'message' => 'Login Admin Berhasil',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }
}
