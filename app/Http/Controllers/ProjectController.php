<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Facades\Auth;
use App\Models\User;

class ProjectController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      return ProjectResource::collection(Project::all());
    }

    public function testproject(){
      // $user = User::find(1);
      // return $user->projects;
      $project = Project::find(1);
      return $project->usersSorted;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = new Project;
        $project->name = $request->name;
        if ($request->has('client_id')){
          $project->client_id = $request->client_id;
        }
        $project->save();
        return response()->json("Project successfully created!", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    public function assignUser(Request $request)
    {
        $project = Project::find($request->projectId);
        $project->users()->attach(User::find($request->id), ['role' => $request->role]);
        // $project->client_id = $request->client_id;
        $project->save();

        return response()->json("User successfully assigned!", 200);

    }

    public function assignClient(Request $request)
    {
        $project = Project::find($request->projectId);
        // $project->users()->attach(User::find($request->id), ['role' => 2]);
        $project->client_id = $request->client_id;
        $project->save();

        return response()->json("Client successfully assigned!", 200);
    }

    public function unassignUser(Request $request){
        $user = User::find($request->user_id);
        $project = Project::find($request->project_id);
        $project->users()->detach($user);
        return response()->json("User successfully unassigned!", 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->users()->detach();
        $project->delete();
        return response()->json("Project successfully deleted!", 200);
    }
}
