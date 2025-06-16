<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request  = $request;
    }

    public function login()
    {
        $this->validate($this->request, [
            'username' => ['required'],
            'password' => ['required'],
        ], [
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi',
        ]);

        $request    = $this->request->only(['username', 'password']);

        $user   = User::where('username', '=', trim($request['username']))->first();
        if( !$user ) {
            return redirect( route('front') )->withErrors(['Username tidak ditemukan']);
        }

        $attempt    = Auth::attempt($request);
        if( $attempt ) {
            return redirect( route('non-tender.list') );
        }

        return redirect( route('front') )->withErrors(['Username dan password salah']);
    }

    public function logout()
    {
        // Auth::guard()->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return redirect( route('front') );
    }
}
