<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Well extends Model
{
    use HasFactory;

    protected $fillable = [
      'project_id',
      'type',
      'well_code',
      'well_campaign',
      'utm_x',
      'utm_y',
      'utm_z',
      'date',
      'dip',
      'length',
      'azimuth',
      'created_at',
      'updated_at',
      'csv_import_id'
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function samplelists(){
      return $this->hasMany(\App\Models\SampleLists\WellSampleList::class);
    }

    public function surveys(){
      return $this->morphMany(\App\Models\Survey::class, 'suveryable');
    }

    public function comments(){
        return $this->morphMany(\App\Models\Spare\Comment::class, 'commentable');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }
}
