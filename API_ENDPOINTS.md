# API Endpoints Summary

## Base URL

- Development: `http://localhost:8000`
- Routes: `routes/web.php`

## Authentication Routes

### Login

- **GET** `/auth/login` - Tampilkan form login
- **POST** `/auth/login` - Proses login
  - Parameters: `username`, `password`
  - Redirect: `/masyarakat/dashboard` atau `/petugas/dashboard`

### Register Masyarakat

- **GET** `/auth/register-masyarakat` - Tampilkan form register
- **POST** `/auth/register-masyarakat` - Proses registrasi
  - Parameters: `nik`, `nama`, `alamat`, `no_hp`, `username`, `email`, `password`, `password_confirmation`

### Logout

- **POST** `/auth/logout` - Logout user
  - Redirect: `/auth/login`

---

## Masyarakat Routes

### Dashboard

- **GET** `/masyarakat/dashboard` - Tampilkan dashboard masyarakat
  - Auth: `auth:web`

### Pengaduan Management

- **GET** `/masyarakat/pengaduan` - Daftar pengaduan milik user
- **GET** `/masyarakat/pengaduan/create` - Form buat pengaduan
- **POST** `/masyarakat/pengaduan` - Simpan pengaduan baru
  - Parameters: `isi_laporan`, `foto` (optional)
- **GET** `/masyarakat/pengaduan/{id}` - Lihat detail pengaduan
- **GET** `/masyarakat/pengaduan/{id}/edit` - Form edit pengaduan
- **PUT/PATCH** `/masyarakat/pengaduan/{id}` - Simpan perubahan pengaduan
  - Parameters: `isi_laporan`, `foto` (optional)
- **DELETE** `/masyarakat/pengaduan/{id}` - Hapus pengaduan
- **Auth**: `auth:web`

---

## Petugas Routes

### Dashboard

- **GET** `/petugas/dashboard` - Tampilkan daftar pengaduan dengan statistik
  - Auth: `auth:petugas`

### Pengaduan Management

- **GET** `/petugas/pengaduan` - Daftar semua pengaduan
- **GET** `/petugas/pengaduan/show/{id}` - Lihat detail pengaduan
- **PATCH** `/petugas/pengaduan/{id}/status` - Update status pengaduan
  - Parameters: `status` (menunggu|proses|selesai)

### Tanggapan Management

- **POST** `/petugas/tanggapan/{pengaduan}` - Tambah tanggapan untuk pengaduan
  - Parameters: `isi_tanggapan`

### Profile Management

- **GET** `/petugas/profile` - Lihat profil petugas
- **GET** `/petugas/profile/edit` - Form edit profil
- **PUT** `/petugas/profile` - Simpan perubahan profil
  - Parameters: `nama`, `email`, `username`, `password_lama` (optional), `password_baru` (optional)

### Laporan

- **GET** `/petugas/laporan` - Lihat laporan tanggapan petugas
- **Auth**: `auth:petugas`

---

## Admin Routes

### Dashboard

- **GET** `/admin/dashboard` - Dashboard dengan statistik lengkap
  - Auth: `auth:petugas` + AdminLevel middleware

### Masyarakat Management

- **GET** `/admin/masyarakat` - Daftar masyarakat
- **GET** `/admin/masyarakat/create` - Form tambah masyarakat
- **POST** `/admin/masyarakat` - Simpan masyarakat baru
  - Parameters: `nik`, `nama`, `alamat`, `no_hp`, `username`, `email`, `password`
- **GET** `/admin/masyarakat/{id}` - Lihat detail masyarakat + pengaduannya
- **GET** `/admin/masyarakat/{id}/edit` - Form edit masyarakat
- **PUT** `/admin/masyarakat/{id}` - Simpan perubahan masyarakat
  - Parameters: `nik`, `nama`, `alamat`, `no_hp`, `username`, `email`, `password` (optional)
- **DELETE** `/admin/masyarakat/{id}` - Hapus masyarakat + data terkait

### Petugas Management

- **GET** `/admin/petugas` - Daftar petugas
- **GET** `/admin/petugas/create` - Form tambah petugas
- **POST** `/admin/petugas` - Simpan petugas baru
  - Parameters: `nama`, `email`, `username`, `password`, `level`, `status`
- **GET** `/admin/petugas/{id}` - Lihat detail petugas + tanggapannya
- **GET** `/admin/petugas/{id}/edit` - Form edit petugas
- **PUT** `/admin/petugas/{id}` - Simpan perubahan petugas
  - Parameters: `nama`, `email`, `username`, `level`, `status`, `password` (optional)
- **DELETE** `/admin/petugas/{id}` - Hapus petugas

### Pengaduan Management

- **GET** `/admin/pengaduan` - Daftar semua pengaduan
- **GET** `/admin/pengaduan/{id}` - Lihat detail pengaduan
- **GET** `/admin/pengaduan/{id}/edit` - Form edit pengaduan
- **PUT** `/admin/pengaduan/{id}` - Simpan perubahan pengaduan
  - Parameters: `isi_laporan`, `status`, `foto` (optional)
- **DELETE** `/admin/pengaduan/{id}` - Hapus pengaduan

### Tanggapan Management

- **GET** `/admin/tanggapan` - Daftar tanggapan
- **GET** `/admin/tanggapan/{id}` - Lihat detail tanggapan
- **GET** `/admin/tanggapan/{id}/edit` - Form edit tanggapan
- **PUT** `/admin/tanggapan/{id}` - Simpan perubahan tanggapan
  - Parameters: `isi_tanggapan`
- **DELETE** `/admin/tanggapan/{id}` - Hapus tanggapan
- **Auth**: `auth:petugas` + AdminLevel middleware

---

## Status Codes

| Code    | Description                           |
| ------- | ------------------------------------- |
| 200     | OK - Request berhasil                 |
| 301/302 | Redirect                              |
| 400     | Bad Request - Validasi gagal          |
| 401     | Unauthorized - Belum login            |
| 403     | Forbidden - Tidak ada akses           |
| 404     | Not Found - Data tidak ditemukan      |
| 422     | Unprocessable Entity - Validasi error |
| 500     | Internal Server Error                 |

---

## Headers Required

### Standard Headers

\`\`\`
Content-Type: application/x-www-form-urlencoded
Accept: text/html,application/xhtml+xml
\`\`\`

### For File Upload

\`\`\`
Content-Type: multipart/form-data
\`\`\`

---

## Error Response Format

### Validation Error

\`\`\`json
{
"message": "Validasi gagal.",
"errors": {
"email": ["Email sudah terdaftar."],
"password": ["Password harus mengandung huruf besar, huruf kecil, dan angka."]
},
"status": 422
}
\`\`\`

### Not Found

\`\`\`json
{
"message": "Data tidak ditemukan.",
"status": 404
}
\`\`\`

### Success

\`\`\`json
{
"message": "Masyarakat berhasil ditambahkan!",
"data": { ... },
"status": "success"
}
\`\`\`

---

## Parameter Validation Rules

### Masyarakat

| Field    | Rule                   | Example                  |
| -------- | ---------------------- | ------------------------ |
| nik      | digits:16, unique      | 1234567890123456         |
| nama     | string, max:100        | John Doe                 |
| alamat   | string, max:500        | Jl. Merdeka No. 1        |
| no_hp    | regex, max:15          | 0812345678 or +628123456 |
| username | string, max:50, unique | john_doe                 |
| email    | email, unique          | john@example.com         |
| password | min:8, regex           | Ab1234567                |

### Pengaduan

| Field       | Rule                       | Example            |
| ----------- | -------------------------- | ------------------ |
| isi_laporan | string, min:20, max:2000   | Laporan tentang... |
| foto        | image, max:2048KB          | file.jpg           |
| status      | in:menunggu,proses,selesai | menunggu           |

### Tanggapan

| Field         | Rule                     | Example                      |
| ------------- | ------------------------ | ---------------------------- |
| isi_tanggapan | string, min:10, max:2000 | Terima kasih atas laporan... |

### Petugas

| Field    | Rule                   | Example          |
| -------- | ---------------------- | ---------------- |
| nama     | string, max:100        | Budi             |
| email    | email, unique          | budi@example.com |
| username | string, max:50, unique | budi             |
| password | min:8, regex           | Budi1234         |
| level    | in:admin,petugas       | admin            |
| status   | in:aktif,nonaktif      | aktif            |
