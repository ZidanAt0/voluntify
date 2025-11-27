<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        // Generator judul Indonesia sederhana
        $prefix = ['Aksi', 'Pelatihan', 'Seminar', 'Webinar', 'Workshop', 'Kopdar', 'Donor Darah', 'Penanaman', 'Bakti Sosial', 'Mentoring', 'Talkshow'];
        $subject = ['Bersih Pantai', 'UI/UX Dasar', 'Keamanan Siber', 'Data Science', 'Laravel Dasar', 'Karier & CV', 'Pengelolaan Sampah', 'Penanaman Pohon', 'Kesehatan Mental', 'Pendidikan Anak', 'Crypto & Risiko', 'Public Speaking'];

        $title = $this->faker->randomElement($prefix) . ' ' . $this->faker->randomElement($subject);
        $slug  = Str::slug($title) . '-' . Str::random(5);

        // Ringkasan & deskripsi dalam Bahasa Indonesia
        $excerpt = $this->faker->randomElement([
            'Kegiatan kolaboratif untuk masyarakat, terbuka bagi relawan pemula maupun berpengalaman.',
            'Belajar praktik langsung bersama mentor, kuota terbatas—daftar sekarang.',
            'Acara komunitas untuk berbagi pengetahuan dan memperluas jejaring.',
            'Program berdampak sosial dengan SOP jelas dan pendampingan panitia.',
        ]);

        $paragraphs = [
            'Kegiatan ini dirancang untuk memberikan pengalaman langsung sekaligus wawasan praktis. Peserta akan mendapatkan materi inti, sesi tanya jawab, dan panduan langkah demi langkah.',
            'Panitia menyiapkan peralatan dasar serta koordinasi lokasi. Mohon datang tepat waktu dan membawa perlengkapan pribadi seperlunya.',
            'Sertifikat partisipasi akan diberikan bagi peserta yang hadir dan menyelesaikan seluruh rangkaian kegiatan.',
        ];
        $description = implode("\n\n", $this->faker->shuffle($paragraphs));

        $start = $this->faker->dateTimeBetween('+3 days', '+45 days');
        $end   = (clone $start)->modify('+' . $this->faker->numberBetween(2,6) . ' hours');

        $city = $this->faker->randomElement(['Bandar Lampung','Metro','Jakarta','Yogyakarta','Bandung','Surabaya']);

        return [
            'organizer_id' => null, // diisi di Seeder
            'category_id'  => null, // diisi di Seeder
            'title'        => $title,
            'slug'         => $slug,
            'excerpt'      => $excerpt,
            'description'  => $description,
            'location_type'=> $this->faker->randomElement(['onsite','online','hybrid']),
            'city'         => $city,
            'address'      => $this->faker->address(),
            'starts_at'    => $start,
            'ends_at'      => $end,
            'capacity'     => $this->faker->randomElement([null,50,100,150,200]),
            'registration_count' => 0,
            'status'       => 'published',
            'published_at' => now(),
            'banner_path'  => "https://picsum.photos/seed/{$slug}/800/450",
        ];
    }
}
