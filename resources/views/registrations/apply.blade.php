@extends('layouts.dashboard')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-4 py-0">
        <div class="mb-6">
            <a href="{{ route('events.show', $event->slug) }}" class="text-sm text-indigo-700 hover:underline">← Kembali</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold">Form Pendaftaran Volunteer</h1>
                <p class="text-gray-600 mt-1">{{ $event->title }}</p>
            </div>

            <form method="POST" action="{{ route('registrations.apply', $event) }}" class="p-6 grid gap-6">
                @csrf

                {{-- Info dasar (prefilled) --}}
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Nama</label>
                        <input value="{{ $user->name }}" disabled
                            class="mt-1 w-full rounded-xl border-gray-200 bg-gray-50">
                        <p class="text-xs text-gray-500 mt-1">Ubah di halaman Profil jika perlu.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Email</label>
                        <input value="{{ $user->email }}" disabled
                            class="mt-1 w-full rounded-xl border-gray-200 bg-gray-50">
                    </div>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">WhatsApp <span class="text-red-600">*</span></label>
                        <input name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}" required inputmode="numeric"
                            pattern="\d{9,15}" class="mt-1 w-full rounded-xl border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">Masukkan 9–15 digit angka tanpa spasi/tanda.</p>
                        @error('whatsapp')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Kota/Domisili <span class="text-red-600">*</span></label>
                        <input name="city" value="{{ old('city', $user->city) }}"
                            class="mt-1 w-full rounded-xl border-gray-300">
                        @error('city')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Pertanyaan inti --}}
                <div>
                    <label class="block text-sm text-gray-700">Motivasi mengikuti event ini <span
                            class="text-red-600">*</span></label>
                    <textarea name="motivation" rows="4" class="mt-1 w-full rounded-xl border-gray-300" required>{{ old('motivation') }}</textarea>
                    @error('motivation')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Pengalaman relevan (opsional)</label>
                    <textarea name="experience" rows="3" class="mt-1 w-full rounded-xl border-gray-300">{{ old('experience') }}</textarea>
                    @error('experience')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm text-gray-700">Keahlian (pilih yang sesuai, opsional)</label>
                    @php
                        $skillOptions = [
                            'Logistik',
                            'Public Speaking',
                            'Desain Grafis',
                            'Fotografi/Videografi',
                            'Administrasi',
                            'Pertolongan Pertama',
                        ];
                        $oldSkills = old('skills', []);
                    @endphp
                    <div class="mt-2 grid sm:grid-cols-2 gap-2">
                        @foreach ($skillOptions as $skill)
                            <label class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-3 py-2">
                                <input type="checkbox" name="skills[]" value="{{ $skill }}"
                                    @checked(in_array($skill, $oldSkills))>
                                <span>{{ $skill }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('skills')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Kontak Darurat <span
                                class="text-red-600">*</span></label>
                        <input name="emergency_name" placeholder="Nama" value="{{ old('emergency_name') }}" required
                            class="mt-1 w-full rounded-xl border-gray-300">
                        @error('emergency_name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">No. Kontak Darurat <span
                                class="text-red-600">*</span></label>
                        <input name="emergency_phone" placeholder="08xxxxxxxxxx" value="{{ old('emergency_phone') }}"
                            required inputmode="numeric" pattern="\d{9,15}" class="mt-1 w-full rounded-xl border-gray-300">
                        <p class="text-xs text-gray-500 mt-1">9–15 digit angka. Tidak boleh sama dengan WhatsApp.</p>
                        @error('emergency_phone')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree" value="1" class="mt-1" {{ old('agree') ? 'checked' : '' }}
                        required>
                    <span class="text-sm text-gray-700">
                        Saya menyatakan data yang saya isi benar dan bersedia mengikuti ketentuan panitia.
                    </span>
                </label>
                @error('agree')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="pt-2">
                    <button class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
