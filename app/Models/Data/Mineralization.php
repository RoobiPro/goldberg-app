<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mineralization extends Model
{
    use HasFactory;

    protected $fillable = [
      'sample_list_id',
      'intensity',
      'utm_x',
      'utm_y',
      'utm_z',
      'from',
      'to',
      'mineralization_description',
      'created_at',
      'updated_at',
    ];

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }
}