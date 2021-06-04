<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingMineralization extends Model
{
    use HasFactory;

    protected $fillable = [
      'drilling_id',
      'sample_list_id',
      'from',
      'to',
      'intensity',
      'mineralization_code',
      'mineralization_description',
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
}
