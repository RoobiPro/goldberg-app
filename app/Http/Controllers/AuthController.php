<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AuthController extends Controller
{

  // Login Function
  public function login(Request $request)
  {
     if (Auth::attempt($request->only(['email', 'password']))) {
        return response()->json([ "success" => true, "user" => Auth::user() ], 200);
         // return response(["success" => Auth::user()], 200);
     } else {
         return response(["success" => false], 403);
     }
  }

  // Logout Function
  public function logout(Request $request)
  {
    // return 'vaddder';
    Auth::guard('web')->logout();
    // return $request;
    $request->session()->invalidate();
    //
    $request->session()->regenerateToken();

    return response(["success" => true], 200);
  }


  // Refresh the User
  public function refresh(Request $request){

    return response()->json([ "success" => true, "user" => Auth::user() ], 200);

  }

}
