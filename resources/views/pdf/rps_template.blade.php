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
            <td rowspan="4" class="logo-cell">
                <!-- Logo placeholder -->
                <div style="width: 60px; height: 60px; margin: auto;"></div>
            </td>
            <td colspan="3" class="text-center bold">UNIVERSITAS ANDALAS</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center bold">FAKULTAS TEKNOLOGI INFORMASI</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center bold">DEPARTEMEN SISTEM INFORMASI</td>
        </tr>
        <tr>
            <td colspan="3" class="text-center bold">RENCANA PEMBELAJARAN SEMESTER</td>
        </tr>
        <tr>
            <td class="bold gray-bg" style="width: 20%;">MATA KULIAH<br>(MK)</td>
            <td class="bold" style="width: 15%;">KODE</td>
            <td class="bold" style="width: 35%;">BAHAN KAJIAN<br>(BK)</td>
            <td class="bold" style="width: 15%;">BOBOT (SKS)</td>
            <td class="bold" style="width: 15%;">SEMESTER</td>
            <td class="bold" style="width: 20%;">TANGGAL PENYUSUNAN</td>
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
                    @foreach($rps->cpl_prodi as $cpl)
                        <div>{{ $cpl }}</div>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" class="bold">Indikator (IK)</td>
        </tr>
        @if($rps->indikator && is_array($rps->indikator))
            @foreach($rps->indikator as $ik)
                <tr>
                    <td class="text-center">{{ $ik }}</td>
                    <td>{{ $ik }}</td>
                </tr>
            @endforeach
        @endif
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
