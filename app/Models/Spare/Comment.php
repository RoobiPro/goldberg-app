<?php

namespace App\Models\Spare;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
      'text',
      'created_at',
      'updated_at',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
}
