<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SampleList extends Model
{
    use HasFactory;

    protected $fillable = [
      'listabel_campaign_type',
      'listabel_campaign_id',
      'listabel_data_type',
      'listabel_data_id',
      'created_at',
      'updated_at'
    ];

    public function project()
    {
      // return class_basename($this->listabel_campaign());
      // $class = '\\Editor\\' . $definition;
      // $foo = new $class();
      return $this->hasOneThrough(\App\Models\Project::class, App::make('\App\Models\Campaigns\Drilling'), 'project_id');

        // return $this->hasOneThrough(Project::class, \App::make(class_basename($this->listabel_campaign())));
    }

    public function listabel_campaign()
    {
        return $this->morphTo();
    }

    public function listabel_data()
    {
        return $this->morphTo();
    }

}
