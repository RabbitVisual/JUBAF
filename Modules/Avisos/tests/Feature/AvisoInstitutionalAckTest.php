<?php

namespace Modules\Avisos\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Avisos\App\Models\Aviso;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AvisoInstitutionalAckTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::findOrCreate('lider', 'web');
    }

    #[Test]
    public function lider_can_post_ciente_on_institutional_aviso(): void
    {
        $lider = User::factory()->create(['active' => true]);
        $lider->assignRole('lider');

        $aviso = Aviso::create([
            'titulo' => 'Comunicado',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => true,
            'modo_quadro' => true,
            'classificacao' => 'informativo',
            'target_role' => 'lider',
            'user_id' => $lider->id,
        ]);

        $response = $this->actingAs($lider)->post(route('lideres.avisos.ciente', $aviso));

        $response->assertRedirect();
        $this->assertDatabaseHas('aviso_user_read', [
            'aviso_id' => $aviso->id,
            'user_id' => $lider->id,
        ]);
    }
}
