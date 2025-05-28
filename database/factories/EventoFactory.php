<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\Produtor;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition()
    {
        return [
            'produtor_id' => Produtor::factory(),
            'nome' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(),
            'data_evento' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'banner_url' => $this->faker->imageUrl(640, 480, 'events', true),
            'localizacao' => $this->faker->address(),
        ];
    }
}
