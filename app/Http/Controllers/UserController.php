<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Session;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


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


    public function deleteAllSessions(){
      if(Auth::user()->role!=2){
        return response()->json([ "success" => false, "type" => 'red', "message" => "Not authorized!"], 200);
      }
      $currentSession= Session::where('user_id', Auth::user()->id)->where('active', true)->get()->first();
      $allsession = Session::all();
      foreach ($allsession as $session) {
        if($session->id!=$currentSession->id){
          $session->delete();
        }
      }
      return response()->json([ "success" => true, "type" => 'green', "message" => "All session logs deleted!"], 200);
    }

    public function deleteUserSessions($id){
      if(Auth::user()->role!=2){
        return response()->json([ "success" => false, "type" => 'red', "message" => "Not authorized!"], 200);
      }
      $user = User::find($id);
      $sessions = $user->sessions;
      if(Auth::user()->id == $id){
        foreach ($sessions as $session){
          if($session->active!=1){
            $session->delete();
          }
        }
      }
      else{
        foreach ($sessions as $session){
          $session->delete();
        }
      }

      return response()->json([ "success" => true, "type" => 'green', "message" => "All session logs of user deleted!"], 200);
    }

    public function getUserSessions($id){
      $user = User::find($id);
      $sessions = $user->sessions;
      foreach ($sessions as $session){
        $session->start_time = \Carbon\Carbon::parse($session->start_time)->format('(d.m.Y) h:i:s');
        if($session->end_time!=null)
        $session->end_time = \Carbon\Carbon::parse($session->end_time)->format('(d.m.Y) h:i:s');
        if($session->duration!=null)
        $session->duration_in_s = $session->duration;
        if($session->duration!=null)
        $session->duration = gmdate("H:i:s", $session->duration);
        if($session->last_alive!=null)
        $session->last_alive = \Carbon\Carbon::parse($session->last_alive)->format('(d.m.Y) h:i:s');

      }
      return response()->json($sessions, 200);

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

    function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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

    public function getClientProjects($id){
      $projects = Project::where('client_id', $id)->get();
      return response()->json($projects, 200);
    }

    public function getUserProjects($id){
      // return utm2ll(514085.99, 7429756.22, 19,false);
      // return true;
      $user = User::find($id);
      // return $user;
      $projects = $user->projects;
      $index = 0;
      // dd($projects[2]->utm_x);
      foreach ($projects as $project){

        $cords = utm2ll($projects[$index]->utm_x, $projects[$index]->utm_y, 19, false);
        // dd($cords['lat']);
        $projects[$index]->latitude = $cords['lat'];
        $projects[$index]->longitude = $cords['lon'];
        $projects[$index]->project_start_date = Carbon::parse($projects[$index]->project_start_date)->format('d.m.Y');
        $projects[$index]->role = getRoleName($projects[$index]->pivot->role);
        // $projects[$index]->count_spatial = count($projects[$index]->spatials);
        // $projects[$index]->count_drilling = count($projects[$index]->drillings);
        // $projects[$index]->count_wells = count($projects[$index]->wells);
        // $projects[$index]->count_handsamples = count($projects[$index]->handsamples);
        // $projects[$index]->count_samplelists = count($projects[$index]->samplelists);


        // dd($projects[$index]);
        $index++;
      }
      return response()->json($projects, 200);
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
        // return response()->json('msg'="User's password was updated!", 200);
        return response()->json([ "success" => true, "msg" => "User's password was updated!", "user" => $user ], 200);

      }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();
        return response()->json([ "success" => true, "msg" => "User was updated!", "user" => $user ], 200);


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
      return response()->json([ "success" => true, "msg" => "User was successfully deleted!"], 200);
    }

    public function upload_user_photo(Request $request){

      if($request->file('avatar')){


          if(auth('sanctum')->user()->avatar != "default_avatar.png"){

              Storage::disk('user_avatars')->delete(auth('sanctum')->user()->avatar);
          }


          // processing the uploaded image
          // return response()->json($this->generateRandomString(), 200);
          //
          $avatar_name = $this->generateRandomString().'.'.$request->file('avatar')->getClientOriginalExtension();
          // return response()->json("alo2", 200);
          $avatar_path = $request->file('avatar')->storeAs('',$avatar_name, 'user_avatars');

          // Update user's avatar column on 'users' table
          $profile = User::find(auth('sanctum')->user()->id);
          $profile->avatar = $avatar_path;

          // return response()->json($avatar_path, 200);

          if($profile->save()){
              return response()->json([
                  'status'    =>  'success',
                  'message'   =>  'Profile Photo Updated!',
                  'avatar_url'=>  url('storage/user-avatar/'.$avatar_path),
                  'avatar' => $profile->avatar
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
