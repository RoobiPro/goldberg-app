<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lithology extends Model
{
    use HasFactory;

    protected $fillable = [
      'sample_list_id',
      'utm_x',
      'utm_y',
      'utm_z',
      'from',
      'to',
      'rock_description',
      'geological_unit_code',
      'created_at',
      'updated_at',
    ];



    // public function campaign()
    // {
    //     return $this->samplelist()->listabel_campaign_type;
    //     return $this->hasOneThrough(Owner::class, Car::class);
    // }

    public function samplelist()
    {
      return $this->morphOne(\App\Models\SampleList::class, 'listabel_data');
    }
}
