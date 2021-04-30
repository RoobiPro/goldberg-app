<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $roleName = 'User';
        if($this->role == 1)
          $roleName = 'Client';
        else if($this->role ==2)
          $roleName = 'Superadmin';

        return [
          'id' => $this->id,
          'name' => $this->name,
          'email' => $this->email,
          'role' => $this->role,
          'role_name' => $roleName,
          'created_at' => $this->created_at,
          'updated_at' => $this->updated_at,
        ];

    }
}
