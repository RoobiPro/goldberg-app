<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alteration extends Model
{
    use HasFactory;

    protected $fillable = [
      'drilling_id',
      'sample_list_id',
      'intensity',
      'utm_x',
      'utm_y',
      'utm_z',
      'from',
      'to',
      'alteration_code',
      'alteration_description',
      'created_at',
      'updated_at',
      'csv_import_id'
    ];

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

    public function drilling(){
      return $this->belongsTo(\App\Models\Campaigns\Drilling::class);
    }

}
