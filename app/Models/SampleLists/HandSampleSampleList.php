<?php

namespace App\Models\SampleLists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandSampleSampleList extends Model
{
    use HasFactory;

    protected $fillable = [
      'hand_sample_id',
      'hand_sample_code',
      'project_id',

      'utm_x',
      'utm_y',
      'utm_z',
      'weight',
      'csv_import_id',
      'created_at',
      'updated_at'
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function handsample(){
      return $this->belongsTo(\App\Models\Drilling::class);
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

}
