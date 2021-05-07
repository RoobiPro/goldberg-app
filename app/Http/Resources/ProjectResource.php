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

        return [
          'id' => $this->id,
          'name' => $this->name,
          'client' => $this->client,
          'users' => $users,
          // 'project_start_date' => Carbon::parse($this->project_start_date)->format('d.m.Y'),
          'project_start_date' =>$this->project_start_date,
          'coordinates_x' => $this->coordinates_x,
          'coordinates_y' => $this->coordinates_y,
          'coordinates_z' => $this->coordinates_z,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ];
    }
}
