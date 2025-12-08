@extends('layouts.dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-6">
        Peserta Event: {{ $event->title }}
    </h1>

    <div class="bg-white rounded-xl shadow ring-1 ring-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-center">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($registrations as $r)
                    <tr>
                        <td class="p-3">{{ $r->user->name }}</td>
                        <td class="p-3">{{ $r->user->email }}</td>

                        <td class="p-3 text-center">
                            <span
                                class="px-3 py-1 rounded-full text-xs
                        @if ($r->status == 'applied') bg-yellow-100 text-yellow-700
                        @elseif($r->status == 'approved') bg-green-100 text-green-700
                        @elseif($r->status == 'rejected') bg-red-100 text-red-700 @endif">
                                {{ ucfirst($r->status) }}
                            </span>
                        </td>

                        <td class="p-3 text-center flex gap-2 justify-center">

                            <button type="button"
                                    class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 js-view-answers"
                                    data-name="{{ $r->user->name }}"
                                    data-status="{{ ucfirst($r->status) }}"
                                    data-answers='@json($r->answers ?? [])'>
                                Jawaban Formulir
                            </button>

                            @if ($r->status == 'applied')
                                <form method="POST" action="{{ route('organizer.registrations.approve', $r->id) }}">
                                    @csrf
                                    <button class="px-3 py-1 bg-green-600 text-white rounded text-xs">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('organizer.registrations.reject', $r->id) }}">
                                    @csrf
                                    <button class="px-3 py-1 bg-red-600 text-white rounded text-xs">
                                        Reject
                                    </button>
                                </form>
                            @else
                                
                            @endif
                            @if ($r->status === 'checked_in')
                                <form method="POST" action="{{ route('organizer.registrations.complete', $r) }}">
                                    @csrf
                                    <button class="px-3 py-1 bg-emerald-600 text-white rounded hover:bg-emerald-700">
                                        Selesaikan & Luluskan
                                    </button>
                                </form>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            Belum ada pendaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('organizer.events.index') }}" class="px-5 py-2 border rounded-xl">
            Kembali ke Event
        </a>
    </div>

    {{-- Modal Jawaban Formulir --}}
    <div id="answersModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b">
                <div>
                    <div class="text-sm text-gray-500">Jawaban Formulir</div>
                    <div id="answersModalName" class="font-semibold text-gray-800"></div>
                    <div id="answersModalStatus" class="text-xs text-gray-500"></div>
                </div>
                <button id="answersModalClose" class="text-gray-500 hover:text-gray-800">✕</button>
            </div>
            <div id="answersModalBody" class="px-5 py-4 space-y-2 max-h-96 overflow-y-auto text-sm text-gray-800">
                {{-- dynamic --}}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('answersModal');
        const modalName = document.getElementById('answersModalName');
        const modalStatus = document.getElementById('answersModalStatus');
        const modalBody = document.getElementById('answersModalBody');
        const closeBtn = document.getElementById('answersModalClose');

        const openModal = (name, status, answers) => {
            modalName.textContent = name || 'Peserta';
            modalStatus.textContent = status ? `Status: ${status}` : '';
            modalBody.innerHTML = '';

            if (!answers || Object.keys(answers).length === 0) {
                const empty = document.createElement('div');
                empty.className = 'text-gray-500';
                empty.textContent = 'Tidak ada jawaban formulir.';
                modalBody.appendChild(empty);
            } else {
                Object.entries(answers).forEach(([key, val]) => {
                    const wrap = document.createElement('div');
                    wrap.className = 'border border-gray-100 rounded-lg p-3';

                    const label = document.createElement('div');
                    label.className = 'text-xs uppercase tracking-wide text-gray-500 mb-1';
                    label.textContent = key;

                    const value = document.createElement('div');
                    value.className = 'text-sm text-gray-800 whitespace-pre-line';
                    value.textContent = Array.isArray(val) ? val.join(', ') : (val ?? '');

                    wrap.appendChild(label);
                    wrap.appendChild(value);
                    modalBody.appendChild(wrap);
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

        document.querySelectorAll('.js-view-answers').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.name || '';
                const status = btn.dataset.status || '';
                let answers = {};
                try {
                    answers = JSON.parse(btn.dataset.answers || '{}');
                } catch (_) {
                    answers = {};
                }
                openModal(name, status, answers);
            });
        });

        closeBtn?.addEventListener('click', closeModal);
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    });
    </script>
    @endpush
@endsection
