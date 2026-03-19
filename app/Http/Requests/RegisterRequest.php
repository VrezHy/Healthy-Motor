<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{
    public function autorize(): bool{
        return true;
    }
    public function rules(): array{
        return[
            'name' => ['required', 'string', 'max: 255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/',
            ],
        ];
    }
    public function messages(): array
    {
        return [
             'username.unique'    => 'Username sudah digunakan.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.regex'     => 'Password harus mengandung huruf kapital, angka, dan karakter spesial (@$!%*?&).',
        ];
    }
}

