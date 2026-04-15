<?php

namespace Modules\Avisos\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Modules\Avisos\App\Models\Aviso;

class AvisosFullSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/admin/dashboard', fn () => 'dashboard')->name('admin.dashboard');

        Role::findOrCreate('super-admin', 'web');
        Role::findOrCreate('jovens', 'web');
    }

    protected function superAdminUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole('super-admin');

        return $user;
    }

    #[Test]
    public function super_admin_can_access_avisos_index(): void
    {
        $user = $this->superAdminUser();

        Aviso::create([
            'titulo' => 'Aviso de Teste',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('admin.avisos.index'));

        $response->assertStatus(200);
        $response->assertSee('Aviso de Teste');
    }

    #[Test]
    public function super_admin_can_store_new_aviso(): void
    {
        $user = $this->superAdminUser();

        $data = [
            'titulo' => 'Novo Aviso Importante',
            'descricao' => 'Descrição do aviso',
            'tipo' => 'danger',
            'posicao' => 'flutuante',
            'estilo' => 'modal',
            'ativo' => 1,
            'dismissivel' => 1,
        ];

        $response = $this->actingAs($user)->post(route('admin.avisos.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('avisos', [
            'titulo' => 'Novo Aviso Importante',
            'tipo' => 'danger',
        ]);
    }

    #[Test]
    public function super_admin_can_update_aviso(): void
    {
        $user = $this->superAdminUser();
        $aviso = Aviso::create([
            'titulo' => 'Aviso Original',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'user_id' => $user->id,
        ]);

        $data = [
            'titulo' => 'Aviso Atualizado',
            'tipo' => 'success',
            'posicao' => 'rodape',
            'estilo' => 'toast',
            'ativo' => 0,
        ];

        $response = $this->actingAs($user)->put(route('admin.avisos.update', $aviso->id), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('avisos', [
            'id' => $aviso->id,
            'titulo' => 'Aviso Atualizado',
            'ativo' => 0,
        ]);
    }

    #[Test]
    public function super_admin_can_destroy_aviso(): void
    {
        $user = $this->superAdminUser();
        $aviso = Aviso::create([
            'titulo' => 'Aviso para Deletar',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('admin.avisos.destroy', $aviso->id));

        $response->assertRedirect(route('admin.avisos.index'));
        $this->assertSoftDeleted('avisos', ['id' => $aviso->id]);
    }

    #[Test]
    public function guest_without_publish_role_cannot_store_aviso(): void
    {
        $user = User::factory()->create();
        $user->assignRole('jovens');

        $data = [
            'titulo' => 'Não deve gravar',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => 1,
        ];

        $this->actingAs($user)->post(route('admin.avisos.store'), $data)->assertForbidden();
    }

    #[Test]
    public function public_avisos_index_returns_ok(): void
    {
        Aviso::create([
            'titulo' => 'Público',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
        ]);

        $this->get(route('avisos.index'))->assertStatus(200)->assertSee('Público');
    }

    #[Test]
    public function public_api_returns_avisos_by_position(): void
    {
        Aviso::create([
            'titulo' => 'Aviso Topo',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
        ]);

        Aviso::create([
            'titulo' => 'Aviso Rodape',
            'tipo' => 'warning',
            'posicao' => 'rodape',
            'estilo' => 'banner',
            'ativo' => true,
        ]);

        $response = $this->get(route('avisos.api.posicao', 'topo'));

        $response->assertStatus(200);
        $response->assertJsonFragment(['titulo' => 'Aviso Topo']);
        $response->assertJsonMissing(['titulo' => 'Aviso Rodape']);
    }

    #[Test]
    public function feed_meta_returns_json(): void
    {
        Aviso::create([
            'titulo' => 'Meta',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
        ]);

        $this->get(route('avisos.api.feed-meta'))
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'updated_at']);
    }

    #[Test]
    public function api_can_record_view_and_click(): void
    {
        $aviso = Aviso::create([
            'titulo' => 'Aviso Métricas',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'visualizacoes' => 0,
            'cliques' => 0,
        ]);

        $this->post(route('avisos.api.visualizar', $aviso->id));
        $this->assertEquals(1, $aviso->fresh()->visualizacoes);

        $this->post(route('avisos.api.clique', $aviso->id));
        $this->assertEquals(1, $aviso->fresh()->cliques);
    }

    #[Test]
    public function aviso_respects_date_range(): void
    {
        $futuro = Aviso::create([
            'titulo' => 'Aviso Futuro',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'data_inicio' => now()->addDays(1),
        ]);

        $expirado = Aviso::create([
            'titulo' => 'Aviso Expirado',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'data_fim' => now()->subDays(1),
        ]);

        $valido = Aviso::create([
            'titulo' => 'Aviso Válido',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'data_inicio' => now()->subDays(1),
            'data_fim' => now()->addDays(1),
        ]);

        $ativos = Aviso::ativos()->get();

        $this->assertTrue($ativos->contains($valido));
        $this->assertFalse($ativos->contains($futuro));
        $this->assertFalse($ativos->contains($expirado));
    }
}
