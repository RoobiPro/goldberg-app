<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\SurveyData;

class SurveryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      SurveyData::create([
          'surveydata_type' => 'drillingasdasd',
          'surveydata_id' => 1,
          'created_at' => now(),
          'updated_at' => now()
      ]);
    }
}
