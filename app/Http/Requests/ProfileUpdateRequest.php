<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$this->user()->id],
            'sign' => ['nullable', 'image', 'mimes:png,jpeg', 'max:2048'], // Explicit MIME types
            'foto' => ['nullable', 'image', 'mimes:png,jpeg', 'max:2048'], // Explicit MIME types
        ];
    }

    public function messages(): array
    {
        return [
            'sign.image' => 'The signature must be an image file.',
            'sign.mimes' => 'The signature must be a PNG or JPEG file.',
            'sign.max' => 'The signature file size must not exceed 2MB.',
            'foto.image' => 'The profile photo must be an image file.',
            'foto.mimes' => 'The profile photo must be a PNG or JPEG file.',
            'foto.max' => 'The profile photo file size must not exceed 2MB.',
        ];
    }
}