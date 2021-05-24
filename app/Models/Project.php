<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'client_id',
      'created_at',
      'updated_at',
      'project_start_date',
      'coordinates_x',
      'coordinates_y',
      'coordinates_z'
    ];

    public function users()
    {
        // return $this->belongsToMany(User::class);
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
        ->withPivot(['role']);
    }

    public function client(){
      return $this->belongsTo(User::class);
    }

    public function drillings()
    {
        return $this->hasMany(\Campaigns\Drilling::class);
    }

    public function wells()
    {
        return $this->hasMany(\Campaigns\Well::class);
    }

    public function spatial()
    {
        return $this->hasMany(\Campaigns\Spatial::class);
    }

    public function handsamples()
    {
        return $this->hasMany(\Campaigns\HandSample::class);
    }

    public function samplelist()
    {
        return $this->hasMany(\Campaigns\SampleList::class);
    }
    // public function users()
    // {
    //   return $this->belongsToMany('App\Models\Role', 'project_user_role', 'project_id');
    // }


}
