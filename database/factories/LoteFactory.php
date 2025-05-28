<?php

namespace Database\Factories;

use App\Models\Lote;
use App\Models\Setor;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    protected $model = Lote::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('-1 month', 'now');
        $end = $this->faker->dateTimeBetween($start, '+1 month');

        return [
            'setor_id' => Setor::factory(),
            'nome' => $this->faker->word(),
            'preco' => $this->faker->randomFloat(2, 5, 100),
            'quantidade' => $this->faker->numberBetween(10, 500),
            'data_inicio' => $start,
            'data_fim' => $end,
            'descricao' => $this->faker->sentence(),
        ];
    }
}
