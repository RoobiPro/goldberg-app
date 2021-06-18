<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandSample extends Model
{
    use HasFactory;

    protected $fillable = [
      'project_id',
      'hand_sample_campaign',
      'hand_sample_code',
      'type',
      'utm_x',
      'utm_y',
      'utm_z',
      'date',
      'csv_import_id',
      'created_at',
      'updated_at'
    ];


    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function samplelists(){
        return $this->hasMany(\App\Models\SampleLists\HandSampleSampleList::class);
    }

    public function comments(){
        return $this->morphMany(\App\Models\Spare\Comment::class, 'commentable');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }
}
