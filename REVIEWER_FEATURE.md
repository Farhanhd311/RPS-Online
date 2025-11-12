# Reviewer RPS Feature

## Overview
Fitur ini memungkinkan reviewer untuk melihat, mengevaluasi, dan menyetujui/menolak RPS yang dibuat oleh dosen. Setelah disetujui, RPS akan muncul di halaman mahasiswa.

## Workflow RPS
1. **Dosen** membuat RPS → Status: `submitted`
2. **Reviewer** mereview RPS → Menyetujui/Menolak
3. **Jika disetujui** → Status: `approved` → Muncul di halaman mahasiswa
4. **Jika ditolak** → Status: `rejected` → Dosen perlu revisi

## Features Added

### 1. ReviewerController
- `reviewRps()` - Menampilkan daftar RPS yang perlu direview
- `showRpsDetail()` - Menampilkan detail RPS untuk review
- `viewRpsPdf()` - Melihat PDF RPS
- `approveRps()` - Menyetujui RPS
- `rejectRps()` - Menolak RPS dengan catatan

### 2. Database Changes
- Menambahkan kolom `reviewer_notes` untuk menyimpan catatan reviewer
- RPS status otomatis menjadi `submitted` saat dibuat dosen

### 3. Routes Reviewer
- `GET /reviewer/{code}/review-rps` - Halaman daftar RPS
- `GET /reviewer/{code}/rps/{rps_id}/detail` - Detail RPS
- `GET /reviewer/{code}/rps/{rps_id}/view` - Lihat PDF
- `POST /reviewer/{code}/rps/{rps_id}/approve` - Setujui RPS
- `POST /reviewer/{code}/rps/{rps_id}/reject` - Tolak RPS

### 4. Views
- `reviewer_review_rps.blade.php` - Daftar RPS untuk review
- `reviewer_rps_detail.blade.php` - Detail dan form approval/rejection

### 5. Status RPS
- `draft` - RPS dalam tahap draft (tidak digunakan saat ini)
- `submitted` - RPS telah dibuat dosen, menunggu review
- `approved` - RPS disetujui reviewer, muncul di mahasiswa
- `rejected` - RPS ditolak reviewer, perlu revisi

## Files Modified

### Controllers
- `app/Http/Controllers/ReviewerController.php` - **NEW** Controller untuk reviewer
- `app/Http/Controllers/RpsController.php` - Status RPS menjadi 'submitted' saat dibuat
- `app/Http/Controllers/FacultyController.php` - Sudah mendukung filtering berdasarkan role

### Models
- `app/Models/Rps.php` - Menambahkan `reviewer_notes` ke fillable

### Database
- `database/migrations/2025_11_12_063438_add_reviewer_notes_to_rps_table.php` - **NEW**
- `database/seeders/SubmitRpsSeeder.php` - **NEW** Untuk testing

### Routes
- `routes/web.php` - Menambahkan routes reviewer

### Views
- `resources/views/reviewer/reviewer_review_rps.blade.php` - **UPDATED**
- `resources/views/reviewer/reviewer_rps_detail.blade.php` - **NEW**
- `resources/views/dosen/dosen_rps.blade.php` - Menampilkan status RPS

## Setup Instructions

1. **Run migration**:
   ```bash
   php artisan migrate
   ```

2. **Untuk testing - buat RPS dengan status submitted**:
   ```bash
   php artisan db:seed --class=SubmitRpsSeeder
   ```

3. **Pastikan ada user dengan role 'reviewer'** di database

## Testing Workflow

### 1. Sebagai Dosen
1. Login sebagai dosen
2. Buat RPS baru → Status otomatis menjadi 'submitted'
3. Lihat status RPS di halaman dosen (Menunggu Review)

### 2. Sebagai Reviewer
1. Login sebagai reviewer
2. Akses halaman Review RPS
3. Pilih semester yang memiliki RPS untuk direview
4. Klik "Review" pada RPS yang ingin dievaluasi
5. Lihat detail RPS dan PDF
6. Pilih "Setujui RPS" atau "Tolak RPS"
7. Isi catatan (wajib untuk penolakan)
8. Konfirmasi keputusan

### 3. Sebagai Mahasiswa
1. Login sebagai mahasiswa
2. RPS yang disetujui akan muncul di halaman mahasiswa
3. Mahasiswa bisa melihat dan download PDF RPS

## Status Indicators

### Di Halaman Dosen
- **Draft**: Kuning - RPS masih draft
- **Menunggu Review**: Biru - RPS telah disubmit, menunggu reviewer
- **Disetujui**: Hijau - RPS telah disetujui reviewer
- **Ditolak**: Merah - RPS ditolak, perlu revisi

### Di Halaman Reviewer
- **Draft**: Kuning - RPS draft (jarang muncul)
- **Submitted**: Biru - RPS siap untuk direview

## Security Features

- Reviewer hanya bisa melihat RPS dengan status 'draft' atau 'submitted'
- Mahasiswa hanya bisa melihat RPS dengan status 'approved'
- Setiap aksi approval/rejection dicatat dengan timestamp dan user ID
- Catatan penolakan wajib diisi untuk memberikan feedback ke dosen

## Database Schema

### Kolom Baru di Tabel `rps`
- `reviewer_notes` (TEXT, nullable) - Catatan dari reviewer
- `approved_by` (existing) - ID reviewer yang menyetujui/menolak
- `approved_at` (existing) - Timestamp approval/rejection

## Future Enhancements
- Email notification ke dosen saat RPS disetujui/ditolak
- History log perubahan status RPS
- Bulk approval untuk reviewer
- Dashboard statistik untuk admin
