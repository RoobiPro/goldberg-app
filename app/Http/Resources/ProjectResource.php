<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;
use Carbon\Carbon;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        $project = Project::find($this->id);
        $users = $project->users;
        // $cords = ToLL($this->utm_x, $this->utm_y);


        return [
          'id' => $this->id,
          'name' => $this->name,
          'project_code' => $this->project_code,
          'type' => $this->type,
          'client' => $this->client,
          'users' => $users,
          // 'project_start_date' => Carbon::parse($this->project_start_date)->format('d.m.Y'),
          'project_start_date' =>$this->project_start_date,
          'utm_x' => $this->utm_x,
          'utm_y' => $this->utm_y,
          'utm_z' => $this->utm_z,
          // 'longitude' => $cords['lat'],
          // 'latitude' => $cords['lon'],
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ];
    }
}
