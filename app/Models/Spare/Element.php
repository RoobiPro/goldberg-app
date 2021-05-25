<?php

namespace App\Models\Spare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Element extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'name',
      'symbol',
      'atomic_number'
    ];
}
