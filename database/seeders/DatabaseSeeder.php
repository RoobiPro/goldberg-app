<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $this->call([
                      UsersSeeder::class,
                      ProjectSeeder::class,
                      // LithologySeeder::class,
                      // DrillingSeeder::class,
                      // WellSeeder::class,
                      // ChemicalElementsSeeder::class,
                      // SurveryDataSeeder::class
                    ]);


        // $this->call(ProjectSeeder::class);
        // $this->call(CampaignSeeder::class);
        // $this->call(DrillingSeeder::class);
        // $this->call(WellSeeder::class);
        // $this->call(ChemicalElementsSeeder::class);

        // \Artisan::call('passport:install');

    }
}
