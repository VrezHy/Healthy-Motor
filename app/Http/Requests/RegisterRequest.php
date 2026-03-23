<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }
    public function rules(): array{
        return[
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[~`!@#$%^&*-+=|\:;"</>?,.]/',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'      => 'Nama tidak boleh kosong.',
            'name.max'           => 'Nama maksimal 50 karakter.',
            'username.required'  => 'Username tidak boleh kosong.',
            'username.max'       => 'Username maksimal 30 karakter.',
            'username.unique'    => 'Username sudah digunakan.',
            'username.regex'     => 'Format username harus memiliki role nama.admin atau nama.mekanik.',
            'password.required'  => 'Password tidak boleh kosong.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.regex'     => 'Password harus mengandung huruf kapital, angka, dan karakter spesial (~`!@#$%^&*-+=|\:;"</>?,.).',
        ];
    }
}

