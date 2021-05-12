<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        User::create([
            'name' => 'Eduardo',
            'email' => 'edoguerra@goldbergresources.com',
            'role' => 2,
            'password' => Hash::make('miclave1')
        ]);
        User::create([
            'name' => 'Juan Carlos',
            'email' => 'juancarlos@goldbergresources.com',
            'role' => 2,
            'password' => Hash::make('miclave1')
        ]);

        // factory(User::class, 120)->create();
        User::factory()->count(20)->create();
    }
}
