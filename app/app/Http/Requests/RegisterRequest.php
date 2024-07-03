<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email',
            "user_id" => "required|string",
            "avatar_url" => "required|string",
            "html_url" => "required|string",
            "access_token" => "required|string",
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'string' => 'O campo :attribute precisa ser um texto válido.',
            'password.confirmed' => 'As senhas não são correspondentes.',
            'unique' => 'O campo :attribute já está em uso.',
            'email' => 'O campo :attribute precisa ser um e-mail válido.',
        ];
    }
}
