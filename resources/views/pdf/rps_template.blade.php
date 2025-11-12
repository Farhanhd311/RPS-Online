<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RPS - {{ $rps->nama_matakuliah }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 10pt;
            line-height: 1.3;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        
        table, th, td {
            border: 1px solid #000;
        }
        
        th, td {
            padding: 5px;
            vertical-align: top;
        }
        
        th {
            background-color: #f0e8d0;
            font-weight: bold;
            text-align: center;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .header h3 {
            font-size: 12pt;
            margin: 2px 0;
        }
        
        .header h4 {
            font-size: 11pt;
            margin: 2px 0;
            font-weight: bold;
        }
        
        .logo-cell {
            width: 80px;
            text-align: center;
            vertical-align: middle;
        }
        
        .logo-img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        
        .header-cell {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }
        
        .section-title {
            background-color: #d0d0d0;
            font-weight: bold;
            padding: 5px;
        }
        
        .gray-bg {
            background-color: #e0e0e0;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-left {
            text-align: left;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .small {
            font-size: 8pt;
        }
        
        ul {
            margin-left: 20px;
            list-style-type: decimal;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <table>
        <tr>
            <td rowspan="5" style="width: 80px; text-align: center; vertical-align: middle; border: 1px solid #000;">
                <!-- Logo Universitas Andalas -->
                <div style="width: 60px; height: 60px; margin: auto; background-color: #2d5016; border-radius: 50%; display: flex; align-items: center; justify-content: center; position: relative;">
                    <!-- Pohon Beringin -->
                    <div style="position: absolute; top: 8px; left: 50%; transform: translateX(-50%); width: 40px; height: 40px;">
                        <!-- Batang pohon -->
                        <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 4px; height: 15px; background-color: #8B4513;"></div>
                        <!-- Daun pohon -->
                        <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); width: 35px; height: 25px; background-color: #228B22; border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%; opacity: 0.9;"></div>
                        <!-- Daun tambahan -->
                        <div style="position: absolute; bottom: 15px; left: 45%; transform: translateX(-50%); width: 20px; height: 15px; background-color: #32CD32; border-radius: 50%; opacity: 0.8;"></div>
                        <div style="position: absolute; bottom: 15px; right: 45%; transform: translateX(50%); width: 20px; height: 15px; background-color: #32CD32; border-radius: 50%; opacity: 0.8;"></div>
                    </div>
                    <!-- Text UNAND -->
                    <div style="position: absolute; bottom: 2px; color: white; font-weight: bold; font-size: 6pt; text-align: center; line-height: 0.8;">
                        UNAND
                    </div>
                </div>
            </td>
            <td style="text-align: center; font-weight: bold; font-size: 14pt; padding: 3px; border: 1px solid #000; line-height: 1.1;">UNIVERSITAS ANDALAS</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; font-size: 12pt; padding: 3px; border: 1px solid #000; line-height: 1.0;">FAKULTAS TEKNOLOGI INFORMASI</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; font-size: 12pt; padding: 3px; border: 1px solid #000; line-height: 1.0;">DEPARTEMEN SISTEM INFORMASI</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bold; font-size: 13pt; padding: 3px; background-color: #f0e8d0; border: 1px solid #000; line-height: 1.0;">RENCANA PEMBELAJARAN SEMESTER</td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 2px;"></td>
        </tr>
    </table>

    <!-- Info Mata Kuliah -->
    <table>
        <tr>
            <td class="bold gray-bg text-center" style="width: 80px;">MATA KULIAH</td>
            <td class="bold gray-bg text-center" style="width: 10%;">KODE</td>
            <td class="bold gray-bg text-center" style="width: 25%;">BAHAN KAJIAN</td>
            <td class="bold gray-bg text-center" style="width: 15%;">BOBOT (SKS)</td>
            <td class="bold gray-bg text-center" style="width: 10%;">SEMESTER</td>
            <td class="bold gray-bg text-center" style="width: 25%;">TANGGAL PENYUSUNAN</td>
        </tr>
        <tr>
            <td class="text-center">{{ $rps->nama_matakuliah }}</td>
            <td class="text-center">{{ $rps->kode_matakuliah }}</td>
            <td>{{ $rps->bahan_kajian ?? '-' }}</td>
            <td class="text-center">{{ $rps->sks }}</td>
            <td class="text-center">{{ $rps->semester }}</td>
            <td class="text-center">{{ $rps->tanggal_penyusunan }}</td>
        </tr>
    </table>

    <!-- Otorisasi -->
    <table>
        <tr>
            <td rowspan="{{ count($rps->dosen_pengampu ?? []) + 1 }}" class="bold gray-bg" style="width: 20%;">OTORISASI</td>
            <td class="bold text-center" style="width: 30%;">DOSEN PENGEMBANG RPS</td>
            <td class="bold text-center" style="width: 30%;">KOORDINATOR BK</td>
            <td class="bold text-center" style="width: 20%;">KAPRODI</td>
        </tr>
        <tr>
            <td class="text-center">{{ $rps->dosen_pengembang }}</td>
            <td class="text-center">{{ $rps->koordinasi_bk }}</td>
            <td class="text-center">{{ $rps->kaprodi }}</td>
        </tr>
    </table>

    <!-- Capaian Pembelajaran -->
    <table>
        <tr>
            <td colspan="2" class="section-title">CAPAIAN PEMBELAJARAN</td>
        </tr>
        <tr>
            <td class="bold gray-bg" style="width: 30%;">CPL-PRODI yang dibebankan pada MK</td>
            <td>
                @if($rps->cpl_prodi && is_array($rps->cpl_prodi))
                    @foreach($rps->cpl_prodi as $index => $cpl)
                        <div style="margin-bottom: 8px;">
                            <strong>CPL-{{ $index + 1 }}:</strong> {{ $cpl }}
                            @php
                                // Deskripsi CPL berdasarkan kode
                                $cplDescriptions = [
                                    'CPL-1' => 'Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ilmu pengetahuan dan teknologi yang memperhatikan dan menerapkan nilai humaniora.',
                                    'CPL-2' => 'Mampu menunjukkan kinerja mandiri, bermutu, dan terukur dalam bidang sistem informasi.',
                                    'CPL-3' => 'Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi yang memperhatikan dan menerapkan nilai humaniora sesuai dengan keahliannya.',
                                    'CPL-4' => 'Mampu menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir, dan mengunggahnya dalam laman perguruan tinggi.',
                                    'CPL-5' => 'Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya, berdasarkan hasil analisis informasi dan data.',
                                    'CPL-6' => 'Mampu memelihara dan mengembangkan jaringan kerja dengan pembimbing, kolega, sejawat baik di dalam maupun di luar lembaganya.',
                                    'CPL-7' => 'Mampu bertanggungjawab atas pencapaian hasil kerja kelompok dan melakukan supervisi serta evaluasi terhadap penyelesaian pekerjaan yang ditugaskan kepada pekerja yang berada di bawah tanggungjawabnya.',
                                    'CPL-8' => 'Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada dibawah tanggung jawabnya, dan mampu mengelola pembelajaran secara mandiri.',
                                    'CPL-9' => 'Mampu mendokumentasikan, menyimpan, mengamankan, dan menemukan kembali data untuk menjamin kesahihan dan mencegah plagiasi.'
                                ];
                                $cplKey = 'CPL-' . ($index + 1);
                                $description = $cplDescriptions[$cplKey] ?? '';
                            @endphp
                            @if($description)
                                <div style="font-size: 9pt; color: #555; margin-top: 3px; font-style: italic;">
                                    {{ $description }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td class="bold gray-bg" style="width: 30%;">Indikator (IK)</td>
            <td>
                @if($rps->indikator && is_array($rps->indikator))
                    @foreach($rps->indikator as $index => $ik)
                        <div style="margin-bottom: 8px;">
                            <strong>IK-{{ $index + 1 }}:</strong> {{ $ik }}
                            @php
                                // Deskripsi IK berdasarkan kode
                                $ikDescriptions = [
                                    'IK-1' => 'Mampu mengidentifikasi, menganalisis, dan merumuskan masalah dalam domain sistem informasi.',
                                    'IK-2' => 'Mampu merancang solusi sistem informasi yang efektif dan efisien.',
                                    'IK-3' => 'Mampu mengimplementasikan sistem informasi menggunakan teknologi yang tepat.',
                                    'IK-4' => 'Mampu mengevaluasi dan memelihara sistem informasi yang telah dibangun.',
                                    'IK-5' => 'Mampu berkomunikasi secara efektif dalam tim pengembangan sistem informasi.',
                                    'IK-6' => 'Mampu menerapkan etika profesi dalam pengembangan sistem informasi.',
                                    'IK-7' => 'Mampu beradaptasi dengan perkembangan teknologi informasi terkini.',
                                    'IK-8' => 'Mampu mengelola proyek sistem informasi dengan baik.',
                                    'IK-9' => 'Mampu melakukan penelitian dalam bidang sistem informasi.'
                                ];
                                $ikKey = 'IK-' . ($index + 1);
                                $description = $ikDescriptions[$ikKey] ?? '';
                            @endphp
                            @if($description)
                                <div style="font-size: 9pt; color: #555; margin-top: 3px; font-style: italic;">
                                    {{ $description }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bold">Kemampuan akhir tiap tahapan belajar (CPMK)</td>
        </tr>
        @if($rps->cpmk && is_array($rps->cpmk))
            @foreach($rps->cpmk as $cpmk)
                <tr>
                    <td class="text-center">{{ $cpmk['kode'] ?? '' }}</td>
                    <td>{{ $cpmk['deskripsi'] ?? '' }}</td>
                </tr>
            @endforeach
        @endif
    </table>

    <!-- Korelasi CPMK terhadap CPL -->
    @if($rps->cpmk && is_array($rps->cpmk) && count($rps->cpmk) > 0)
    <table>
        <tr>
            <td colspan="{{ count($rps->cpl_prodi ?? []) + count($rps->indikator ?? []) + 2 }}" class="section-title">Korelasi CPMK terhadap CPL</td>
        </tr>
        <tr>
            <td class="bold text-center">CPMK</td>
            <td class="bold text-center">Indikator (IK)</td>
            <td class="bold text-center">CPL yang Didukung</td>
        </tr>
        @foreach($rps->cpmk as $index => $cpmk)
            <tr>
                <td class="text-center">{{ $cpmk['kode'] ?? '' }}</td>
                <td>
                    @if(isset($rps->korelasi[$index]['ik']))
                        {{ implode(', ', $rps->korelasi[$index]['ik']) }}
                    @endif
                </td>
                <td>
                    @if(isset($rps->korelasi[$index]['cpl']))
                        {{ implode(', ', $rps->korelasi[$index]['cpl']) }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
    @endif

    <!-- Komponen Asesmen -->
    <table>
        <tr>
            <td colspan="3" class="section-title">Komponen Asesmen</td>
        </tr>
        <tr>
            <td class="bold text-center" style="width: 40%;">Nama Komponen</td>
            <td class="bold text-center" style="width: 20%;">Bobot (%)</td>
            <td class="bold text-center" style="width: 40%;">Keterangan</td>
        </tr>
        @if($rps->asesmen && is_array($rps->asesmen))
            @php $totalBobot = 0; @endphp
            @foreach($rps->asesmen as $asesmen)
                <tr>
                    <td>{{ $asesmen['jenis'] ?? '' }}</td>
                    <td class="text-center">{{ $asesmen['bobot'] ?? 0 }}%</td>
                    <td>{{ $asesmen['keterangan'] ?? '' }}</td>
                </tr>
                @php $totalBobot += $asesmen['bobot'] ?? 0; @endphp
            @endforeach
            <tr>
                <td class="bold">TOTAL</td>
                <td class="bold text-center">{{ $totalBobot }}%</td>
                <td></td>
            </tr>
        @endif
    </table>

    <!-- Deskripsi Singkat MK -->
    <table>
        <tr>
            <td class="section-title">Deskripsi Singkat MK</td>
        </tr>
        <tr>
            <td style="text-align: justify;">{{ $rps->deskripsi_mk }}</td>
        </tr>
    </table>

    <!-- Bahan Kajian: Materi Pembelajaran -->
    @if($rps->materi_pembelajaran && is_array($rps->materi_pembelajaran) && count($rps->materi_pembelajaran) > 0)
    <table>
        <tr>
            <td class="section-title">Bahan Kajian: Materi Pembelajaran</td>
        </tr>
        <tr>
            <td>
                <ol>
                    @foreach($rps->materi_pembelajaran as $materi)
                        @if(!empty($materi))
                            <li>{{ is_array($materi) && isset($materi['isi']) ? $materi['isi'] : $materi }}</li>
                        @endif
                    @endforeach
                </ol>
            </td>
        </tr>
    </table>
    @endif

    <!-- Pustaka -->
    <table>
        <tr>
            <td class="section-title">Pustaka</td>
        </tr>
        <tr>
            <td class="bold">1. Utama</td>
        </tr>
        @if($rps->pustaka_utama && is_array($rps->pustaka_utama))
            @foreach($rps->pustaka_utama as $index => $pustaka)
                @if(!empty($pustaka))
                    <tr>
                        <td>{{ $index + 1 }}. {{ is_array($pustaka) && isset($pustaka['isi']) ? $pustaka['isi'] : $pustaka }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td class="bold">2. Pendukung</td>
        </tr>
        @if($rps->pustaka_pendukung && is_array($rps->pustaka_pendukung))
            @foreach($rps->pustaka_pendukung as $index => $pustaka)
                @if(!empty($pustaka))
                    <tr>
                        <td>{{ $index + 1 }}. {{ is_array($pustaka) && isset($pustaka['isi']) ? $pustaka['isi'] : $pustaka }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
    </table>

    <!-- Media Pembelajaran -->
    <table>
        <tr>
            <td class="section-title">Media Pembelajaran</td>
        </tr>
        <tr>
            <td class="bold">1. Perangkat Lunak</td>
        </tr>
        @if($rps->perangkat_lunak && is_array($rps->perangkat_lunak))
            @foreach($rps->perangkat_lunak as $software)
                @if(!empty($software))
                    <tr>
                        <td>{{ is_array($software) && isset($software['isi']) ? $software['isi'] : $software }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td class="bold">2. Perangkat Keras</td>
        </tr>
        @if($rps->perangkat_keras && is_array($rps->perangkat_keras))
            @foreach($rps->perangkat_keras as $hardware)
                @if(!empty($hardware))
                    <tr>
                        <td>{{ is_array($hardware) && isset($hardware['isi']) ? $hardware['isi'] : $hardware }}</td>
                    </tr>
                @endif
            @endforeach
        @endif
    </table>

    <!-- Dosen Pengampu -->
    @if($rps->dosen_pengampu && is_array($rps->dosen_pengampu) && count($rps->dosen_pengampu) > 0)
    <table>
        <tr>
            <td class="section-title">Dosen Pengampu</td>
        </tr>
        @foreach($rps->dosen_pengampu as $index => $dosen)
            @if(!empty($dosen))
                <tr>
                    <td>{{ $index + 1 }}. {{ is_array($dosen) && isset($dosen['nama']) ? $dosen['nama'] : $dosen }}</td>
                </tr>
            @endif
        @endforeach
    </table>
    @endif

    <!-- Mata Kuliah Prasyarat -->
    @if($rps->mk_prasyarat && is_array($rps->mk_prasyarat) && count($rps->mk_prasyarat) > 0)
    <table>
        <tr>
            <td class="section-title">Mata Kuliah Prasyarat</td>
        </tr>
        @foreach($rps->mk_prasyarat as $prasyarat)
            @if(!empty($prasyarat))
                <tr>
                    <td>{{ is_array($prasyarat) && isset($prasyarat['nama']) ? $prasyarat['nama'] : $prasyarat }}</td>
                </tr>
            @endif
        @endforeach
    </table>
    @endif

    <!-- Page break sebelum tabel aktivitas pembelajaran -->
    <div class="page-break"></div>

    <!-- Rencana Pembelajaran Semester (Aktivitas Pembelajaran) -->
    @if($rps->aktivitasPembelajaran && count($rps->aktivitasPembelajaran) > 0)
    <table style="font-size: 8pt;">
        <tr>
            <td colspan="11" class="section-title">Rencana Pembelajaran Semester</td>
        </tr>
        <tr>
            <td class="bold text-center" style="width: 5%;">Mg Ke</td>
            <td class="bold text-center" style="width: 10%;">CPMK</td>
            <td class="bold text-center" style="width: 10%;">Indikator Penilaian</td>
            <td class="bold text-center" style="width: 10%;">Bentuk Penilaian</td>
            <td colspan="4" class="bold text-center">Aktivitas Pembelajaran</td>
            <td class="bold text-center" style="width: 10%;">Media</td>
            <td class="bold text-center" style="width: 10%;">Materi Pembelajaran</td>
            <td class="bold text-center" style="width: 10%;">Referensi</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2" class="bold text-center">Sinkronous</td>
            <td colspan="2" class="bold text-center">Asinkronous</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="bold text-center">Luring</td>
            <td class="bold text-center">Daring</td>
            <td class="bold text-center">Mandiri</td>
            <td class="bold text-center">Kolaboratif</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($rps->aktivitasPembelajaran as $aktivitas)
            <tr>
                <td class="text-center">{{ $aktivitas->minggu_ke }}</td>
                <td class="text-center">{{ $aktivitas->cpmk_kode }}</td>
                <td>{{ $aktivitas->indikator_penilaian }}</td>
                <td>{{ $aktivitas->bentuk_penilaian_jenis }}@if($aktivitas->bentuk_penilaian_bobot) ({{ $aktivitas->bentuk_penilaian_bobot }}%)@endif</td>
                <td>{{ $aktivitas->aktivitas_sinkron_luring }}</td>
                <td>{{ $aktivitas->aktivitas_sinkron_daring }}</td>
                <td>{{ $aktivitas->aktivitas_asinkron_mandiri }}</td>
                <td>{{ $aktivitas->aktivitas_asinkron_kolaboratif }}</td>
                <td>{{ $aktivitas->media }}</td>
                <td>{{ $aktivitas->materi_pembelajaran }}</td>
                <td>{{ $aktivitas->referensi }}</td>
            </tr>
        @endforeach
    </table>
    @endif
</body>
</html>
