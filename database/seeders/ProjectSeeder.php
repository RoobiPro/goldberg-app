<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
      Project::factory()->count(10)->create();

      $project = Project::find(1);
      $project->users()->attach(User::find(1), ['role' => 3]);
      $project = Project::find(2);
      $project->users()->attach(User::find(1), ['role' => 0]);
      $project = Project::find(3);
      $project->users()->attach(User::find(1), ['role' => 1]);
      $project = Project::find(4);
      $project->users()->attach(User::find(1), ['role' => 2]);
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
