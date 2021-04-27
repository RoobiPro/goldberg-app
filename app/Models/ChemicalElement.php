<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChemicalElement extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = [
      'name',
      'symbol',
      'atomic_number'
    ];
}
