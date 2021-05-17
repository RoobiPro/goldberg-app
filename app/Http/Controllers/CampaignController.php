<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Carbon\Carbon;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      return CampaignResource::collection(Campaign::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campaign = new Campaign();
        $data = $request->only($campaign->getFillable());
        $campaign->fill($data)->save();
        return response()->json("Campaign successfully created!", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $campaign = Campaign::find($id);
        $campaign->start_date = Carbon::parse($campaign->start_date)->format('d.m.Y');
        $campaign->end_date = Carbon::parse($campaign->end_date)->format('d.m.Y');
        $drillings = $campaign->drillings;
        foreach ($drillings as $drilling) {
          $drilling->start_date = Carbon::parse($drilling->start_date)->format('d.m.Y');
          $drilling->end_date = Carbon::parse($drilling->end_date)->format('d.m.Y');
        }

        $wells = $campaign->wells;
        $samples = $campaign->samples;
        $spatial = $campaign->spatials;
        // return response()->json([$campaign, $drillings, $wells, $samples], 200);
        return response()->json($campaign, 200);

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
