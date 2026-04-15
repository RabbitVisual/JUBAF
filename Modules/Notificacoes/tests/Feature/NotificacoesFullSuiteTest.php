<?php

namespace Modules\Notificacoes\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Notificacoes\App\Models\Notificacao;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Framework\Attributes\Test;

class NotificacoesFullSuiteTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Bypass Gate
        Gate::before(function () {
            return true;
        });

        // Mock Broadcasting to avoid Pusher error
        config(['broadcasting.default' => 'log']);

        $this->artisan('module:enable', ['module' => 'Notificacoes']);
    }

    #[Test]
    public function database_has_notifications_table()
    {
        $this->assertTrue(DB::getSchemaBuilder()->hasTable('notifications'));
        $columns = DB::getSchemaBuilder()->getColumnListing('notifications');
        $this->assertContains('type', $columns);
        $this->assertContains('is_read', $columns);
        $this->assertContains('module_source', $columns);
        $this->assertContains('panel', $columns);
    }

    #[Test]
    public function can_create_notification_via_static_method()
    {
        $user = User::factory()->create();

        $notificacao = Notificacao::createNotification(
            type: 'info',
            title: 'Teste Título',
            message: 'Teste Mensagem',
            userId: $user->id,
            data: ['key' => 'value'],
            moduleSource: 'Notificações',
            panel: 'admin'
        );

        $this->assertDatabaseHas('notifications', [
            'type' => 'info',
            'title' => 'Teste Título',
            'user_id' => $user->id,
            'is_read' => false,
            'panel' => 'admin'
        ]);

        $this->assertEquals(['key' => 'value'], $notificacao->data);
    }

    #[Test]
    public function prevents_duplicate_notification_within_10_seconds()
    {
        $user = User::factory()->create();

        $notificacao1 = Notificacao::createNotification(
            'warning',
            'Duplicada',
            'Msg',
            $user->id
        );

        $notificacao2 = Notificacao::createNotification(
            'warning',
            'Duplicada',
            'Msg',
            $user->id
        );

        // Deve retornar a mesma instância (mesmo ID)
        $this->assertEquals($notificacao1->id, $notificacao2->id);
        $this->assertEquals(1, Notificacao::where('title', 'Duplicada')->count());

        // Simular passagem de tempo (> 10s)
        $this->travel(11)->seconds();

        $notificacao3 = Notificacao::createNotification(
            'warning',
            'Duplicada',
            'Msg',
            $user->id
        );

        $this->assertNotEquals($notificacao1->id, $notificacao3->id);
        $this->assertEquals(2, Notificacao::where('title', 'Duplicada')->count());
    }

    #[Test]
    public function can_mark_notification_as_read()
    {
        $user = User::factory()->create();
        $notificacao = Notificacao::createNotification('info', 'Lida', 'Msg', $user->id);

        $this->assertFalse($notificacao->is_read);
        $this->assertNull($notificacao->read_at);

        $notificacao->markAsRead();

        $this->assertTrue($notificacao->fresh()->is_read);
        $this->assertNotNull($notificacao->fresh()->read_at);
    }

    #[Test]
    public function scopes_work_correctly()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // User 1 nots
        Notificacao::createNotification('info', 'U1', 'M', $user1->id);

        // User 2 nots
        Notificacao::createNotification('info', 'U2', 'M', $user2->id);

        // Global not (no user_id)
        Notificacao::createNotification('system', 'Global', 'M');

        // Test forUser scope
        $this->assertEquals(2, Notificacao::forUser($user1->id)->count()); // 1 pessoal + 1 global
        $this->assertEquals(2, Notificacao::forUser($user2->id)->count()); // 1 pessoal + 1 global

        // Test unread scope
        $n = Notificacao::createNotification('info', 'Lida', 'M', $user1->id);
        $n->markAsRead();

        $this->assertEquals(1, Notificacao::where('user_id', $user1->id)->unread()->count());

        // Test panel scope
        Notificacao::createNotification(type: 'info', title: 'Admin Not', message: 'M', userId: $user1->id, panel: 'admin');
        Notificacao::createNotification(type: 'info', title: 'Diretoria Not', message: 'M', userId: $user1->id, panel: 'diretoria');

        $this->assertEquals(1, Notificacao::forPanel('admin')->where('title', 'Admin Not')->count());
        $this->assertEquals(1, Notificacao::forPanel('diretoria')->where('title', 'Diretoria Not')->count());
    }

    #[Test]
    public function api_lists_notifications_with_filters()
    {
        $user = User::factory()->create();

        Notificacao::createNotification('success', 'Success', 'M', $user->id);
        Notificacao::createNotification('error', 'Error', 'M', $user->id);
        Notificacao::createNotification(type: 'info', title: 'Panel Admin', message: 'M', userId: $user->id, panel: 'admin');
        Notificacao::createNotification(type: 'info', title: 'Panel Diretoria', message: 'M', userId: $user->id, panel: 'diretoria');

        // Test type filter
        $response = $this->actingAs($user)->get(route('notificacoes.index', ['type' => 'success']));
        $response->assertStatus(200);
        $response->assertSee('Success');
        $response->assertDontSee('Error');

        // Test panel filter
        $responseAdmin = $this->actingAs($user)->get(route('notificacoes.index', ['panel' => 'admin']));
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertSee('Panel Admin');
        $responseAdmin->assertDontSee('Panel Diretoria');

        $responseDiretoria = $this->actingAs($user)->get(route('notificacoes.index', ['panel' => 'diretoria']));
        $responseDiretoria->assertStatus(200);
        $responseDiretoria->assertSee('Panel Diretoria');
        $responseDiretoria->assertDontSee('Panel Admin');
    }

    #[Test]
    public function api_can_mark_all_as_read()
    {
        $user = User::factory()->create();

        Notificacao::createNotification('info', '1', 'M', $user->id);
        Notificacao::createNotification('info', '2', 'M', $user->id);

        $this->assertEquals(2, Notificacao::where('user_id', $user->id)->unread()->count());

        $response = $this->actingAs($user)->post(route('notificacoes.read-all'));

        $response->assertRedirect();
        $this->assertEquals(0, Notificacao::where('user_id', $user->id)->unread()->count());
    }
}
