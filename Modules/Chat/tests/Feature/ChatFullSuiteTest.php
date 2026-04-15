<?php

namespace Modules\Chat\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Chat\App\Models\ChatConfig;
use Modules\Chat\App\Models\ChatSession;
use Modules\Chat\App\Models\ChatMessage;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;

class ChatFullSuiteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('module:enable', ['module' => 'Chat']);

        // Configurar Broadcasting para log (evita erro Pusher)
        config(['broadcasting.default' => 'log']);

        // Mockar configurações para chat aberto 24/7
        ChatConfig::set('chat_enabled', 'true');
        ChatConfig::set('public_chat_enabled', 'true');

        $hours = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $hours[$day] = ['enabled' => true, 'start' => '00:00', 'end' => '23:59'];
        }
        ChatConfig::set('opening_hours', json_encode($hours));

        Event::fake([
            \Modules\Chat\App\Events\UserTyping::class,
            \Modules\Chat\App\Events\ChatSessionUpdated::class,
        ]);
    }

    #[Test]
    public function public_chat_status_returns_available()
    {
        $response = $this->getJson(route('chat.status'));

        $response->assertStatus(200)
                 ->assertJson([
                     'available' => true,
                 ]);
    }

    #[Test]
    public function cannot_start_chat_with_invalid_cpf()
    {
        $response = $this->postJson(route('chat.start'), [
            'name' => 'Teste User',
            'cpf' => '12345678900', // CPF Inválido
        ]);

        $response->assertStatus(422)
                 ->assertJsonPath('success', false);
    }

    #[Test]
    public function can_start_chat_with_valid_cpf()
    {
        $cpf = '12345678909'; // CPF válido calculado

        $response = $this->postJson(route('chat.start'), [
            'name' => 'Visitante Teste',
            'cpf' => $cpf,
            'email' => 'teste@example.com'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure(['session_id', 'session']);

        $this->assertDatabaseHas('chat_sessions', [
            'visitor_cpf' => $cpf,
            'status' => 'waiting'
        ]);
    }

    #[Test]
    public function cannot_start_duplicate_session()
    {
        $cpf = '12345678909';

        // Cleanup previous sessions
        ChatSession::where('visitor_cpf', $cpf)->forceDelete();

        // Primeira sessão
        $this->postJson(route('chat.start'), [
            'name' => 'Visitante 1',
            'cpf' => $cpf,
        ])->assertStatus(200);

        // Tentativa duplicada
        $response = $this->postJson(route('chat.start'), [
            'name' => 'Visitante 1',
            'cpf' => $cpf,
        ]);

        $response->assertStatus(409) // Conflict
                 ->assertJsonPath('success', false);
    }

    #[Test]
    public function visitor_can_send_message()
    {
        $cpf = '12345678909';
        ChatSession::where('visitor_cpf', $cpf)->forceDelete(); // Cleanup

        $startResponse = $this->postJson(route('chat.start'), [
            'name' => 'Visitante msg',
            'cpf' => $cpf,
        ]);

        $startResponse->assertStatus(200);
        $sessionId = $startResponse->json('session_id');

        $response = $this->postJson(route('chat.session.message', $sessionId), [
            'message' => 'Olá, preciso de ajuda!'
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('chat_messages', [
            'message' => 'Olá, preciso de ajuda!',
            'sender_type' => 'visitor'
        ]);
    }

    #[Test]
    public function can_retrieve_session_and_history()
    {
        $cpf = '11223344517'; // Outro CPF valido
        ChatSession::where('visitor_cpf', $cpf)->forceDelete();

        $startResponse = $this->postJson(route('chat.start'), [
            'name' => 'Visitante Histórico',
            'cpf' => $cpf,
        ]);

        $startResponse->assertStatus(200);
        $sessionId = $startResponse->json('session_id');

        // Enviar mensagem
        $this->postJson(route('chat.session.message', $sessionId), ['message' => 'Minha dúvida']);

        // Recuperar sessão
        $response = $this->getJson(route('chat.session.get', $sessionId));

        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonPath('session.session_id', $sessionId);

        $this->assertGreaterThanOrEqual(1, count($response->json('messages')));
    }

    #[Test]
    public function cannot_send_message_to_closed_session()
    {
        $cpf = '11223344517'; // Reusando segundo CPF, mas limpando
        ChatSession::where('visitor_cpf', $cpf)->forceDelete();

        $startResponse = $this->postJson(route('chat.start'), [
            'name' => 'Visitante Closed',
            'cpf' => $cpf,
        ]);

        $startResponse->assertStatus(200);
        $sessionId = $startResponse->json('session_id');

        // Close session manually in DB
        ChatSession::where('session_id', $sessionId)->update(['status' => 'closed']);

        $response = $this->postJson(route('chat.session.message', $sessionId), [
            'message' => 'Tentar enviar'
        ]);

        $response->assertStatus(403)
                 ->assertJsonPath('success', false);
    }
}
