<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Campaign::create([
          'project_id' => 1,
          'name' => 'first camp. name',
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 1,
          'name' => 'seccond camp. name',
          'description' => 'seccond campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 1,
          'name' => 'third camp. name',
          'description' => 'third campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 2,
          'name' => 'first camp. name',
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);
      Campaign::create([
          'project_id' => 3,
          'name' => 'first camp. name',
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);
    }
}
