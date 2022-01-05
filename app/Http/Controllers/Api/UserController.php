<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function login (Request $request){
        //dd($request->all());die();

        $user = User::where('email', $request->email)->first();

        if($user){
            if (password_verify($request->password, $user->password)){
                return response()->json([
                        'success' => 1,
                        'message' => 'Selamat Datang '.$user->name,
                        'user' => $user
                    ]);
            }
            return $this->eror('Password Salah');
        }
        return $this->eror('Email Tidak Terdaftar');
    }

    public function register(Request $request){
        //
        $validasi = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'password ' => 'required|min:6'
        ]);

        if ($validasi->fails()){
            $val =$validasi->errors()->all();
            return $this -> error($val[0]);
        }

        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->password)
        ]));


        if($user){
            return response()->json([
                'success' => 1,
                'message' => 'Selamat datang Register Berhasil',
                'user' => $user
            ]);
        }
        return $this->error('Registrasi gagal');


    }


    public function error($pesan){
        return response()->json([
            'success' => 0,
            'message' => $pesan
        ]);
    }
}
