# Dokumentasi Lengkap Implementasi CRUD

## Ringkasan Sistem

Aplikasi Sistem Pengaduan Masyarakat ini mengimplementasikan operasi CRUD lengkap untuk 4 entitas utama:

1. **Masyarakat** - Data pengguna pelapor
2. **Petugas** - Data admin dan petugas penangani
3. **Pengaduan** - Data laporan/complaint dari masyarakat
4. **Tanggapan** - Data respons dari petugas terhadap pengaduan

---

## 1. CRUD MASYARAKAT

### Lokasi File

- **Controller**: `app/Http/Controllers/AdminController.php` (methods: indexMasyarakat, createMasyarakat, storeMasyarakat, showMasyarakat, editMasyarakat, updateMasyarakat, destroyMasyarakat)
- **Model**: `app/Models/Masyarakat.php`
- **FormRequest**: `app/Http/Requests/StoreMasyarakatRequest.php`, `UpdateMasyarakatRequest.php`

### Routes

\`\`\`
GET /admin/masyarakat - Tampilkan daftar masyarakat
GET /admin/masyarakat/create - Tampilkan form tambah
POST /admin/masyarakat - Simpan data baru
GET /admin/masyarakat/{id} - Tampilkan detail
GET /admin/masyarakat/{id}/edit - Tampilkan form edit
PUT /admin/masyarakat/{id} - Simpan perubahan
DELETE /admin/masyarakat/{id} - Hapus data
\`\`\`

### Fitur CRUD

- **Create**: Admin dapat menambah data masyarakat baru dengan validasi NIK 16 digit, email unik, username unik
- **Read**: Menampilkan daftar masyarakat dengan pagination dan detail pengaduan
- **Update**: Mengubah data masyarakat termasuk password opsional
- **Delete**: Menghapus masyarakat beserta pengaduan dan tanggapan terkait

### Validasi

- NIK: 16 digit, unik
- Nama: required, max 100 karakter
- Email: required, email format, unik
- Username: required, max 50 karakter, unik
- Password: min 8 karakter, harus mengandung huruf besar, kecil, dan angka

---

## 2. CRUD PETUGAS

### Lokasi File

- **Controller**: `app/Http/Controllers/AdminController.php` (methods: indexPetugas, createPetugas, storePetugas, showPetugas, editPetugas, updatePetugas, destroyPetugas)
- **Model**: `app/Models/Petugas.php`
- **FormRequest**: `app/Http/Requests/StorePetugasRequest.php`

### Routes

\`\`\`
GET /admin/petugas - Tampilkan daftar petugas
GET /admin/petugas/create - Tampilkan form tambah
POST /admin/petugas - Simpan data baru
GET /admin/petugas/{id} - Tampilkan detail
GET /admin/petugas/{id}/edit - Tampilkan form edit
PUT /admin/petugas/{id} - Simpan perubahan
DELETE /admin/petugas/{id} - Hapus data
\`\`\`

### Fitur CRUD

- **Create**: Admin dapat menambah petugas baru dengan level (admin/petugas) dan status (aktif/nonaktif)
- **Read**: Menampilkan daftar petugas dengan statistik tanggapan
- **Update**: Mengubah data petugas termasuk level dan status
- **Delete**: Menghapus petugas

### Validasi

- Nama: required, max 100 karakter
- Email: required, email format, unik
- Username: required, max 50 karakter, unik
- Password: min 8 karakter, harus mengandung huruf besar, kecil, dan angka
- Level: required, hanya 'admin' atau 'petugas'
- Status: required, hanya 'aktif' atau 'nonaktif'

### Fitur Tambahan

- Petugas dapat edit profil sendiri di `/petugas/profile/edit`
- Petugas dapat melihat laporan tanggapannya di `/petugas/laporan`
- Middleware `petugas.active` memastikan petugas nonaktif tidak dapat login

---

## 3. CRUD PENGADUAN

### Lokasi File

- **Controller**: `app/Http/Controllers/PengaduanController.php`
- **Model**: `app/Models/Pengaduan.php`
- **FormRequest**: `app/Http/Requests/StorePengaduanRequest.php`

### Routes Masyarakat

\`\`\`
GET /masyarakat/pengaduan - Tampilkan daftar pengaduan milik user
GET /masyarakat/pengaduan/create - Tampilkan form buat pengaduan
POST /masyarakat/pengaduan - Simpan pengaduan baru
GET /masyarakat/pengaduan/{id} - Tampilkan detail pengaduan
GET /masyarakat/pengaduan/{id}/edit - Tampilkan form edit
PUT /masyarakat/pengaduan/{id} - Simpan perubahan
DELETE /masyarakat/pengaduan/{id} - Hapus pengaduan
\`\`\`

### Routes Admin/Petugas

\`\`\`
GET /admin/pengaduan - Tampilkan semua pengaduan (admin)
GET /admin/pengaduan/{id} - Tampilkan detail
GET /admin/pengaduan/{id}/edit - Tampilkan form edit
PUT /admin/pengaduan/{id} - Simpan perubahan
DELETE /admin/pengaduan/{id} - Hapus pengaduan
\`\`\`

### Fitur CRUD

- **Create**: Masyarakat dapat membuat pengaduan dengan isi laporan dan foto opsional
- **Read**: Menampilkan pengaduan milik masyarakat dengan tanggapan dari petugas
- **Update**: Masyarakat dapat mengubah pengaduan hanya jika status masih "menunggu"
- **Delete**: Masyarakat dapat menghapus pengaduan milik mereka

### Validasi

- Isi laporan: required, min 20 karakter, max 2000 karakter
- Foto: optional, harus image (jpeg, png, jpg, gif), max 2MB
- Status: hanya 'menunggu', 'proses', 'selesai'

### Status Flow

\`\`\`
menunggu (baru) → proses (saat ada tanggapan) → selesai (manual update)
\`\`\`

---

## 4. CRUD TANGGAPAN

### Lokasi File

- **Controller**: `app/Http/Controllers/TanggapanController.php`
- **Model**: `app/Models/Tanggapan.php`
- **FormRequest**: `app/Http/Requests/StoreTanggapanRequest.php`

### Routes

\`\`\`
POST /petugas/tanggapan/{pengaduan} - Tambah tanggapan
GET /admin/tanggapan - Tampilkan daftar tanggapan
GET /admin/tanggapan/{id} - Tampilkan detail
GET /admin/tanggapan/{id}/edit - Tampilkan form edit
PUT /admin/tanggapan/{id} - Simpan perubahan
DELETE /admin/tanggapan/{id} - Hapus tanggapan
\`\`\`

### Fitur CRUD

- **Create**: Petugas dapat menambah tanggapan terhadap pengaduan dan otomatis update status pengaduan ke 'proses'
- **Read**: Admin dan petugas dapat melihat daftar tanggapan dengan filter
- **Update**: Admin dapat mengubah isi tanggapan
- **Delete**: Admin dapat menghapus tanggapan

### Validasi

- Isi tanggapan: required, min 10 karakter, max 2000 karakter

---

## Middleware & Authorization

### AdminLevel Middleware

- Memastikan hanya petugas dengan level 'admin' yang dapat mengakses `/admin/*` routes
- Cek apakah status petugas 'aktif'
- Redirect ke petugas dashboard jika tidak authorized

### PetugasActive Middleware

- Memastikan petugas dengan status 'nonaktif' dilogout otomatis
- Cek setiap request yang menggunakan guard 'petugas'

### Authenticate Middleware

- Memastikan user sudah login sebelum mengakses route yang dilindungi
- Support multiple guards: 'web' untuk masyarakat, 'petugas' untuk admin

---

## Error Handling

### Exception Handling

- **ModelNotFoundException**: Menampilkan pesan "Data tidak ditemukan"
- **ValidationException**: Menampilkan error validasi ke view
- **Generic Exception**: Menampilkan pesan error generic

### Response Methods (Base Controller)

\`\`\`php
$this->logAction($action, $modelType, $modelId, $oldValues, $newValues);
$this->successResponse($message, $data, $redirect);
$this->errorResponse($message, $errors, $statusCode);
$this->handleNotFound($model, $message);
\`\`\`

---

## Audit Logging

### Fitur

- Setiap operasi CREATE, UPDATE, DELETE dicatat di table `audit_logs`
- Menyimpan siapa yang melakukan aksi, kapan, dan perubahan apa saja
- Menggunakan trait `Auditable` di semua model

### Struktur Data

\`\`\`

- user_id: ID user yang melakukan aksi
- user_type: 'masyarakat' atau 'petugas'
- action: 'create', 'update', 'delete'
- model_type: Nama model yang diubah
- model_id: ID record yang diubah
- old_values: Nilai lama (JSON)
- new_values: Nilai baru (JSON)
- ip_address: IP address user
- user_agent: Browser/user agent string
  \`\`\`

---

## Cara Menggunakan

### Untuk Masyarakat

1. Login dengan username/password
2. Akses `/masyarakat/dashboard` untuk melihat dashboard
3. Buat pengaduan baru di `/masyarakat/pengaduan/create`
4. Lihat daftar pengaduan di `/masyarakat/pengaduan`
5. Lihat detail dan tanggapan di `/masyarakat/pengaduan/{id}`
6. Edit atau hapus pengaduan milik sendiri

### Untuk Petugas

1. Login dengan username/password
2. Akses `/petugas/dashboard` untuk melihat daftar pengaduan
3. Lihat detail pengaduan di `/petugas/pengaduan/show/{id}`
4. Tambah tanggapan di form pada halaman detail pengaduan
5. Update status pengaduan di `/petugas/pengaduan/{id}/status`
6. Edit profil di `/petugas/profile/edit`
7. Lihat laporan tanggapan di `/petugas/laporan`

### Untuk Admin

1. Login dengan username/password (harus level = 'admin')
2. Akses `/admin/dashboard` untuk melihat statistik
3. Kelola data masyarakat di `/admin/masyarakat`
4. Kelola data petugas di `/admin/petugas`
5. Kelola pengaduan di `/admin/pengaduan`
6. Kelola tanggapan di `/admin/tanggapan`

---

## File yang Dimodifikasi/Dibuat

### Controllers (Dibuat/Dimodifikasi)

- `app/Http/Controllers/AdminController.php` - BARU
- `app/Http/Controllers/PengaduanController.php` - MODIFIKASI
- `app/Http/Controllers/TanggapanController.php` - MODIFIKASI
- `app/Http/Controllers/PetugasController.php` - BARU
- `app/Http/Controllers/Controller.php` - MODIFIKASI

### Form Requests (Dibuat)

- `app/Http/Requests/StoreMasyarakatRequest.php`
- `app/Http/Requests/UpdateMasyarakatRequest.php`
- `app/Http/Requests/StorePengaduanRequest.php`
- `app/Http/Requests/StoreTanggapanRequest.php`
- `app/Http/Requests/StorePetugasRequest.php`

### Middleware (Dibuat/Modifikasi)

- `app/Http/Middleware/AdminLevel.php` - BARU
- `app/Http/Middleware/PetugasActive.php` - BARU
- `app/Http/Kernel.php` - MODIFIKASI

### Models (Modifikasi)

- `app/Models/Masyarakat.php` - Tambah trait Auditable
- `app/Models/Petugas.php` - Tambah trait Auditable
- `app/Models/Pengaduan.php` - Tambah trait Auditable
- `app/Models/Tanggapan.php` - Tambah trait Auditable
- `app/Models/AuditLog.php` - BARU

### Traits (Dibuat)

- `app/Traits/Auditable.php`

### Routes (Modifikasi)

- `routes/web.php` - Tambah admin routes dan petugas routes

### Exceptions (Modifikasi)

- `app/Exceptions/Handler.php` - Tambah custom exception handling

### Migrations (Dibuat)

- `database/migrations/2025_12_03_000001_create_audit_logs_table.php`

---

## Testing Checklist

### Masyarakat CRUD

- [ ] Login sebagai masyarakat
- [ ] Buat pengaduan baru
- [ ] Lihat daftar pengaduan
- [ ] Lihat detail pengaduan
- [ ] Edit pengaduan
- [ ] Hapus pengaduan
- [ ] Lihat tanggapan dari petugas

### Petugas CRUD

- [ ] Login sebagai petugas
- [ ] Lihat daftar pengaduan
- [ ] Lihat detail pengaduan
- [ ] Tambah tanggapan
- [ ] Update status pengaduan
- [ ] Edit profil sendiri
- [ ] Lihat laporan tanggapan

### Admin CRUD

- [ ] Login sebagai admin
- [ ] Lihat dashboard dengan statistik
- [ ] CRUD masyarakat
- [ ] CRUD petugas
- [ ] CRUD pengaduan
- [ ] CRUD tanggapan
- [ ] Middleware admin level berfungsi

### Validasi & Error Handling

- [ ] Validasi form berfungsi
- [ ] Error message ditampilkan dengan benar
- [ ] 404 error handled dengan baik
- [ ] Permission denied handled dengan baik

### Audit Logging

- [ ] Setiap CREATE tercatat di audit_logs
- [ ] Setiap UPDATE tercatat dengan old/new values
- [ ] Setiap DELETE tercatat

---

## Catatan Penting

1. **Database Migration**: Jalankan `php artisan migrate` untuk membuat tabel audit_logs
2. **File Storage**: Pastikan folder `storage/app/public/pengaduan` dapat ditulis
3. **Symlink**: Jalankan `php artisan storage:link` untuk public storage
4. **Email Domain**: Konfigurasi email di `.env` jika ingin mengirim notifikasi
5. **Timezone**: Pastikan timezone di `config/app.php` sesuai (Asia/Jakarta untuk Indonesia)
