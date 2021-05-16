<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = ['project_id','name','description','start_date', 'end_date', 'created_at', 'updated_at'];

    public function project(){
      return $this->belongsTo(\App\Models\Project::class);
    }

    public function drillings(){
      return $this->hasMany(\App\Models\Drilling::class);
    }

    public function wells(){
      return $this->hasMany(\App\Models\Well::class);
    }

    public function samples(){
      return $this->hasMany(\App\Models\Sample::class);
    }

    public function spatials(){
      return $this->hasMany(\App\Models\Spatial::class);
    }

}
