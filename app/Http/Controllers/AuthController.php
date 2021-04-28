<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{
  public function login(Request $request)
  {
     if (Auth::attempt($request->only(['email', 'password']))) {
        return response()->json([ "success" => true, "user" => Auth::user() ], 200);
         // return response(["success" => Auth::user()], 200);
     } else {
         return response(["success" => false], 403);
     }
  }

  public function logout(Request $request)
  {
    // Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();
  }

  public function refresh(Request $request){
    return response()->json([ "success" => true, "user" => Auth::user() ], 200);
  }

}
