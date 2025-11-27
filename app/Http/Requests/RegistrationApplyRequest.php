<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationApplyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // WA wajib angka 9–15 digit
            'whatsapp'   => ['required','regex:/^\d{9,15}$/'],

            // opsional
            'city'       => ['required','string','max:100'],

            // inti
            'motivation' => ['required','string','max:500'],
            'experience' => ['nullable','string','max:500'],
            'skills'     => ['nullable','array'],
            'skills.*'   => ['string','max:50'],

            // kontak darurat wajib dan beda dari WA, juga angka
            'emergency_name'  => ['required','string','max:100'],
            'emergency_phone' => ['required','regex:/^\d{9,15}$/','different:whatsapp'],

            // persetujuan
            'agree'      => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.regex'    => 'Nomor WhatsApp harus 9–15 digit angka.',
            'city.required' => 'Kota domisili wajib diisi.',
            'motivation.required' => 'Jelaskan motivasi Anda.',
            'emergency_name.required'  => 'Nama kontak darurat wajib diisi.',
            'emergency_phone.required' => 'No. kontak darurat wajib diisi.',
            'emergency_phone.regex'    => 'No. kontak darurat harus 9–15 digit angka.',
            'emergency_phone.different'=> 'No. kontak darurat tidak boleh sama dengan WhatsApp.',
            'agree.accepted'           => 'Anda harus menyetujui ketentuan.',
        ];
    }
}
