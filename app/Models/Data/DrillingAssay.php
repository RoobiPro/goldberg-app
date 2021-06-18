<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrillingAssay extends Model
{
    use HasFactory;

    protected $fillable = [
      'drilling_id',
      'sample_list_id',
      'sample_code',
      'from',
      'to',
      'certificate',
      'element_Ag',
      'element_Al',
      'element_As',
      'element_Au',
      'element_B',
      'element_Ba',
      'element_Be',
      'element_Bi',
      'element_Ca',
      'element_Cd',
      'element_Ce',
      'element_Co',
      'element_Cr',
      'element_Cs',
      'element_Cu',
      'element_Fe',
      'element_Ga',
      'element_Ge',
      'element_Hf',
      'element_Hg',
      'element_In',
      'element_K',
      'element_La',
      'element_Li',
      'element_Mg',
      'element_Mn',
      'element_Mo',
      'element_Na',
      'element_Nb',
      'element_Ni',
      'element_P',
      'element_Pb',
      'element_Rb',
      'element_Re',
      'element_S',
      'element_Sb',
      'element_Sc',
      'element_Se',
      'element_Sn',
      'element_Sr',
      'element_Ta',
      'element_Te',
      'element_Th',
      'element_Ti',
      'element_Tl',
      'element_U',
      'element_V',
      'element_W',
      'element_Y',
      'element_Zn',
      'element_Zr',
      'created_at',
      'updated_at',
      'csv_import_id'
    ];


    public function drilling(){
      return $this->belongsTo(\App\Models\Campaigns\Drilling::class);
    }

    public function import(){
      return $this->belongsTo(\App\Models\CsvImport::class);
    }

}
