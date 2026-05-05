<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreauthDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_preauth_dashboard(): void
    {
        $user = User::query()->create([
            'userid' => 'dash_user',
            'name' => 'Dashboard User',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);

        ServiceType::query()->create(['name' => 'IPD']);
        Provider::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard.preauth', ['month' => 5, 'year' => 2026]))
            ->assertOk()
            ->assertSee('Pre-authorization', false);
    }

    public function test_authenticated_user_can_download_preauth_power_bi_csv_export(): void
    {
        $user = User::query()->create([
            'userid' => 'export_user',
            'name' => 'Export User',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);

        ServiceType::query()->create(['name' => 'IPD']);
        Provider::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.preauth.export', ['month' => 4, 'year' => 2026]));

        $response->assertOk();
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertStringContainsString('attachment', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('pre_authorization_id', $response->streamedContent());
    }
}
