<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Governance\Models\Assembly;
use Modules\Governance\Models\Minute;
use Modules\Governance\Models\OfficialCommunication;
use Tests\TestCase;

class GovernanceTransparencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_transparency_index_is_public(): void
    {
        $this->get(route('public.transparency.index'))->assertOk();
    }

    public function test_unpublished_minute_is_not_public(): void
    {
        $user = User::factory()->create();
        $assembly = Assembly::query()->create([
            'type' => 'ordinaria',
            'title' => 'Assembleia teste',
            'scheduled_at' => now(),
            'created_by' => $user->id,
        ]);
        $minute = Minute::query()->create([
            'assembly_id' => $assembly->id,
            'slug' => 'ata-teste-rascunho',
            'body' => 'Conteúdo confidencial.',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $this->get(route('public.transparency.minute', $minute))->assertNotFound();
    }

    public function test_published_minute_is_visible(): void
    {
        $user = User::factory()->create();
        $assembly = Assembly::query()->create([
            'type' => 'ordinaria',
            'title' => 'Assembleia pública',
            'scheduled_at' => now(),
            'created_by' => $user->id,
        ]);
        $minute = Minute::query()->create([
            'assembly_id' => $assembly->id,
            'slug' => 'ata-teste-publicada',
            'body' => 'Texto visível a todos.',
            'status' => 'published',
            'published_at' => now(),
            'created_by' => $user->id,
        ]);

        $this->get(route('public.transparency.minute', $minute))
            ->assertOk()
            ->assertSee('Texto visível a todos.', false);
    }

    public function test_unpublished_communication_is_not_public(): void
    {
        $user = User::factory()->create();
        $comm = OfficialCommunication::query()->create([
            'title' => 'Rascunho',
            'slug' => 'com-draft',
            'body' => 'Interno',
            'is_published' => false,
            'created_by' => $user->id,
        ]);

        $this->get(route('public.transparency.communication', $comm))->assertNotFound();
    }
}
