<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//6|lfmxamySRgEU7mEyPhsBWOLC2zNIPSuSbRhpZtJx4484c7f5 invoice-srote
//7|1DXvcyDcCpb9SQDBnq3WuGBFuum1piExsbhVpgRjc754a955 user-store
// 8|LqgngM31wT684I8c8iAOrLZkkv5HBd95UqRSsxXm3c1ca9ee teste-index

class AuthController extends Controller
{

    use HttpResponses;

    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            return $this->response('Authorized', 200, [
                'token' => $request->user()->createToken('invoice-token', ['teste-index'])->plainTextToken
            ]);
        }

        return $this->response('Not Authorized', 403);
    }

    public function logout()
    {
    }
}
