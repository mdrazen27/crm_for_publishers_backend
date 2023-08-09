<?php

namespace Database\Factories;

use App\Models\Advertisement;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistic>
 */
class StatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => fake()->countryCode(),
            'count' => rand(1, 100_000),
            'publisher_id' => Publisher::inRandomOrder()->first('id')->id,
            'date' => fake()->dateTimeBetween('-3months', '+3months'),
            'advertisement_id' => function (array $attributes) {
                return Advertisement::where('publisher_id', $attributes['publisher_id'])
                    ->inRandomOrder()
                    ->first('id')
                    ->id;
            },
        ];
    }
}
