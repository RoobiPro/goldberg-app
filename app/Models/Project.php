<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_at', 'updated_at'];

    public function users()
    {
        // return $this->belongsToMany(User::class);
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
        ->withPivot(['role']);
    }

    public function campaings(){
      return $this->hasMany(\App\Models\Campaign::class);
    }

    // public function users()
    // {
    //   return $this->belongsToMany('App\Models\Role', 'project_user_role', 'project_id');
    // }


}
