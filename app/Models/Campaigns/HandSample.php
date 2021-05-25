<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandSample extends Model
{
    use HasFactory;

    protected $fillable = [
      'project_id',
      'type',
      'utm_x',
      'utm_y',
      'utm_z',
      'created_at',
      'updated_at'
    ];


    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function samplelist()
    {
        return $this->morphMany(\App\Models\SampleList::class, 'listabel_campaign');
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Spare\Comment::class, 'commentable');
    }
}
