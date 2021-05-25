<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaigns\Drilling;


class DrillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Drilling::create([
          'project_id' => 1,
          'type' => 'Type A',
          'utm_x' => 176.0944492,
          'utm_y' => -43.6173664,
          'utm_z' => 15.55,
          'start_date' => now(),
          'end_date' => now(),
          'dip' => 20.51,
          'length' => 15.5,
          'azimuth' => 10.3
      ]);
      Drilling::create([
        'project_id' => 2,
        'type' => 'Type A',
        'utm_x' => 176.0944492,
        'utm_y' => -43.6173664,
        'utm_z' => 15.55,
        'start_date' => now(),
        'end_date' => now(),
        'dip' => 20.51,
        'length' => 15.5,
        'azimuth' => 10.3
      ]);
    }
}
