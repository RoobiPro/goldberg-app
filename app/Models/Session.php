<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'user_id',
      'start_time',
      'end_time',
      'duration',
    ];

    public function user(){
      return $this->belongsTo(User::class);
    }
}
