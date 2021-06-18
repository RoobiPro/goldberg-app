<?php

namespace App\Models\SampleLists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingAssay extends Model
{
    use HasFactory;

    protected $fillable = [
      'hand_sample_id',
      'hand_sample_code',
      'utm_x',
      'utm_y',
      'utm_z',
      'weight',
      'csv_import_id',
      'created_at',
      'updated_at'
    ];


    public function handsample(){
      return $this->belongsTo(\App\Models\Drilling::class);
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

}
