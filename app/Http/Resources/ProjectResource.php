<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Project;

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

          // 'email' => $this->email,
          // 'role' => $this->role,
          // 'role_name' => $roleName,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ];
    }
}
