<?php

namespace Database\Factories;

use App\Models\Produtor;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutorFactory extends Factory
{
    protected $model = Produtor::class;

    public function definition()
    {
        return [
            'usuario_id' => Usuario::factory(),
            'nome_empresa' => $this->faker->company(),
        ];
    }
}
