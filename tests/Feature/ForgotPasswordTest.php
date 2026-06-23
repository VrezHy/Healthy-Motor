<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_lupa_password_dapat_diakses()
    {
        $response = $this->get('/forgot-password');

        $response->assertOk();
        $response->assertViewIs('forgot_password');
    }

    public function test_reset_password_berhasil_dengan_recovery_code_valid()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('Password123.'),
            'role' => 'admin',
            'recovery_code' => 'RC-VALID-123',
        ]);

        $response = $this->post('/forgot-password', [
            'username' => 'admin1',
            'recovery_code' => 'RC-VALID-123',
            'password' => 'PasswordBaru123.',
            'password_confirmation' => 'PasswordBaru123.',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success', 'Password berhasil diubah.');

        $user->refresh();
        $this->assertTrue(Hash::check('PasswordBaru123.', $user->password));
    }

    public function test_reset_password_gagal_jika_recovery_code_salah()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('Password123.'),
            'role' => 'admin',
            'recovery_code' => 'RC-VALID-123',
        ]);

        $response = $this->post('/forgot-password', [
            'username' => 'admin1',
            'recovery_code' => 'RC-SALAH-123',
            'password' => 'PasswordBaru123.',
            'password_confirmation' => 'PasswordBaru123.',
        ]);

        $response->assertSessionHasErrors('recovery_code');

        $user->refresh();
        $this->assertTrue(Hash::check('Password123.', $user->password));
    }

    public function test_reset_password_gagal_jika_data_wajib_kosong()
    {
        $response = $this->post('/forgot-password', []);

        $response->assertSessionHasErrors([
            'username',
            'recovery_code',
            'password',
        ]);
    }

    public function test_reset_password_gagal_jika_password_kurang_dari_delapan_karakter()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('Password123.'),
            'role' => 'admin',
            'recovery_code' => 'RC-VALID-123',
        ]);

        $response = $this->post('/forgot-password', [
            'username' => 'admin1',
            'recovery_code' => 'RC-VALID-123',
            'password' => 'Pass1.',
            'password_confirmation' => 'Pass1.',
        ]);

        $response->assertSessionHasErrors('password');

        $user->refresh();
        $this->assertTrue(Hash::check('Password123.', $user->password));
    }

    public function test_reset_password_gagal_jika_konfirmasi_password_tidak_sama()
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('Password123.'),
            'role' => 'admin',
            'recovery_code' => 'RC-VALID-123',
        ]);

        $response = $this->post('/forgot-password', [
            'username' => 'admin1',
            'recovery_code' => 'RC-VALID-123',
            'password' => 'PasswordBaru123.',
            'password_confirmation' => 'PasswordBeda123.',
        ]);

        $response->assertSessionHasErrors('password');

        $user->refresh();
        $this->assertTrue(Hash::check('Password123.', $user->password));
    }
}