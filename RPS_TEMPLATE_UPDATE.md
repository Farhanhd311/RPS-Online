# RPS Template Update

## Overview
Update template RPS PDF untuk meningkatkan tampilan dan informasi yang ditampilkan.

## Changes Made

### 1. Header Improvements
- **Logo Unand**: Menambahkan logo Universitas Andalas di kiri atas header
- **Centered Header**: Header text sekarang berada di tengah dengan ukuran font yang lebih besar
- **Balanced Layout**: Menambahkan space di kanan atas untuk keseimbangan visual
- **Styling**: Menambahkan background color untuk "RENCANA PEMBELAJARAN SEMESTER"

### 2. CPL-PRODI Enhancement
- **Detailed Display**: Setiap CPL sekarang ditampilkan dengan format "CPL-1:", "CPL-2:", dst.
- **Descriptions**: Menambahkan deskripsi lengkap untuk setiap CPL-PRODI
- **Styling**: Deskripsi ditampilkan dengan font lebih kecil dan italic untuk membedakan

#### CPL-PRODI Descriptions:
- **CPL-1**: Mampu menerapkan pemikiran logis, kritis, sistematis, dan inovatif dalam konteks pengembangan atau implementasi ilmu pengetahuan dan teknologi yang memperhatikan dan menerapkan nilai humaniora.
- **CPL-2**: Mampu menunjukkan kinerja mandiri, bermutu, dan terukur dalam bidang sistem informasi.
- **CPL-3**: Mampu mengkaji implikasi pengembangan atau implementasi ilmu pengetahuan teknologi yang memperhatikan dan menerapkan nilai humaniora sesuai dengan keahliannya.
- **CPL-4**: Mampu menyusun deskripsi saintifik hasil kajian tersebut di atas dalam bentuk skripsi atau laporan tugas akhir, dan mengunggahnya dalam laman perguruan tinggi.
- **CPL-5**: Mampu mengambil keputusan secara tepat dalam konteks penyelesaian masalah di bidang keahliannya, berdasarkan hasil analisis informasi dan data.
- **CPL-6**: Mampu memelihara dan mengembangkan jaringan kerja dengan pembimbing, kolega, sejawat baik di dalam maupun di luar lembaganya.
- **CPL-7**: Mampu bertanggungjawab atas pencapaian hasil kerja kelompok dan melakukan supervisi serta evaluasi terhadap penyelesaian pekerjaan yang ditugaskan kepada pekerja yang berada di bawah tanggungjawabnya.
- **CPL-8**: Mampu melakukan proses evaluasi diri terhadap kelompok kerja yang berada dibawah tanggung jawabnya, dan mampu mengelola pembelajaran secara mandiri.
- **CPL-9**: Mampu mendokumentasikan, menyimpan, mengamankan, dan menemukan kembali data untuk menjamin kesahihan dan mencegah plagiasi.

### 3. Indikator (IK) Enhancement
- **Detailed Display**: Setiap IK sekarang ditampilkan dengan format "IK-1:", "IK-2:", dst.
- **Descriptions**: Menambahkan deskripsi lengkap untuk setiap Indikator
- **Styling**: Deskripsi ditampilkan dengan font lebih kecil dan italic

#### Indikator (IK) Descriptions:
- **IK-1**: Mampu mengidentifikasi, menganalisis, dan merumuskan masalah dalam domain sistem informasi.
- **IK-2**: Mampu merancang solusi sistem informasi yang efektif dan efisien.
- **IK-3**: Mampu mengimplementasikan sistem informasi menggunakan teknologi yang tepat.
- **IK-4**: Mampu mengevaluasi dan memelihara sistem informasi yang telah dibangun.
- **IK-5**: Mampu berkomunikasi secara efektif dalam tim pengembangan sistem informasi.
- **IK-6**: Mampu menerapkan etika profesi dalam pengembangan sistem informasi.
- **IK-7**: Mampu beradaptasi dengan perkembangan teknologi informasi terkini.
- **IK-8**: Mampu mengelola proyek sistem informasi dengan baik.
- **IK-9**: Mampu melakukan penelitian dalam bidang sistem informasi.

## Technical Details

### CSS Classes Added
```css
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
```

### Logo Implementation
- Menggunakan styled div dengan gradient background
- Warna hijau sesuai identitas Universitas Andalas
- Border radius untuk bentuk circular
- Text "UNIV ANDALAS" di dalam logo

### Layout Structure
```
[LOGO] [HEADER TEXT] [SPACE]
```

### Font Sizes
- **UNIVERSITAS ANDALAS**: 14pt
- **FAKULTAS TEKNOLOGI INFORMASI**: 12pt
- **DEPARTEMEN SISTEM INFORMASI**: 12pt
- **RENCANA PEMBELAJARAN SEMESTER**: 13pt (dengan background)

### Description Styling
- **Font Size**: 9pt
- **Color**: #555 (gray)
- **Style**: Italic
- **Margin**: 3px top margin untuk spacing

## Files Modified
- `resources/views/pdf/rps_template.blade.php`

## Visual Improvements
1. **Professional Header**: Logo dan text yang seimbang
2. **Better Information**: CPL dan IK dengan deskripsi lengkap
3. **Clear Hierarchy**: Berbagai ukuran font untuk hierarchy yang jelas
4. **Consistent Styling**: Warna dan spacing yang konsisten

## Usage
Template akan otomatis menggunakan format baru saat:
1. Dosen membuat RPS baru
2. Generate PDF dari RPS yang ada
3. Review RPS oleh reviewer
4. Download RPS oleh mahasiswa

## Future Enhancements
- Menambahkan logo Unand yang sebenarnya (file image)
- Customizable CPL dan IK descriptions
- Dynamic logo berdasarkan fakultas
- Watermark untuk draft vs approved documents
