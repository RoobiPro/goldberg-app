<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assay extends Model
{
    use HasFactory;

    protected $fillable = [
      'sample_list_id',
      'utm_x',
      'utm_y',
      'utm_z',
      'from',
      'to',
      'analysis_code',
      'laboratory',
      'certificate_number',
      'date_sent',
      'date_received',
      'created_at',
      'updated_at',
    ];

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }
}
