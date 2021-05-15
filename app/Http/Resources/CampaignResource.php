<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Campaign;
use Carbon\Carbon;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
          // $campaign = Campaign::find($this->id);
          // $drillings = $campaign->drillings;
          // $wells = $campaign->wells;
          // $samples = $campaign->samples;
          //
          // return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            // 'client' => $this->client,
            // 'users' => $users,
            // // 'project_start_date' => Carbon::parse($this->project_start_date)->format('d.m.Y'),
            // 'project_start_date' =>$this->project_start_date,
            // 'coordinates_x' => $this->coordinates_x,
            // 'coordinates_y' => $this->coordinates_y,
            // 'coordinates_z' => $this->coordinates_z,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
          ];


    }
}
