<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class JovensPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $jovensUser;

    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        if (! Role::where('name', 'jovens')->exists()) {
            Role::create(['name' => 'jovens', 'guard_name' => 'web']);
        }

        $this->jovensUser = User::factory()->create();
        $this->jovensUser->assignRole('jovens');

        $this->regularUser = User::factory()->create();
    }

    public function test_jovens_panel_routes_are_protected(): void
    {
        $response = $this->get(route('jovens.dashboard'));
        $response->assertRedirect(route('login'));

        $this->actingAs($this->regularUser);
        $response = $this->get(route('jovens.dashboard'));
        $response->assertStatus(403);
    }

    public function test_jovens_user_can_access_dashboard(): void
    {
        $this->actingAs($this->jovensUser);
        $response = $this->get(route('jovens.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('paineljovens::dashboard');
    }

    public function test_jovens_user_can_access_notificacoes_index(): void
    {
        if (! Route::has('jovens.notificacoes.index')) {
            $this->markTestSkipped('Módulo Notificacoes inativo ou rotas não registadas.');
        }

        $this->actingAs($this->jovensUser);
        $response = $this->get(route('jovens.notificacoes.index'));
        $response->assertStatus(200);
    }

    public function test_jovens_user_can_access_profile(): void
    {
        $this->actingAs($this->jovensUser);
        $response = $this->get(route('jovens.profile.index'));
        $response->assertStatus(200);
    }
}
