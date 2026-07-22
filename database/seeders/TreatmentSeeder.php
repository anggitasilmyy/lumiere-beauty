<?php

namespace Database\Seeders;

use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('email', 'admin@lumiere.com')->value('id');

        $treatments = [
            [
                'treatment_name' => 'Glow Facial Treatment',
                'slug' => 'glow-facial-treatment',
                'category' => 'Facial',
                'short_description' => 'Facial premium untuk membersihkan, melembapkan, dan membuat kulit tampak lebih glowing.',
                'description' => 'Glow Facial Treatment adalah perawatan wajah premium untuk membantu membersihkan kulit, menjaga kelembapan, dan membuat tampilan wajah terlihat lebih segar serta bercahaya.',
                'price' => 450000,
                'duration_minutes' => 75,
                'image' => 'assets/images/beauty1.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'treatment_name' => 'Acne Care Treatment',
                'slug' => 'acne-care-treatment',
                'category' => 'Acne',
                'short_description' => 'Perawatan intensif untuk membantu merawat kulit berjerawat dan pori-pori tersumbat.',
                'description' => 'Acne Care Treatment membantu merawat kulit berjerawat, membersihkan pori-pori, menenangkan kulit, dan mendukung proses perawatan kulit agar terlihat lebih sehat.',
                'price' => 550000,
                'duration_minutes' => 90,
                'image' => 'assets/images/beauty2.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'treatment_name' => 'Brightening Skin Treatment',
                'slug' => 'brightening-skin-treatment',
                'category' => 'Brightening',
                'short_description' => 'Treatment premium untuk membantu tampilan kulit terlihat lebih cerah dan merata.',
                'description' => 'Brightening Skin Treatment cocok untuk customer yang ingin membantu tampilan kulit terlihat lebih cerah, sehat, dan merata dengan perawatan klinik kecantikan.',
                'price' => 650000,
                'duration_minutes' => 90,
                'image' => 'assets/images/beauty3.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'treatment_name' => 'Bundle Glow Facial & Acne Care',
                'slug' => 'bundle-glow-facial-acne-care',
                'category' => 'Bundle',
                'short_description' => 'Paket kombinasi Glow Facial dan Acne Care untuk perawatan kulit glowing sekaligus jerawat.',
                'description' => 'Bundle Glow Facial & Acne Care menggabungkan facial premium dan perawatan acne care. Paket ini cocok untuk customer yang ingin kulit terasa lebih bersih, segar, dan tetap fokus pada perawatan kulit berjerawat.',
                'price' => 925000,
                'duration_minutes' => 150,
                'image' => 'assets/images/beauty1.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'treatment_name' => 'Bundle Glow Facial & Brightening',
                'slug' => 'bundle-glow-facial-brightening',
                'category' => 'Bundle',
                'short_description' => 'Paket kombinasi Glow Facial dan Brightening untuk kulit lebih segar dan tampak cerah.',
                'description' => 'Bundle Glow Facial & Brightening menggabungkan facial premium dan brightening treatment. Paket ini cocok untuk customer yang ingin kulit terlihat lebih glowing, cerah, dan terawat.',
                'price' => 1000000,
                'duration_minutes' => 150,
                'image' => 'assets/images/beauty3.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'treatment_name' => 'Bundle Complete Glow, Acne Care & Brightening',
                'slug' => 'bundle-complete-glow-acne-brightening',
                'category' => 'Bundle',
                'short_description' => 'Paket lengkap Glow Facial, Acne Care, dan Brightening untuk perawatan menyeluruh.',
                'description' => 'Bundle Complete Glow, Acne Care & Brightening adalah paket perawatan lengkap yang menggabungkan facial premium, acne care, dan brightening treatment untuk pengalaman treatment yang lebih menyeluruh.',
                'price' => 1475000,
                'duration_minutes' => 240,
                'image' => 'assets/images/beauty2.jpeg',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($treatments as $treatment) {
            Treatment::updateOrCreate(
                [
                    'slug' => $treatment['slug'],
                ],
                array_merge($treatment, [
                    'created_by' => $adminId,
                ])
            );
        }
    }
}