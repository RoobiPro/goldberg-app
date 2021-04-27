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
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 1,
          'description' => 'seccond campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 1,
          'description' => 'third campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);

      Campaign::create([
          'project_id' => 2,
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);
      Campaign::create([
          'project_id' => 3,
          'description' => 'first campaign',
          'created_at' => now(),
          'updated_at' => now()
      ]);
    }
}
