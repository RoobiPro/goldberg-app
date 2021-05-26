<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SampleList;
use App\Models\Campaigns\Drilling;
use App\Models\Data\Lithology;
use Illuminate\Support\Facades\Schema;

class SampleListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $drilling = Drilling::find(1);
        // $lithology = Lithology::find(1);
        // $sampleList = new SampleList();
        // // $sampleList->VATER= 'alo';
        // $lithology->samplelist()->save($sampleList);
        // $drilling->samplelist()->save($sampleList);

        // $sampleList = Lithology::find(1)->samplelist->listabel_campaign->project;
        // $columns = Schema::getColumnListing('assays');
        $userHeaders = getTableHeaders('users'); // dump the result and die
        // $sampleList = class_basename(SampleList::find(1)->listabel_campaign);
        // $sampleList = SampleList::find(1)->project;


        return $userHeaders;
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
