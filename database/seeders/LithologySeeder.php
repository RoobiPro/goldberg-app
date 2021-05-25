<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data\Lithology;


class LithologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Lithology::create([
          'sample_list_id' => 1,
          'utm_x' =>  176.0944492,
          'utm_y' => 176.0944492,
          'utm_z' => -43.6173664,
          'from' => 15.55,
          'to' => 15.55,
          'rock_description' => 'Vadder rock',
          'geological_unit_code' => '2222',
          'created_at' => now(),
          'updated_at' => now()
      ]);
      Lithology::create([
        'sample_list_id' => 1,
        'utm_x' =>  176.0944492,
        'utm_y' => 176.0944492,
        'utm_z' => -43.6173664,
        'from' => 15.55,
        'to' => 15.55,
        'rock_description' => 'Vadder rock',
        'geological_unit_code' => '2222',
        'created_at' => now(),
        'updated_at' => now()
      ]);
    }
}
