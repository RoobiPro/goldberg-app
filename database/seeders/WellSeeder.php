<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Well;


class WellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Well::create([
          'campaign_id' => 1,
          'coordinates_x' => 176.0944492,
          'coordinates_y' => -43.6173664,
          'coordinates_z' => 15.55,
          'azimuth' => 18.44,
          'dip' => 20.51,
          'length' => 15.5,
          'drilling_type' => 'Gravel',
          'start_date' => now(),
          'end_date' => now(),
          'created_at' => now(),
          'updated_at' => now()
      ]);
      Well::create([
          'campaign_id' => 1,
          'coordinates_x' => 150.0944492,
          'coordinates_y' => -43.6173664,
          'coordinates_z' => 15.55,
          'azimuth' => 18.44,
          'dip' => 20.51,
          'length' => 15.5,
          'drilling_type' => 'Gravel',
          'start_date' => now(),
          'end_date' => now(),
          'created_at' => now(),
          'updated_at' => now()
      ]);
    }
}
