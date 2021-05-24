<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandSample extends Model
{
    use HasFactory;

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function samplelist()
    {
        return $this->morphMany(SampleList::class, 'samplelistable');
    }
}
