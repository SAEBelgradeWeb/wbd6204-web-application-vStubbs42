<?php

namespace Database\Factories;

use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Board::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement($array = array ('Bigboard','Cityboard','Megaboard')),
            'size' => $this->faker->randomElement($array = array ('10 x 10','10 x 15','10 x 30')),
            'price' => $this->faker->randomElement($array = array ('100','120','150')),
            'location' => $this->faker->randomElement($array = array ('Highway','Throughway','Walkway')),
            'image' => $this->faker->asciify('************'),
            'status' => ('pending'),

        ];
    }
}
