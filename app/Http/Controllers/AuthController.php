<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Facades\Hash;

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

  public function register(Request $request)
  {
      if(!Auth::check()){
        return response()->json("Not authenticated", 401);
      }

      $loggedUser = Auth::user();

      if($loggedUser->role==2){
        if ($request->has('role')){
          if($request->role==0){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 0,
                'password' => Hash::make($request->password)
            ]);
            return response()->json("User created", 200);
          }
          if($request->role==1){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 1,
                'password' => Hash::make($request->password)
            ]);
            return response()->json("Client created", 200);
          }
          if($request->role==2){
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 2,
                'password' => Hash::make($request->password)
            ]);
            return response()->json("Superadmin created", 200);
          }
        }
        else{
          User::create([
              'name' => $request->name,
              'email' => $request->email,
              'role' => 0,
              'password' => Hash::make($request->password)
          ]);
          return response()->json("User created", 200);
        }
      }
      else{
        return response()->json("No Superadmin", 403);
      }
  }

}
