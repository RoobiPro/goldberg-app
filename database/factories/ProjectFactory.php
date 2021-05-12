<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *getProjectUsers
     * @return array
     */
    public function definition()
    {
        $timestamp = mt_rand(1525639948, time());
        return [
          'name' => $this->faker->name,
          'project_start_date' => $this->faker->date('Y-m-d', $timestamp),
          'coordinates_x' => rand(12, 57) / 10,
          'coordinates_y' => rand(12, 57) / 10,
          'coordinates_z' => rand(12, 57) / 10,
          // 'client' =>
          'created_at' => now(),
          'updated_at' => now(),
        ];
    }
}
