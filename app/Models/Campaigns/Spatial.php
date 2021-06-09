<?php

namespace App\Models\Campaigns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spatial extends Model
{
    use HasFactory;

    protected $fillable = [
      'project_id',
      'attachment',
      'file_type',
      'created_at',
      'updated_at',
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Spare\Comment::class, 'commentable');
    }

}
