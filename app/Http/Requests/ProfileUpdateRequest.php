<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'  => ['required','string','max:255'],
            'email' => ['required','string','email','max:255', \Illuminate\Validation\Rule::unique('users','email')->ignore($this->user()->id)],
            'whatsapp' => ['nullable','string','max:30'],
            'city'     => ['nullable','string','max:100'],
            'address'  => ['nullable','string','max:500'],   // ← alamat baru
            'bio'      => ['nullable','string','max:1000'],
            'interests'=> ['nullable'],
            'avatar'   => ['nullable','image','max:2048'],
        ];
    }

}
