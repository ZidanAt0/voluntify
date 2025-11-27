<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan organizer ada
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@local.test'],
            ['name' => 'Yayasan Karya Bersama', 'password' => Hash::make('password')]
        );
        if (method_exists($organizer, 'assignRole')) {
            $organizer->syncRoles('organizer');
        }

        // Pastikan kategori tersedia
        $this->call(CategorySeeder::class);
        $cat = fn($slug) => optional(Category::where('slug', $slug)->first())->id;

        // Hapus event lama (aman untuk dev)
        Event::query()->delete();

        // Preset 10 event realistis
        $presets = [
            [
                'title' => 'Workshop Literasi Digital',
                'category_slug' => 'workshop',
                'city' => 'Jakarta',
                'address' => 'Perpustakaan Nasional, Jakarta Pusat',
                'in_days' => 10, 'start' => '09:00', 'dur_hours' => 6,
                'capacity' => 40, 'reg' => 1, 'location_type' => 'onsite',
                'excerpt' => 'Membantu masyarakat memahami penggunaan teknologi digital secara aman dan bertanggung jawab.',
                'desc' => "- Pengenalan keamanan akun & privasi\n- Praktik cek hoaks\n- Etika digital untuk pemula",
            ],
            [
                'title' => 'Aksi Bersih Pantai',
                'category_slug' => 'relawan',
                'city' => 'Bandar Lampung',
                'address' => 'Pantai Mutun, Pesawaran',
                'in_days' => 14, 'start' => '07:30', 'dur_hours' => 4,
                'capacity' => 120, 'reg' => 38, 'location_type' => 'onsite',
                'excerpt' => 'Gerakan kolaboratif membersihkan garis pantai dan edukasi pengelolaan sampah.',
                'desc' => "- Briefing & pembagian area\n- Pemetaan jenis sampah\n- Pengangkutan & dokumentasi",
            ],
            [
                'title' => 'Donor Darah Komunitas',
                'category_slug' => 'donor-darah',
                'city' => 'Bandung',
                'address' => 'Aula Kecamatan Coblong',
                'in_days' => 7, 'start' => '08:00', 'dur_hours' => 5,
                'capacity' => 100, 'reg' => 22, 'location_type' => 'onsite',
                'excerpt' => 'Aksi kemanusiaan bersama PMI, terbuka untuk umum dengan protokol kesehatan.',
                'desc' => "- Registrasi & skrining\n- Donor terjadwal\n- Snack & sertifikat partisipasi",
            ],
            [
                'title' => 'Seminar Keamanan Siber Dasar',
                'category_slug' => 'seminar',
                'city' => 'Online',
                'address' => 'Zoom Webinar',
                'in_days' => 5, 'start' => '19:00', 'dur_hours' => 2,
                'capacity' => null, 'reg' => 75, 'location_type' => 'online',
                'excerpt' => 'Pahami ancaman siber umum dan cara sederhana melindungi data pribadi.',
                'desc' => "- Phishing & social engineering\n- Password manager & MFA\n- Tanya jawab praktis",
            ],
            [
                'title' => 'Penanaman 1.000 Pohon',
                'category_slug' => 'relawan',
                'city' => 'Metro',
                'address' => 'Hutan Kota Metro',
                'in_days' => 20, 'start' => '08:00', 'dur_hours' => 5,
                'capacity' => 200, 'reg' => 94, 'location_type' => 'onsite',
                'excerpt' => 'Program penghijauan bersama komunitas dan dinas lingkungan setempat.',
                'desc' => "- Penanaman bibit\n- Pemasangan label pohon\n- Edukasi perawatan 3 bulan",
            ],
            [
                'title' => 'Pelatihan UI/UX Dasar',
                'category_slug' => 'pelatihan',
                'city' => 'Yogyakarta',
                'address' => 'Coworking Space Kotabaru',
                'in_days' => 12, 'start' => '09:00', 'dur_hours' => 6,
                'capacity' => 60, 'reg' => 18, 'location_type' => 'onsite',
                'excerpt' => 'Belajar dasar riset pengguna, wireframe, dan prototyping.',
                'desc' => "- Persona & user journey\n- Figma hands-on\n- Review desain peserta",
            ],
            [
                'title' => 'Workshop Public Speaking',
                'category_slug' => 'workshop',
                'city' => 'Jakarta',
                'address' => 'Ruang Serbaguna, Tebet',
                'in_days' => 25, 'start' => '13:00', 'dur_hours' => 3,
                'capacity' => 80, 'reg' => 27, 'location_type' => 'onsite',
                'excerpt' => 'Latihan presentasi dan storytelling yang efektif.',
                'desc' => "- Struktur presentasi\n- Latihan suara & gestur\n- Simulasi panggung kecil",
            ],
            [
                'title' => 'Kopdar Komunitas Laravel',
                'category_slug' => 'kopdar',
                'city' => 'Bandung',
                'address' => 'Kafe Ngoding, Dago',
                'in_days' => 9, 'start' => '18:30', 'dur_hours' => 2,
                'capacity' => 80, 'reg' => 41, 'location_type' => 'hybrid',
                'excerpt' => 'Berbagi praktik terbaik Laravel 10 & pengalaman deploy.',
                'desc' => "- Lightning talk\n- Sesi Q&A\n- Hiring corner",
            ],
            [
                'title' => 'CV Clinic & Bimbingan Karier',
                'category_slug' => 'seminar',
                'city' => 'Surabaya',
                'address' => 'Perpustakaan Daerah, Genteng',
                'in_days' => 16, 'start' => '10:00', 'dur_hours' => 3,
                'capacity' => 70, 'reg' => 29, 'location_type' => 'onsite',
                'excerpt' => 'Optimasi CV, LinkedIn, dan simulasi interview.',
                'desc' => "- Review CV peserta\n- LinkedIn optimization\n- Simulasi interview singkat",
            ],
            [
                'title' => 'Data Science untuk Pemula',
                'category_slug' => 'webinar',
                'city' => 'Online',
                'address' => 'Google Meet',
                'in_days' => 3, 'start' => '19:30', 'dur_hours' => 2,
                'capacity' => 300, 'reg' => 120, 'location_type' => 'online',
                'excerpt' => 'Pengantar analisis data, Python, dan visualisasi.',
                'desc' => "- Dasar Python & pandas\n- Visualisasi cepat\n- Demo notebook",
            ],
            [
                // Satu event yang sudah lewat → akan tampil "Closed"
                'title' => 'Bakti Sosial Panti Werdha',
                'category_slug' => 'relawan',
                'city' => 'Jakarta',
                'address' => 'Panti Werdha Cempaka Putih',
                'in_days' => -5, 'start' => '09:00', 'dur_hours' => 4,
                'capacity' => 50, 'reg' => 50, 'location_type' => 'onsite',
                'excerpt' => 'Kunjungan dan penyerahan paket kebutuhan harian.',
                'desc' => "- Pendampingan lansia\n- Hiburan musik\n- Donasi kebutuhan harian",
            ],
        ];

        foreach ($presets as $p) {
            $start = Carbon::now()->addDays($p['in_days'])->setTime(...explode(':', $p['start']));
            $end   = (clone $start)->addHours($p['dur_hours']);

            $slug = Str::slug($p['title']).'-'.$start->format('Ymd');

            Event::updateOrCreate(
                ['slug' => $slug],
                [
                    'organizer_id' => $organizer->id,
                    'category_id'  => $cat($p['category_slug']),
                    'title'        => $p['title'],
                    'excerpt'      => $p['excerpt'],
                    'description'  => $p['desc'],
                    'location_type'=> $p['location_type'],
                    'city'         => $p['city'] === 'Online' ? null : $p['city'],
                    'address'      => $p['address'],
                    'starts_at'    => $start,
                    'ends_at'      => $end,
                    'capacity'     => $p['capacity'],
                    'registration_count' => $p['reg'],
                    'status'       => ($end->isPast() ? 'closed' : 'published'),
                    'published_at' => Carbon::now(),
                    'banner_path'  => "https://picsum.photos/seed/{$slug}/800/450",
                ]
            );
        }
    }
}
