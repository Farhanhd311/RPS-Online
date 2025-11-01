<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $faculties = [
            [
                'code' => 'FTI',
                'name' => 'Fakultas Teknologi Informasi',
                'departments' => 3,
                'stats' => ['departments' => 3, 'dosen' => 45, 'mahasiswa' => 45],
                'cards' => [
                    ['title' => 'Profil'],
                    ['title' => 'Struktur Organisasi'],
                    ['title' => 'Departemen'],
                    ['title' => 'Tentang FTI'],
                ],
            ],
            [
                'code' => 'FT',
                'name' => 'Fakultas Teknik',
                'departments' => 6,
                'stats' => ['departments' => 6, 'dosen' => 120, 'mahasiswa' => 850],
                'cards' => [
                    ['title' => 'Profil'],
                    ['title' => 'Struktur Organisasi'],
                    ['title' => 'Departemen'],
                    ['title' => 'Tentang FT'],
                ],
            ],
            [
                'code' => 'FIB',
                'name' => 'Fakultas Ilmu Budaya',
                'departments' => 3,
                'stats' => ['departments' => 3, 'dosen' => 60, 'mahasiswa' => 500],
                'cards' => [
                    ['title' => 'Profil'],
                    ['title' => 'Struktur Organisasi'],
                    ['title' => 'Departemen'],
                    ['title' => 'Tentang FIB'],
                ],
            ],
            [
                'code' => 'FH',
                'name' => 'Fakultas Hukum',
                'departments' => 4,
                'stats' => ['departments' => 4, 'dosen' => 70, 'mahasiswa' => 600],
                'cards' => [
                    ['title' => 'Profil'],
                    ['title' => 'Struktur Organisasi'],
                    ['title' => 'Departemen'],
                    ['title' => 'Tentang FH'],
                ],
            ],
            [
                'code' => 'FEB',
                'name' => 'Fakultas Ekonomi dan Bisnis',
                'departments' => 5,
                'stats' => ['departments' => 5, 'dosen' => 90, 'mahasiswa' => 1000],
                'cards' => [
                    ['title' => 'Profil'],
                    ['title' => 'Struktur Organisasi'],
                    ['title' => 'Departemen'],
                    ['title' => 'Tentang FEB'],
                ],
            ],
        ];

        return view('mahasiswa.mahasiswa_fakultas', [
            'title' => 'Fakultas',
            'faculties' => $faculties,
        ]);
    }

    public function programs(string $code)
    {
        $map = [
            'FTI' => [
                'name' => 'Fakultas Teknologi Informasi',
                'stats' => ['departments' => 3, 'dosen' => 45, 'mahasiswa' => 45],
                'programs' => [
                    [
                        'name' => 'Program Studi S1 Teknik Komputer',
                        'visi' => 'Menjadi rujukan nasional bidang rekayasa perangkat keras dan sistem terbenam.',
                        'misi' => [
                            'Menghasilkan lulusan kompeten pada rekayasa perangkat keras.',
                            'Mendorong inovasi melalui riset terapan.',
                        ],
                    ],
                    [
                        'name' => 'Program Studi S1 Sistem Informasi',
                        'visi' => 'Menjadi program studi yang unggul dalam pengembangan sistem informasi yang berdampak.',
                        'misi' => [
                            'Menyelenggarakan pendidikan berkualitas di bidang sistem informasi.',
                            'Melaksanakan penelitian dan pengabdian relevan kebutuhan masyarakat.',
                            'Membangun kolaborasi industri dan institusi.',
                        ],
                    ],
                    [
                        'name' => 'Program Studi S1 Informatika',
                        'visi' => 'Menjadi program studi Informatika berdaya saing global.',
                        'misi' => [
                            'Memberikan pendidikan komputasi modern.',
                            'Menghasilkan karya ilmiah bereputasi.',
                        ],
                    ],
                ],
            ],
        ];

        abort_unless(isset($map[$code]), 404);
        $faculty = $map[$code];

        return view('mahasiswa.mahasiswa_departemen', [
            'title' => 'Departemen',
            'code' => $code,
            'faculty' => $faculty,
        ]);
    }

    public function programDetail(Request $request, string $code, string $slug)
    {
        abort_unless($code === 'FTI', 404);
        $slug = strtolower($slug);

        $programs = [
            'program-studi-s1-sistem-informasi' => [
                'name' => 'Program Studi S1 Sistem Informasi',
                'stats' => ['dosen' => 18, 'mahasiswa' => 420],
                'struktur' => [
                    ['jabatan' => 'Kaprodi', 'nama' => 'Ricky Akbar, M.Kom'],
                    ['jabatan' => 'Sekprodi', 'nama' => 'Afriyanti Dwi Kartika, M.T'],
                ],
                'kalender' => [
                    ['event' => 'Yudisium', 'tanggal' => '2025-12-15'],
                    ['event' => 'Seminar Proyek', 'tanggal' => '2025-11-20'],
                ],
                'berita' => [
                    ['judul' => 'Mahasiswa SI Juara Hackathon Nasional'],
                    ['judul' => 'Kerja Sama Riset dengan Industri'],
                ],
            ],
        ];

        abort_unless(isset($programs[$slug]), 404);
        $program = $programs[$slug];

        // Pengecekan role untuk menentukan view yang sesuai
        $user = Auth::user();
        $role = $request->query('role') ?? $user->role ?? 'mahasiswa';

        $viewMap = [
            'dosen' => 'dosen.dosen_departemen_detail',
            'reviewer' => 'reviewer.reviewer_departemen_detail',
            'mahasiswa' => 'mahasiswa.mahasiswa_departemen_detail',
            'admin' => 'mahasiswa.mahasiswa_departemen_detail',
        ];

        $viewName = $viewMap[$role] ?? 'mahasiswa.mahasiswa_departemen_detail';

        return view($viewName, [
            'title' => $program['name'],
            'code' => $code,
            'program' => $program,
        ]);
    }

    public function rps(Request $request, string $code)
    {
        $semesters = [
            [
                'value' => 1,
                'label' => 'Semester 1',
                'courses' => [
                    ['name' => 'Dasar-dasar Sistem Informasi'],
                    ['name' => 'Pengantar Bisnis dan Manajemen'],
                ],
            ],
            [
                'value' => 3,
                'label' => 'Semester 3',
                'courses' => [
                    ['name' => 'Analisis dan Perancangan Sistem Informasi'],
                    ['name' => 'Basis Data'],
                ],
            ],
            [
                'value' => 5,
                'label' => 'Semester 5',
                'courses' => [
                    ['name' => 'Manajemen Proyek TI'],
                    ['name' => 'Sistem Enterprise'],
                ],
            ],
        ];

        // Pengecekan role untuk menentukan view yang sesuai
        $user = Auth::user();
        $role = $request->query('role') ?? $user->role ?? 'mahasiswa';

        $viewMap = [
            'dosen' => 'dosen.dosen_rps',
            'reviewer' => 'reviewer.reviewer_rps',
            'mahasiswa' => 'mahasiswa.mahasiswa_rps',
            'admin' => 'mahasiswa.mahasiswa_rps',
        ];

        $viewName = $viewMap[$role] ?? 'mahasiswa.mahasiswa_rps';

        return view($viewName, [
            'title' => 'S1 Sistem Informasi',
            'code' => $code,
            'semesters' => $semesters,
        ]);
    }
}
