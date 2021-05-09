<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      return UserResource::collection(User::all());
    }

    public function getUsers(Request $request){
      return UserResource::collection(User::all()->where('role', 0));
    }

    public function getClients(Request $request){
      return UserResource::collection(User::all()->where('role', 1));
    }

    public function getAdmins(Request $request){
      return UserResource::collection(User::all()->where('role', 2));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req)
    {
        return $req->user();

    }

    public function getUserProjects($id){
      $user = User::find($id);
      return response()->json($user->projects, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // return response()->json($request, 200);
      $user = User::find($id);
      if($request->has('password')){
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json("User's password was updated!", 200);
      }
      else{
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
        return response()->json('The user has been updated!', 200);
      }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $user = User::find($id);
      $user->projects()->detach();
      $user->delete();
      return response()->json('User was successfully deleted!', 200);
    }

    public function upload_user_photo(Request $request){
    // check if image has been received from form
    if($request->file('avatar')){
        // check if user has an existing avatar
        if($this->guard()->user()->avatar != NULL){
            // delete existing image file
            Storage::disk('user_avatars')->delete($this->guard()->user()->avatar);
        }

        // processing the uploaded image
        $avatar_name = $this->random_char_gen(20).'.'.$request->file('avatar')->getClientOriginalExtension();
        $avatar_path = $request->file('avatar')->storeAs('',$avatar_name, 'user_avatars');

        // Update user's avatar column on 'users' table
        $profile = User::find($request->user()->id);
        $profile->avatar = $avatar_path;

        if($profile->save()){
            return response()->json([
                'status'    =>  'success',
                'message'   =>  'Profile Photo Updated!',
                'avatar_url'=>  url('storage/user-avatar/'.$avatar_path)
            ]);
        }else{
            return response()->json([
                'status'    => 'failure',
                'message'   => 'failed to update profile photo!',
                'avatar_url'=> NULL
            ]);
        }

    }

      return response()->json([
          'status'    => 'failure',
          'message'   => 'No image file uploaded!',
          'avatar_url'=> NULL
      ]);
    }

}
