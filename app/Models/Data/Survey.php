<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
      'suveryable_type',
      'suveryable_id',
      'type',
      'from',
      'to',
      'azimuth',
      'dip',
      'created_at',
      'updated_at',
      'csv_import_id'
    ];


    public function surveyable()
    {
        return $this->morphTo();
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }
}
