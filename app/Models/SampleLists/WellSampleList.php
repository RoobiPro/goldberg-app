<?php

namespace App\Models\SampleLists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellSampleList extends Model
{
    use HasFactory;

    protected $fillable = [
      'well_id',
      'project_id',
      'sample_code',
      'from',
      'to',
      'weight',
      'csv_import_id',
      'created_at',
      'updated_at',
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function well(){
      return $this->belongsTo(\App\Models\Campaigns\Well::class);
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

}
