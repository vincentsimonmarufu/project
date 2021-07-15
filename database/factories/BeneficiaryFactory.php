<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BeneficiaryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Beneficiary::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'mobile_number' => $this->faker->numberBetween(700000000,782877933),
            'id_number' => Str::random(9),
            'pin' => User::all()->random()->pin
        ];
    }
}
