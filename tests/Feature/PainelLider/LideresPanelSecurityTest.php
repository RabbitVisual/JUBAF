<?php

namespace Tests\Feature\PainelLider;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LideresPanelSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected User $liderUser;

    protected User $standardUser;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();

        Role::firstOrCreate(['name' => 'lider', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        $this->liderUser = User::factory()->create(['email' => 'lider'.rand(1, 9999).'@example.org']);
        $this->liderUser->assignRole('lider');

        $this->adminUser = User::factory()->create(['email' => 'admin'.rand(1, 9999).'@example.org']);
        $this->adminUser->assignRole('super-admin');

        $this->standardUser = User::factory()->create(['email' => 'user'.rand(1, 9999).'@example.org']);
    }

    #[Test]
    public function lider_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->liderUser)
            ->get(route('lideres.dashboard'));

        $response->assertStatus(200);
    }

    #[Test]
    public function lider_user_can_access_profile(): void
    {
        $response = $this->actingAs($this->liderUser)
            ->get(route('lideres.profile.index'));

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_cannot_access_lideres_panel_without_role(): void
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('lideres.dashboard'));

        $response->assertStatus(403);
    }

    #[Test]
    public function standard_user_cannot_access_lideres_panel(): void
    {
        $response = $this->actingAs($this->standardUser)
            ->get(route('lideres.dashboard'));

        $response->assertStatus(403);
    }

    #[Test]
    public function guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('lideres.dashboard'));

        $response->assertRedirect(route('login'));
    }
}
