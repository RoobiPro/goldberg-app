<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Facades\Auth;
use App\Models\User;
use App\Models\Campaign;
use Carbon\Carbon;


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
        $project->project_start_date = $request->date;
        // $project->project_start_date =  Carbon::parse($this->project_start_date)->format('d.m.Y');

        $project->coordinates_x = $request->coordinates_x;
        $project->coordinates_y = $request->coordinates_y;
        $project->coordinates_z = $request->coordinates_z;
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
      $project = Project::find($id);
      $campaigns = $project->campaigns;

      // $project_obj-> = $
      $drillings = $project->drillings;
      $wells = $project->wells;

      $drillings_count = count($drillings);
      $wells_count = count($wells);

      $project->drillings_count = $drillings_count;
      $project->wells_count = $wells_count;

      // $project_obj = $project;
      // $samples = $project->samples;
      // return response()->json("alos", 200);
      return response()->json($project, 200);
    }

    public function showCampaigns($id){

      $project = Project::find($id);
      $campaigns = $project->campaigns;

      // echo $campaigns[0]->id;
      // $campaign_list = [];

      $index = 0;

      foreach ($campaigns as $campaign) {
        // $drillings = $campaign->drillings;
        $campaigns[$index]->start_date = Carbon::parse($campaigns[$index]->start_date)->format('d.m.Y');
        $campaigns[$index]->end_date = Carbon::parse($campaigns[$index]->end_date)->format('d.m.Y');
        $drillingscount = count($campaign->drillings);
        $wellscount = count($campaign->wells);
        $samplescount = count($campaign->samples);
        $spatialscount = count($campaign->spatials);
        // echo $drillingscount.' ';

        $campaigns[$index]->drillings_count = $drillingscount;
        $campaigns[$index]->wells_count = $wellscount;
        $campaigns[$index]->samples_count = $samplescount;
        $campaigns[$index]->spatials_count = $spatialscount;

        $index ++;

        // $drillings = $campaignobj->drillings;
        // echo $drillings;
        // echo $campaignobj;

        // code...
      }

      // return $campaigns;

      // $campaign_list = [];



      return response()->json($campaigns, 200);;
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
        $project = Project::find($request->id);
        $project->name = $request->name;
        $project->project_start_date = $request->date;
        $project->coordinates_x = $request->coordinates_x;
        $project->coordinates_y = $request->coordinates_y;
        $project->coordinates_z = $request->coordinates_z;
        $project->save();
        return response()->json("Project successfully updated!", 200);
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

    public function reassignUser(Request $request){
      $user = User::find($request->user_id);
      $project = Project::find($request->project_id);
      $project->users()->detach($user);
      $project->users()->attach($user, ['role' => $request->role]);
      return response()->json("User role successfully changed!", 200);
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
    public function getProjectUsers($id){

      $project = Project::find($id);
      $projectUsers = $project->users;

      $allUsers = User::where('role', 0)->get();
      // return response()->json($allUsers, 200);

      // return $allUsers;
      $ProjectUsersIds = [];

      foreach($projectUsers as $item){
        array_push($ProjectUsersIds, $item->id);
      }

      $selected = [];
      foreach ($allUsers as $key => $value) {
            if (!in_array($value->id,$ProjectUsersIds)) {
                $selected[] = $value;
                $allUsers->forget($key);
            }
      }

      return response()->json($selected, 200);
      // return response()->json($myArray = json_decode(json_encode($allUsers), true), 200);
      return gettype();
    }
}
