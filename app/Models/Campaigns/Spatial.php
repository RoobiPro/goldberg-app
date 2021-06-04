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
      'csv_import_id'
    ];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function comments()
    {
        return $this->morphMany(\App\Models\Spare\Comment::class, 'commentable');
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class)
    }
}
