<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\hu_HU\Text;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'category_id' =>  Category::inRandomOrder()->first()->id,
            'is_completed' => fake()->boolean(70),
            'priority' => fake()->randomElement(['alacsony', 'közepes', 'magas']),
            'task' => fake()->realText(100)
        ];
    }
}
