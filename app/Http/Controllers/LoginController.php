<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        try {
            if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $success = true;
                $data = Auth::user();
            } else {
                $success = false;
                $data = 'Invalid username or password';
            }
        } catch (\Throwable$th) {
            throw $th;
        }

        $response = [
            'success' => $success,
            'data' => $data,
        ];

        return response()->json($response);
    }
}
