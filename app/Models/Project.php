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
      'project_code',
      'type',
      'client_id',
      'project_start_date',
      'utm_x',
      'utm_y',
      'utm_z',
      'created_at',
      'updated_at'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'project_id', 'user_id')
        ->withPivot(['role']);
    }

    public function client(){
      return $this->belongsTo(User::class);
    }

    public function drillings()
    {
        return $this->hasMany(Campaigns\Drilling::class);
    }

    public function wells()
    {
        return $this->hasMany(Campaigns\Well::class);
    }

    public function spatials()
    {
        return $this->hasMany(Campaigns\Spatial::class);
    }

    public function handsamples()
    {
        return $this->hasMany(Campaigns\HandSample::class);
    }

    public function samplelists()
    {
        return $this->hasMany(\App\Models\SampleList::class);
    }

    public function surveys()
    {
        return $this->hasMany(\Data\Survey::class);
    }

    public function drilling_sample_list()
    {
        return $this->hasMany(SampleLists\DrillingSampleList::class);
    }

    public function well_sample_list()
    {
        return $this->hasMany(SampleLists\WellSampleList::class);
    }

    public function hand_sample_sample_list()
    {
        return $this->hasMany(SampleLists\HandSampleSampleList::class);
    }

    public function drilling_assays()
    {
        return $this->hasMany(Data\DrillingAssay::class);
    }
    // public function users()
    // {
    //   return $this->belongsToMany('App\Models\Role', 'project_user_role', 'project_id');
    // }


}
