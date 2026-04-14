<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_pemilik_bengkel_berhasil()
    {
        $response = $this->post('/login', [
            'username' => 'pemilik bengkel',
            'password' => 'DM5SPM'
        ]);

        $response->assertRedirect(route('dashboard.admin'));

        $this->assertEquals(session('username'), 'pemilik bengkel');
    }


    public function test_login_admin_berhasil()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('A123456.'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'admin1',
            'password' => 'A123456.'
        ]);

        $response->assertRedirect(route('dashboard.admin'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_mekanik_berhasil()
    {
        $user = User::create([
            'name' => 'Mekanik',
            'username' => 'mekanik1',
            'password' => Hash::make('M123456.'),
            'role' => 'mekanik'
        ]);

        $response = $this->post('/login', [
            'username' => 'mekanik1',
            'password' => 'M123456.'
        ]);

        $response->assertRedirect(route('dashboard.mekanik'));
    }

    public function test_login_gagal()
    {
        $response = $this->post('/login', [
            'username' => 'salah',
            'password' => 'salahpss'
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_role_tidak_dikenali()
    {
        $user = User::create([
            'name' => 'aneh',
            'username' => 'useraneh',
            'password' => Hash::make('An123456.'),
            'role' => 'tidak_valid'
        ]);

        $response = $this->post('/login', [
            'username' => 'useraneh',
            'password' => 'An123456.'
        ]);

        $response->assertSessionHasErrors('username');
    }

    public function test_validasi_kosong()
    {
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors(['username', 'password']);
    }
}
