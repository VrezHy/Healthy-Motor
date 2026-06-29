<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_login_pemilik_bengkel_berhasil()
    {


        $response = $this->post('/login', [
            'username' => 'pemilik bengkel',
            'password' => 'DM5SPM'
        ]);

        $response->assertRedirect('/admin/kerusakan');
        $response->assertSessionHas('username', 'pemilik bengkel');
    }

    #[Test]
    public function test_login_admin_berhasil()
    {

        User::create([
            'name' => 'Admin',
            'username' => 'admin1.admin',
            'password' => Hash::make('A123456.'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'username' => 'admin1.admin',
            'password' => 'A123456.'
        ]);

        $response->assertRedirect('/admin/kerusakan');
        $this->assertAuthenticated();
    }

    #[Test]
    public function test_login_mekanik_berhasil()
    {

        User::create([
            'name' => 'Mekanik',
            'username' => 'mekanik1.mekanik',
            'password' => Hash::make('M123456.'),
            'role' => 'mekanik'
        ]);

        $response = $this->post('/login', [
            'username' => 'mekanik1.mekanik',
            'password' => 'M123456.'
        ]);

        $response->assertRedirect('/mekanik/dashboard');
        $this->assertAuthenticated();
    }

    #[Test]
    public function test_login_gagal()
    {

        $response = $this->post('/login', [
            'username' => 'salah',
            'password' => 'salahpss'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    #[Test]
    public function test_role_tidak_dikenali()
    {

        User::create([
            'name' => 'aneh',
            'username' => 'useraneh',
            'password' => Hash::make('An123456.'),
            'role' => 'tidak_valid'
        ]);

        $response = $this->post('/login', [
            'username' => 'useraneh',
            'password' => 'An123456.'
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    #[Test]
    public function test_validasi_kosong()
    {
        $response = $this->post('/login', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username', 'password']);
        $this->assertGuest();
    }
}
