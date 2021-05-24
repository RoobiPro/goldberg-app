<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drilling extends Model
{
    use HasFactory;

    protected $fillable = [
      'campaign_id',
      'coordinates_x',
      'coordinates_y',
      'coordinates_z',
      'azimuth',
      'dip',
      'length',
      'drilling_type',
      'start_date',
      'end_date',
      'created_at',
      'updated_at'
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function samplelist()
    {
        return $this->morphMany(\App\Models\SampleList::class, 'samplelistable');
    }
}
