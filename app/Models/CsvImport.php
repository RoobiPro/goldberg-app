<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CsvImport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
      'table_type',
      'import_date',
      'file_name',
      'data_lines',
      'bytes',
      'success',
      'error_description'
    ];

    public function drilling()
    {
        return $this->hasMany(\App\Models\Campaigns\Drilling::class);
    }

    public function well()
    {
        return $this->hasMany(\App\Models\Campaigns\Well::class);
    }

    public function spatial()
    {
        return $this->hasMany(\App\Models\Campaigns\Spatial::class);
    }

    public function handsample()
    {
        return $this->hasMany(\App\Models\Campaigns\HandSample::class);
    }

    public function samplelist()
    {
        return $this->hasMany(\App\Models\Campaigns\SampleList::class);
    }

    public function survey()
    {
        return $this->hasMany(\App\Models\Data\Survey::class);
    }

    public function drilling_mineralization()
    {
        return $this->hasMany(\App\Models\Data\DrillingMineralization::class);
    }

    public function drilling_alteration()
    {
        return $this->hasMany(\App\Models\Data\Alteration::class);
    }

    public function drilling_assay()
    {
        return $this->hasMany(\App\Models\Data\DrillingAssay::class);
    }

    public function well_assay()
    {
        return $this->hasMany(\App\Models\Data\WellAssay::class);
    }

    public function lithology()
    {
        return $this->hasMany(\App\Models\Data\Lithology::class);
    }


}
