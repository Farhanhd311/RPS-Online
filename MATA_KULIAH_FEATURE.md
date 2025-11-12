# Mata Kuliah Management Feature

## Overview
Fitur ini memungkinkan dosen untuk menambahkan mata kuliah baru langsung dari interface dosen. Mata kuliah yang ditambahkan akan langsung tersimpan ke database dan muncul di daftar mata kuliah.

## Features Added

### 1. MataKuliahController
- `store()` - Menambahkan mata kuliah baru dengan validasi
- `getBySemester()` - Mengambil mata kuliah berdasarkan semester (untuk AJAX)
- `destroy()` - Menghapus mata kuliah (dengan pengecekan RPS)

### 2. Database Structure
- Tabel: `mata_kuliah`
- Kolom: `id`, `kode_matakuliah`, `nama_matakuliah`, `sks`, `semester`, `timestamps`
- Validasi: Kode mata kuliah harus unique

### 3. Routes
- `POST /dosen/{code}/mata-kuliah` - Tambah mata kuliah baru
- `GET /dosen/{code}/mata-kuliah/semester/{semester}` - Get mata kuliah by semester
- `DELETE /dosen/{code}/mata-kuliah/{id}` - Hapus mata kuliah

### 4. UI Components
- Modal form untuk input mata kuliah baru
- Button "Tambah Mata Kuliah" di halaman dosen
- Form validation dengan feedback
- Loading state saat submit

### 5. Validation Rules
- **Kode Mata Kuliah**: Required, max 20 karakter, unique
- **Nama Mata Kuliah**: Required, max 255 karakter
- **SKS**: Required, integer, 1-6
- **Semester**: Required, integer, 1-8

## Files Modified/Created

### Controllers
- `app/Http/Controllers/MataKuliahController.php` - **NEW** Controller untuk mata kuliah

### Routes
- `routes/web.php` - Menambahkan routes mata kuliah

### Views
- `resources/views/dosen/dosen_rps.blade.php` - **UPDATED** Menambahkan modal dan button
- `resources/views/layouts/app.blade.php` - **UPDATED** Menambahkan CSRF token

### Database
- `database/seeders/MataKuliahSeeder.php` - **NEW** Seeder untuk data mata kuliah

### Models
- `app/Models/MataKuliah.php` - **EXISTING** Model sudah ada

## Form Fields

### Modal "Tambah Mata Kuliah Baru"
1. **Kode Mata Kuliah**
   - Input text
   - Placeholder: "Contoh: SI001"
   - Required, unique

2. **Nama Mata Kuliah**
   - Input text
   - Placeholder: "Contoh: Pemrograman Web"
   - Required

3. **SKS**
   - Select dropdown
   - Options: 1-6 SKS
   - Required

4. **Semester**
   - Select dropdown
   - Options: Semester 1-8
   - Required

## JavaScript Functionality

### AJAX Submit
- Menggunakan Fetch API untuk submit form
- CSRF token protection
- Loading state management
- Error handling dengan alert
- Success feedback dan page reload

### Alpine.js Data
```javascript
{
    showAddMataKuliah: false,
    isSubmitting: false,
    newMataKuliah: {
        kode_matakuliah: '',
        nama_matakuliah: '',
        sks: '',
        semester: ''
    }
}
```

## Security Features

### Validation
- Server-side validation untuk semua input
- Unique constraint untuk kode mata kuliah
- CSRF token protection untuk AJAX requests

### Access Control
- Hanya dosen yang bisa menambah mata kuliah
- Route protection dengan middleware auth

### Data Integrity
- Pengecekan RPS sebelum menghapus mata kuliah
- Transaction untuk operasi database
- Error logging untuk debugging

## Usage Instructions

### Untuk Dosen
1. **Login sebagai dosen**
2. **Akses halaman RPS dosen**
3. **Klik tombol "Tambah Mata Kuliah"**
4. **Isi form dengan data mata kuliah:**
   - Kode mata kuliah (contoh: SI404)
   - Nama mata kuliah (contoh: Machine Learning)
   - SKS (1-6)
   - Semester (1-8)
5. **Klik "Simpan"**
6. **Mata kuliah baru akan muncul di daftar**

### Validasi Error Messages
- "Kode mata kuliah wajib diisi"
- "Kode mata kuliah sudah ada"
- "Nama mata kuliah wajib diisi"
- "SKS wajib diisi"
- "SKS minimal 1"
- "SKS maksimal 6"
- "Semester wajib diisi"
- "Semester minimal 1"
- "Semester maksimal 8"

## Testing

### Setup Data
```bash
php artisan db:seed --class=MataKuliahSeeder
```

### Test Cases
1. **Tambah mata kuliah valid** - Should success
2. **Tambah dengan kode duplicate** - Should show error
3. **Tambah dengan field kosong** - Should show validation error
4. **Tambah dengan SKS invalid** - Should show validation error
5. **Tambah dengan semester invalid** - Should show validation error

## API Response Format

### Success Response
```json
{
    "success": true,
    "message": "Mata kuliah berhasil ditambahkan!",
    "data": {
        "id": 1,
        "kode_matakuliah": "SI404",
        "nama_matakuliah": "Machine Learning",
        "sks": 3,
        "semester": 5
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Kode mata kuliah sudah ada"
}
```

## Database Schema

### Tabel mata_kuliah
```sql
CREATE TABLE mata_kuliah (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_matakuliah VARCHAR(20) UNIQUE NOT NULL,
    nama_matakuliah VARCHAR(255) NOT NULL,
    sks INT DEFAULT 0,
    semester INT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Future Enhancements
- Bulk import mata kuliah dari Excel/CSV
- Edit mata kuliah yang sudah ada
- Mata kuliah prerequisites management
- Mata kuliah category/group
- Export mata kuliah list
- Advanced search dan filter
- Mata kuliah history/versioning
