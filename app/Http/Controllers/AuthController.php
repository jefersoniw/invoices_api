<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 3|YP46bv6eNsIJDdUhT4CB37D6bz6ikfqZ4AT9H3Ul8ab97cd8

class AuthController extends Controller
{

    use HttpResponses;

    public function login(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('Authorized', 200, [
                'token' => $request->user()->createToken('invoice-token')->plainTextToken
            ]);
        }

        return $this->response('Not Authorized', 403);
    }

    public function logout()
    {
    }
}
