<?php

namespace App\Models\SampleLists;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingSampleList extends Model
{
    use HasFactory;

    protected $fillable = [
      'drilling_id',
      'sample_code',
      'from',
      'to',
      'sample_type',
      'weight',
      'csv_import_id',
      'created_at',
      'updated_at'
    ];

    public function drilling(){
      return $this->belongsTo(\App\Models\Campaigns\Drilling::class);
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

}
