<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\AdmissionNote;
use App\Models\PreAuthorization;
use App\Models\PreAuthorizationNote;
use App\Models\Provider;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdmissionAndPreauthAdminNotesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_add_pre_authorization_note_and_user_sees_it_on_show(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_test',
            'name' => 'Admin Tester',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $regular = User::query()->create([
            'userid' => 'user_test',
            'name' => 'Regular User',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);
        $serviceType = ServiceType::query()->create(['name' => 'Inpatient']);
        $provider = Provider::factory()->create();
        $preauth = PreAuthorization::query()->create([
            'service_type_id' => $serviceType->id,
            'provider_id' => $provider->id,
            'hn' => 'HN12345',
            'patient_name' => 'Test Patient',
        ]);

        $this->actingAs($admin)->post(route('preauth.notes.store', $preauth), [
            'note' => 'Follow up with insurer.',
        ])->assertRedirect(route('preauth.show', $preauth));

        $this->assertDatabaseHas('pre_authorization_notes', [
            'pre_authorization_id' => $preauth->id,
            'note' => 'Follow up with insurer.',
            'created_by' => 'Admin Tester',
        ]);

        $response = $this->actingAs($regular)->get(route('preauth.show', $preauth));
        $response->assertOk();
        $response->assertSee('Follow up with insurer.', false);
        $response->assertSee('Admin Tester', false);
    }

    public function test_non_admin_cannot_store_pre_authorization_note(): void
    {
        $regular = User::query()->create([
            'userid' => 'user_test2',
            'name' => 'Regular User Two',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);
        $serviceType = ServiceType::query()->create(['name' => 'OPD']);
        $provider = Provider::factory()->create();
        $preauth = PreAuthorization::query()->create([
            'service_type_id' => $serviceType->id,
            'provider_id' => $provider->id,
            'hn' => 'HN999',
        ]);

        $this->actingAs($regular)->post(route('preauth.notes.store', $preauth), [
            'note' => 'Should not save',
        ])->assertForbidden();

        $this->assertSame(0, PreAuthorizationNote::query()->where('pre_authorization_id', $preauth->id)->count());
    }

    public function test_admin_can_add_admission_note_and_user_sees_it_on_show(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_adm',
            'name' => 'Admission Admin',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $regular = User::query()->create([
            'userid' => 'user_adm',
            'name' => 'Admission Viewer',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);
        $admission = Admission::query()->create([
            'hn' => 'HN777',
            'name' => 'Admitted Patient',
        ]);

        $this->actingAs($admin)->post(route('admissions.notes.store', $admission), [
            'note' => 'GOP pending scan.',
        ])->assertRedirect(route('admissions.show', $admission));

        $this->assertDatabaseHas('admission_notes', [
            'admission_id' => $admission->id,
            'note' => 'GOP pending scan.',
            'created_by' => 'Admission Admin',
        ]);

        $response = $this->actingAs($regular)->get(route('admissions.show', $admission));
        $response->assertOk();
        $response->assertSee('GOP pending scan.', false);
    }

    public function test_non_admin_cannot_store_admission_note(): void
    {
        $regular = User::query()->create([
            'userid' => 'user_adm2',
            'name' => 'No Admin',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);
        $admission = Admission::query()->create(['hn' => 'HN888']);

        $this->actingAs($regular)->post(route('admissions.notes.store', $admission), [
            'note' => 'Blocked',
        ])->assertForbidden();

        $this->assertSame(0, AdmissionNote::query()->where('admission_id', $admission->id)->count());
    }

    public function test_admin_soft_deletes_pre_authorization_note(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_del_pa',
            'name' => 'Admin Delete PA',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $serviceType = ServiceType::query()->create(['name' => 'Surgery']);
        $provider = Provider::factory()->create();
        $preauth = PreAuthorization::query()->create([
            'service_type_id' => $serviceType->id,
            'provider_id' => $provider->id,
            'hn' => 'HN-DEL-PA',
        ]);
        $note = $preauth->notes()->create([
            'note' => 'To be soft deleted',
            'created_by' => 'Admin Delete PA',
        ]);

        $this->actingAs($admin)->post(route('preauth.notes.destroy', [$preauth, $note]))
            ->assertRedirect(route('preauth.show', $preauth));

        $this->assertSoftDeleted($note);
        $this->actingAs($admin)->get(route('preauth.show', $preauth))
            ->assertOk()
            ->assertDontSee('To be soft deleted', false);
    }

    public function test_non_admin_cannot_destroy_pre_authorization_note(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_own',
            'name' => 'Note Owner',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $regular = User::query()->create([
            'userid' => 'user_del_denied',
            'name' => 'No Delete',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'user',
        ]);
        $serviceType = ServiceType::query()->create(['name' => 'Lab']);
        $provider = Provider::factory()->create();
        $preauth = PreAuthorization::query()->create([
            'service_type_id' => $serviceType->id,
            'provider_id' => $provider->id,
            'hn' => 'HN-403-PA',
        ]);
        $note = $preauth->notes()->create([
            'note' => 'Protected note',
            'created_by' => 'Note Owner',
        ]);

        $this->actingAs($regular)->post(route('preauth.notes.destroy', [$preauth, $note]))
            ->assertForbidden();

        $this->assertDatabaseHas('pre_authorization_notes', [
            'id' => $note->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_soft_deletes_admission_note(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_del_adm',
            'name' => 'Admin Delete Adm',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $admission = Admission::query()->create(['hn' => 'HN-DEL-ADM']);
        $note = $admission->notes()->create([
            'note' => 'Admission note delete me',
            'created_by' => 'Admin Delete Adm',
        ]);

        $this->actingAs($admin)->post(route('admissions.notes.destroy', [$admission, $note]))
            ->assertRedirect(route('admissions.show', $admission));

        $this->assertSoftDeleted($note);
    }

    public function test_destroy_admission_note_returns_404_when_note_belongs_to_other_admission(): void
    {
        $admin = User::query()->create([
            'userid' => 'admin_404',
            'name' => 'Admin 404',
            'position' => 'Staff',
            'department' => 'IT',
            'division' => 'HQ',
            'role' => 'admin',
        ]);
        $admissionA = Admission::query()->create(['hn' => 'HN-A']);
        $admissionB = Admission::query()->create(['hn' => 'HN-B']);
        $noteOnA = $admissionA->notes()->create([
            'note' => 'On A only',
            'created_by' => 'Admin 404',
        ]);

        $this->actingAs($admin)->post(route('admissions.notes.destroy', [$admissionB, $noteOnA]))
            ->assertNotFound();

        $this->assertDatabaseHas('admission_notes', [
            'id' => $noteOnA->id,
            'deleted_at' => null,
        ]);
    }
}
