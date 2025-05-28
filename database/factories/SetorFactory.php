<?php

namespace Database\Factories;

use App\Models\Setor;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

class SetorFactory extends Factory
{
    protected $model = Setor::class;

    public function definition()
    {
        return [
            'evento_id' => Evento::factory(),
            'nome' => $this->faker->word(),
            'descricao' => $this->faker->sentence(),
        ];
    }
}
