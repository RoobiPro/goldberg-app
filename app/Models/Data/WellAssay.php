<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellAssay extends Model
{
    use HasFactory;

    protected $fillable = [
      'well_id',
      'sample',
      'sample_list_id',
      'analysis_code',
      'laboratory',
      'certificate_number',
      'certificate',
      'date_sent',
      'date_received',
      'from',
      'to',
      'element_Fe',
      'csv_import_id',
    ];

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }
}
