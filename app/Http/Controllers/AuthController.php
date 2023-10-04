<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
