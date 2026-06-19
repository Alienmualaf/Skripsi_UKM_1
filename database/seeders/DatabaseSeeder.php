<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $roles = [
            ['name' => 'administrator', 'display_name' => 'Administrator', 'description' => 'System Super Admin'],
            ['name' => 'admin_ukm', 'display_name' => 'Admin UKM', 'description' => 'Admin Pengelola UKM PSUP'],
            ['name' => 'pengurus', 'display_name' => 'Pengurus UKM', 'description' => 'Pengurus Harian UKM PSUP'],
            ['name' => 'anggota', 'display_name' => 'Anggota', 'description' => 'Anggota Aktif UKM PSUP'],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['name' => $r['name']], $r);
        }

        // Get Role IDs
        $adminRoleId = DB::table('roles')->where('name', 'administrator')->value('id');
        $adminUkmRoleId = DB::table('roles')->where('name', 'admin_ukm')->value('id');
        $pengurusRoleId = DB::table('roles')->where('name', 'pengurus')->value('id');
        $anggotaRoleId = DB::table('roles')->where('name', 'anggota')->value('id');

        // 2. Seed Voice Classifications
        $voices = [
            ['name' => 'Sopran', 'description' => 'Suara tinggi wanita'],
            ['name' => 'Alto', 'description' => 'Suara rendah wanita'],
            ['name' => 'Tenor', 'description' => 'Suara tinggi pria'],
            ['name' => 'Bass', 'description' => 'Suara rendah pria'],
        ];
        foreach ($voices as $v) {
            DB::table('voice_classifications')->updateOrInsert(['name' => $v['name']], $v);
        }
        $sopranId = DB::table('voice_classifications')->where('name', 'Sopran')->value('id');
        $tenorId = DB::table('voice_classifications')->where('name', 'Tenor')->value('id');

        // 3. Seed Users
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'admin@psup.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'status' => 'active',
            ],
            [
                'name' => 'Admin UKM PSUP',
                'email' => 'admin_ukm@psup.com',
                'password' => Hash::make('password'),
                'role_id' => $adminUkmRoleId,
                'status' => 'active',
            ],
            [
                'name' => 'Pengurus UKM PSUP',
                'email' => 'pengurus@psup.com',
                'password' => Hash::make('password'),
                'role_id' => $pengurusRoleId,
                'status' => 'active',
            ],
            [
                'name' => 'Anggota PSUP',
                'email' => 'anggota@psup.com',
                'password' => Hash::make('password'),
                'role_id' => $anggotaRoleId,
                'status' => 'active',
            ]
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(['email' => $u['email']], $u);
        }

        // Get User IDs
        $adminUserId = DB::table('users')->where('email', 'admin@psup.com')->value('id');
        $adminUkmUserId = DB::table('users')->where('email', 'admin_ukm@psup.com')->value('id');
        $pengurusUserId = DB::table('users')->where('email', 'pengurus@psup.com')->value('id');
        $anggotaUserId = DB::table('users')->where('email', 'anggota@psup.com')->value('id');

        // 4. Seed Members for Admin UKM, Pengurus, and Anggota (administrator doesn't necessarily need a member profile)
        $members = [
            [
                'user_id' => $adminUkmUserId,
                'npm' => '4520210001',
                'name' => 'Admin UKM PSUP',
                'gender' => 'L',
                'faculty' => 'Teknik',
                'major' => 'Informatika',
                'class_year' => '2021',
                'birth_place' => 'Jakarta',
                'birth_date' => '2003-05-10',
                'address' => 'Srengseng Sawah, Jakarta Selatan',
                'phone' => '081234567890',
                'email' => 'admin_ukm@psup.com',
                'status' => 'Anggota Aktif',
                'voice_classification_id' => null,
            ],
            [
                'user_id' => $pengurusUserId,
                'npm' => '4520210002',
                'name' => 'Pengurus UKM PSUP',
                'gender' => 'P',
                'faculty' => 'Ekonomi dan Bisnis',
                'major' => 'Manajemen',
                'class_year' => '2021',
                'birth_place' => 'Depok',
                'birth_date' => '2003-08-15',
                'address' => 'Margonda, Depok',
                'phone' => '081298765432',
                'email' => 'pengurus@psup.com',
                'status' => 'Anggota Aktif',
                'voice_classification_id' => null,
            ],
            [
                'user_id' => $anggotaUserId,
                'npm' => '4520220003',
                'name' => 'Anggota PSUP',
                'gender' => 'L',
                'faculty' => 'Hukum',
                'major' => 'Ilmu Hukum',
                'class_year' => '2022',
                'birth_place' => 'Bogor',
                'birth_date' => '2004-02-20',
                'address' => 'Pajajaran, Bogor',
                'phone' => '085712345678',
                'email' => 'anggota@psup.com',
                'status' => 'Anggota Aktif',
                'voice_classification_id' => $tenorId,
            ]
        ];

        foreach ($members as $m) {
            DB::table('members')->updateOrInsert(['npm' => $m['npm']], $m);
        }

        // Get Member IDs
        $pengurusMemberId = DB::table('members')->where('npm', '4520210002')->value('id');
        $anggotaMemberId = DB::table('members')->where('npm', '4520220003')->value('id');

        // 6. Seed Sample Finance Transactions
        $finances = [
            [
                'type' => 'income',
                'amount' => 500000.00,
                'title' => 'Iuran Kas Bulan Mei 2026',
                'description' => 'Iuran kas bulanan anggota aktif',
                'transaction_date' => '2026-05-15',
            ],
            [
                'type' => 'income',
                'amount' => 3500000.00,
                'title' => 'Fee Penampilan Wisuda UP Semester Genap',
                'description' => 'Fee bernyanyi di acara Wisuda Universitas Pancasila',
                'transaction_date' => '2026-05-20',
            ],
            [
                'type' => 'expense',
                'amount' => 1500000.00,
                'title' => 'Honor Pelatih Bulan Mei 2026',
                'description' => 'Pembayaran honor pelatih utama Mas Hendra',
                'transaction_date' => '2026-05-28',
            ],
            [
                'type' => 'expense',
                'amount' => 300000.00,
                'title' => 'Konsumsi Latihan Rutin',
                'description' => 'Air minum dan roti untuk latihan berkala',
                'transaction_date' => '2026-05-22',
            ]
        ];

        foreach ($finances as $f) {
            DB::table('finances')->updateOrInsert(['title' => $f['title']], $f);
        }

        // 7. Seed Sample Trainers
        $trainers = [
            [
                'name' => 'Hendra Wijaya, S.Sn.',
                'specialty' => 'Vocal Director & Conductor',
                'phone' => '08122334455',
                'email' => 'hendra.vocals@gmail.com',
                'salary' => 1500000.00,
                'status' => 'Aktif',
            ],
            [
                'name' => 'Siti Aminah, M.Mus.',
                'specialty' => 'Vocal Coach (Sopran & Alto)',
                'phone' => '08566778899',
                'email' => 'siti.vocalcoach@gmail.com',
                'salary' => 1000000.00,
                'status' => 'Aktif',
            ]
        ];

        foreach ($trainers as $t) {
            DB::table('trainers')->updateOrInsert(['name' => $t['name']], $t);
        }

        // 8. Seed Sample Programs
        $programs = [
            [
                'name' => 'Konser Tahunan PSUP 2026',
                'division' => 'Divisi Acara',
                'description' => 'Konser musik paduan suara tahunan bertema Harmoni Nusantara.',
                'start_date' => '2026-08-01',
                'end_date' => '2026-08-02',
                'budget' => 15000000.00,
                'progress' => 20,
                'status' => 'Berjalan',
                'show_on_landing' => true,
            ],
            [
                'name' => 'Recruitment & Audisi Anggota Baru',
                'division' => 'Divisi PSDM',
                'description' => 'Penerimaan dan audisi klasifikasi suara calon anggota baru PSUP angkatan 2026.',
                'start_date' => '2026-09-15',
                'end_date' => '2026-09-30',
                'budget' => 2000000.00,
                'progress' => 0,
                'status' => 'Perencanaan',
                'show_on_landing' => false,
            ]
        ];

        foreach ($programs as $p) {
            DB::table('programs')->updateOrInsert(['name' => $p['name']], $p);
        }

        // Get Program ID for Performance
        $programId = DB::table('programs')->where('name', 'Konser Tahunan PSUP 2026')->value('id');

        // 9. Seed Sample Performances & Classrooms
        if ($programId) {
            DB::table('performances')->updateOrInsert(
                ['title' => 'Konser Utama Harmoni Nusantara'],
                [
                    'program_id' => $programId,
                    'title' => 'Konser Utama Harmoni Nusantara',
                    'venue' => 'Aula Gedung Rektorat Lt. 4',
                    'performance_date' => '2026-08-01',
                    'performance_time' => '19:00:00',
                    'description' => 'Konser tahunan utama PSUP membawakan lagu-lagu daerah dan klasik.',
                    'dress_code' => 'Jas Hitam Almamater',
                    'status' => 'Persiapan',
                    'show_on_landing' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Get Performance ID
            $performanceId = DB::table('performances')->where('title', 'Konser Utama Harmoni Nusantara')->value('id');

            // 10. Seed Classrooms
            if ($performanceId) {
                DB::table('classrooms')->updateOrInsert(
                    ['name' => 'Kelas Konser 2026'],
                    [
                        'performance_id' => $performanceId,
                        'name' => 'Kelas Konser 2026',
                        'description' => 'Ruang koordinasi materi & presensi untuk persiapan Konser Utama.',
                        'status' => 'Aktif',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Get Classroom ID
                $classroomId = DB::table('classrooms')->where('name', 'Kelas Konser 2026')->value('id');

                // 11. Seed Classroom Members Pivot
                if ($classroomId) {
                    DB::table('classroom_members')->updateOrInsert(
                        ['classroom_id' => $classroomId, 'member_id' => $pengurusMemberId],
                        ['role' => 'PJ']
                    );
                    DB::table('classroom_members')->updateOrInsert(
                        ['classroom_id' => $classroomId, 'member_id' => $anggotaMemberId],
                        ['role' => 'Peserta']
                    );

                    // 12. Seed Announcements (both global and classroom)
                    DB::table('announcements')->updateOrInsert(
                        ['title' => 'Selamat Datang di Portal UKM PSUP!'],
                        [
                            'title' => 'Selamat Datang di Portal UKM PSUP!',
                            'content' => 'Selamat beraktivitas di portal resmi Paduan Suara Universitas Pancasila.',
                            'created_by' => $adminUserId,
                            'classroom_id' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    DB::table('announcements')->updateOrInsert(
                        ['title' => 'Persiapan Latihan Konser Nusantara'],
                        [
                            'title' => 'Persiapan Latihan Konser Nusantara',
                            'content' => 'Diharapkan seluruh peserta kelas konser untuk mengunduh partitur terbaru di tab Materi.',
                            'created_by' => $pengurusUserId,
                            'classroom_id' => $classroomId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    // 13. Seed Attendances & Details
                    DB::table('attendances')->updateOrInsert(
                        ['title' => 'Latihan Perdana Kelas Konser'],
                        [
                            'classroom_id' => $classroomId,
                            'title' => 'Latihan Perdana Kelas Konser',
                            'type' => 'Latihan',
                            'date' => '2026-06-10',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $attendanceId = DB::table('attendances')->where('title', 'Latihan Perdana Kelas Konser')->value('id');
                    if ($attendanceId) {
                        DB::table('attendance_details')->updateOrInsert(
                            ['attendance_id' => $attendanceId, 'member_id' => $pengurusMemberId],
                            ['status' => 'Hadir', 'created_at' => now(), 'updated_at' => now()]
                        );
                        DB::table('attendance_details')->updateOrInsert(
                            ['attendance_id' => $attendanceId, 'member_id' => $anggotaMemberId],
                            ['status' => 'Hadir', 'created_at' => now(), 'updated_at' => now()]
                        );
                    }

                    // 14. Seed Materials linked to Classroom
                    DB::table('materials')->updateOrInsert(
                        ['title' => 'Partitur Bagimu Negeri - SATB'],
                        [
                            'title' => 'Partitur Bagimu Negeri - SATB',
                            'type' => 'Partitur',
                            'file_path' => 'materials/sample_partitur.pdf',
                            'file_type' => 'pdf',
                            'description' => 'Partitur paduan suara lagu Bagimu Negeri aransemen SATB.',
                            'uploader_id' => $pengurusUserId,
                            'classroom_id' => $classroomId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        // 15. Seed Sample Inventories
        $inventories = [
            [
                'name' => 'Kostum Jas Hitam Almamater PSUP',
                'code' => 'INV-KST-001',
                'category' => 'Kostum',
                'condition' => 'Baik',
                'quantity' => 35,
                'storage_location' => 'Lemari Aset UKM',
            ],
            [
                'name' => 'Keyboard Yamaha PSR-S975',
                'code' => 'INV-MUS-001',
                'category' => 'Alat Musik',
                'condition' => 'Baik',
                'quantity' => 1,
                'storage_location' => 'Ruang Latihan PSUP',
            ],
            [
                'name' => 'Sound System & Mic Wireless Shure',
                'code' => 'INV-SND-001',
                'category' => 'Sound System',
                'condition' => 'Baik',
                'quantity' => 2,
                'storage_location' => 'Gudang Inventaris',
            ]
        ];

        foreach ($inventories as $i) {
            DB::table('inventories')->updateOrInsert(['code' => $i['code']], $i);
        }

        // 16. Seed Sample Achievements
        $achievements = [
            [
                'title' => 'Juara 1 Penampilan Paduan Suara Klasik Nasional 2025',
                'date' => '2025-10-12',
                'description' => 'Memenangkan medali emas dan Juara Umum pada ajang Lomba Paduan Suara Mahasiswa tingkat nasional di Universitas Indonesia.',
                'photo' => null,
                'show_on_landing' => true,
            ],
            [
                'title' => 'Gold Medal Bali International Choir Festival 2024',
                'date' => '2024-07-25',
                'description' => 'Mendapatkan penghargaan Gold Medal untuk kategori Musica Sacra pada ajang bergengsi BICF 2024.',
                'photo' => null,
                'show_on_landing' => true,
            ]
        ];

        foreach ($achievements as $ac) {
            DB::table('achievements')->updateOrInsert(['title' => $ac['title']], $ac);
        }

        // 17. Seed Organization Histories (Timeline)
        $histories = [
            [
                'year' => '2023',
                'title' => 'Pendirian & Konser Perdana',
                'description' => 'Pembentukan resmi PSUP sebagai Unit Kegiatan Mahasiswa Universitas Pancasila, ditandai dengan resital perdana.',
            ],
            [
                'year' => '2024',
                'title' => 'Medali Emas BICF 2024',
                'description' => 'PSUP berhasil meraih Gold Medal untuk kategori Musica Sacra pada ajang Bali International Choir Festival 2024.',
            ],
            [
                'year' => '2025',
                'title' => 'Juara Nasional & Konser Reminiscentia',
                'description' => 'Meraih juara umum di tingkat nasional dan melangsungkan konser tahunan termegah bertajuk Reminiscentia.',
            ]
        ];

        foreach ($histories as $h) {
            DB::table('organization_histories')->updateOrInsert(['year' => $h['year']], $h);
        }
    }
}
