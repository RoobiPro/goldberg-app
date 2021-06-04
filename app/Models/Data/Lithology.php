<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lithology extends Model
{
    use HasFactory;

    protected $fillable = [
      'lithologyable_type',
      'lithologyable_id',
      'sample_list_id',
      'from',
      'to',
      'rock_code',
      'rock_description',
      'geological_unit',
      'csv_import_id',
      'created_at',
      'updated_at',
    ];



    // public function campaign()
    // {
    //     return $this->samplelist()->listabel_campaign_type;
    //     return $this->hasOneThrough(Owner::class, Car::class);
    // }
    public function lithologyable()
    {
      return $this->morphTo();
    }

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }
}
