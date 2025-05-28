<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CadastroFeatureTest extends TestCase
{
    /** @test */
    public function a_cadastro_form_can_be_displayed()
    {
        $response = $this->get('/cadastro'); // Assumindo que a rota para showForm é /cadastro
        $response->assertStatus(200);
        $response->assertViewIs('cadastro'); // Verifica se a view 'cadastro' é retornada
    }

    use RefreshDatabase; // Garante um banco de dados limpo para cada teste

    /** @test */
    public function a_user_can_be_registered_with_valid_data()
    {
        $response = $this->post('/cadastro', [ // Assumindo que a rota para processForm é /cadastro (POST)
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '11987654321',
            'cpf/cnpj' => '12345678900', // Campo 'cpf/cnpj' no formulário
            'senha' => 'senhaSegura123',
        ]);

        $response->assertRedirect('/cadastro'); // Verifica o redirecionamento
        $response->assertSessionHas('success', 'Cadastro realizado com sucesso!'); // Verifica a mensagem de sucesso

        $this->assertDatabaseHas('usuarios', [ // Verifica se o usuário foi salvo no banco de dados
            'nome' => 'João Silva',
            'email' => 'joao@example.com',
            'telefone' => '11987654321',
            'cpf/cnpj' => '12345678900',
        ]);

        // Verifique se a senha foi hasheada (não é possível verificar o hash exato, mas podemos verificar que não é a senha em texto claro)
        $this->assertNotNull(Usuario::where('email', 'joao@example.com')->first()->senha);
        $this->assertNotEquals('senhaSegura123', Usuario::where('email', 'joao@example.com')->first()->senha);
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $response = $this->post('/cadastro', [
            'nome' => '', // Nome vazio - inválido
            'email' => 'email-invalido', // Email inválido
            'telefone' => '123', // Telefone muito curto
            'cpf/cnpj' => '', // CPF/CNPJ vazio
            'senha' => '', // Senha vazia
        ]);

        $response->assertSessionHasErrors(['nome', 'email', 'telefone', 'cpf/cnpj', 'senha']); // Verifica se há erros de validação
        $this->assertDatabaseCount('usuarios', 0); // Garante que nenhum usuário foi criado
    }
}