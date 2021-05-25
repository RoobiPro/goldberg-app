<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use App\Models\Project;
use App\Models\User;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
      $faker = Faker::create();
      $companies = [
        'Antofagasta plc',
        'CAP S.A.',
        'Codelco	State-owned enterprise',
        'Compañía de Acero del Pacífico',
        'Compañía Minera San Esteban Primera',
        'Coro Mining Corp.',
        'Herencia Resources',
        'Minera Escondida',
        'Minera Valparaíso',
        'Santiago Metals Proyecto 4',
        'Sigdo Koppers',
        'Sociedad Química y Minera',
        'SQM'];

        foreach ($companies as $comp) {
          $timestamp = mt_rand(1430438400, time());
          Project::create([
              'name' => $comp,
              'project_start_date' => $faker->date('Y-m-d', $timestamp),
              'utm_x' => rand(12, 57) / 10,
              'utm_y' => rand(12, 57) / 10,
              'utm_z' => rand(12, 57) / 10,
              'created_at' => now(),
              'updated_at' => now(),
          ]);
        }

      // Project::factory()->count(10)->create();

      // $project = Project::find(1);
      // $project->users()->attach(User::find(1), ['role' => 3]);
      // $project = Project::find(2);
      // $project->users()->attach(User::find(1), ['role' => 0]);
      // $project = Project::find(3);
      // $project->users()->attach(User::find(1), ['role' => 1]);
      // $project = Project::find(4);
      // $project->users()->attach(User::find(1), ['role' => 2]);
      //
      // $project = Project::find(1);
      // $project->users()->attach(User::find(2), ['role' => 0]);
      // $project = Project::find(1);
      // $project->users()->attach(User::find(3), ['role' => 1]);
      // $project = Project::find(1);
      // $project->users()->attach(User::find(4), ['role' => 2]);
      // $project = Project::find(1);
      // $project->users()->attach(User::find(5), ['role' => 3]);
      // foreach(Project::all() as $project){
      //     $users = User::inRandomOrder()->take(rand(1,3))->pluck('id');
      //     $project->users()->attach($users);
      // }
      // $user = User::find(11);
      // // dd($user);
      // $userpro = $user->projects;
      // dd(sizeof($userpro));
      // foreach ($userpro as $project) {
      //   dd($project);
      // }
    }
}
