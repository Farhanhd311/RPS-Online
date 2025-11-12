# Student RPS PDF Feature

## Overview
This feature allows students (mahasiswa role) to view and download approved RPS documents as PDF files.

## Features Added

### 1. Student-specific Routes
- `/mahasiswa/{code}/rps/{rps_id}/view` - View RPS PDF in browser
- `/mahasiswa/{code}/rps/{rps_id}/download` - Download RPS PDF

### 2. Access Control
- Students can only view/download RPS with status 'approved'
- Draft or pending RPS are not visible to students
- Automatic filtering in the FacultyController

### 3. Updated Student Interface
- RPS list now shows "Lihat PDF" and "Unduh PDF" buttons for approved RPS
- Shows "RPS belum tersedia" for courses without approved RPS
- Direct PDF links (no modal preview)

### 4. PDF Generation
- Uses existing PDF template (`resources/views/pdf/rps_template.blade.php`)
- Stores generated PDFs in `storage/app/public/rps/`
- Automatic PDF regeneration if file doesn't exist

## Files Modified

### Controllers
- `app/Http/Controllers/RpsController.php`
  - Added `viewPdfStudent()` method
  - Added `downloadPdfStudent()` method
  - Added Storage facade import

- `app/Http/Controllers/FacultyController.php`
  - Updated `rps()` method to filter approved RPS for students

### Routes
- `routes/web.php`
  - Added student routes for PDF view and download

### Views
- `resources/views/mahasiswa/mahasiswa_rps.blade.php`
  - Updated to show PDF links instead of mock buttons
  - Removed unused modal and JavaScript functions

### New Files
- `database/seeders/ApproveRpsSeeder.php` - For testing approved RPS
- `app/Console/Commands/EnsureStorageDirectories.php` - Ensure storage directories exist

## Setup Instructions

1. **Run migrations** (if not already done):
   ```bash
   php artisan migrate
   ```

2. **Create storage link** (if not already done):
   ```bash
   php artisan storage:link
   ```

3. **Ensure storage directories exist**:
   ```bash
   php artisan storage:ensure-directories
   ```

4. **For testing - approve some RPS records**:
   ```bash
   php artisan db:seed --class=ApproveRpsSeeder
   ```

## Testing

1. **Login as a student (mahasiswa role)**
2. **Navigate to the RPS page** for any faculty
3. **Select a semester** with courses that have approved RPS
4. **Click "Lihat PDF"** to view the RPS in browser
5. **Click "Unduh PDF"** to download the RPS file

## Technical Notes

- PDF files are cached in storage to improve performance
- If a cached PDF doesn't exist, it's regenerated automatically
- Students can only access approved RPS (status = 'approved')
- The PDF template includes all RPS data including learning activities
- File naming convention: `RPS_{course_code}_{date}.pdf`

## Security

- Route-level access control ensures only approved RPS are accessible
- Database queries filter by status='approved' for student role
- PDF files are served through Laravel's response system (not direct file access)
