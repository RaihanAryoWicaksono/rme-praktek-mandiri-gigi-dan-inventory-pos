# Use Case — Dental Medical Inventory POS
**Aplikasi:** drg. Moh Ariv Widodo Dental Clinic  
**Teknologi:** Laravel 11  
**Tanggal:** 29 Mei 2026

---

## Aktor

| Aktor | Deskripsi |
|---|---|
| **Pengunjung / Pasien** | Pengguna umum yang mengakses halaman publik (landing page) |
| **Admin / Staf Klinik** | Pengguna yang sudah login, memiliki akses penuh ke semua fitur sistem |

---

## Daftar Use Case

### UC-00 · Autentikasi

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-01 | Login | Admin |
| UC-02 | Logout | Admin |
| UC-03 | Lupa / Reset Password | Admin |
| UC-04 | Mengelola Profil Akun | Admin |

---

### UC-10 · Halaman Publik

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-11 | Melihat Halaman Landing Page | Pengunjung |
| UC-12 | Memantau Antrian Real-Time | Pengunjung |
| UC-13 | Mengaktifkan Notifikasi Suara | Pengunjung |

---

### UC-20 · Dashboard

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-21 | Melihat Dashboard | Admin |

---

### UC-30 · Manajemen Pasien

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-31 | Mengelola Data Pasien | Admin |

---

### UC-40 · Kunjungan (Antrian)

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-41 | Mengelola Kunjungan | Admin |
| UC-42 | Mengubah Status Kunjungan | Admin |

---

### UC-50 · Rekam Medis

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-51 | Mengelola Rekam Medis | Admin |
| UC-52 | Mengisi Data Odontogram | Admin |

---

### UC-70 · Master Tindakan & BOM

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-71 | Mengelola Data Tindakan | Admin |
| UC-72 | Mengelola BOM Template | Admin |

---

### UC-90 · Inventori & Stok

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-91 | Mengelola Data Item | Admin |
| UC-92 | Mencatat Penerimaan Stok | Admin |
| UC-93 | Melakukan Koreksi Stok | Admin |

---

### UC-110 · POS & Transaksi

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-111 | Memproses Transaksi POS | Admin |
| UC-112 | Mengelola Transaksi | Admin |
| UC-113 | Mencetak Struk | Admin |
| UC-114 | Membatalkan (Void) Transaksi | Admin |

---

### UC-130 · Laporan

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-131 | Melihat Laporan Pendapatan | Admin |
| UC-132 | Melihat Laporan Stok | Admin |
| UC-133 | Mengekspor Laporan (CSV / PDF) | Admin |

---

### UC-140 · Pengaturan

| Kode | Nama Use Case | Aktor |
|---|---|---|
| UC-141 | Mengelola Pengaturan Klinik | Admin |

---

## Detail Use Case Utama

---

### UC-41 · Mengelola Kunjungan
**Aktor:** Admin  
**Cakupan:** Tambah, lihat daftar, lihat detail, filter (tanggal/status/nama), edit, hapus kunjungan

---

### UC-42 · Mengubah Status Kunjungan
**Aktor:** Admin  
**Precondition:** Kunjungan sudah terdaftar

| Status | Keterangan | Efek ke Landing Page |
|---|---|---|
| `antrian` | Pasien menunggu | — |
| `sedang_diperiksa` | Pasien dipanggil | Popup biru + suara panggil |
| `selesai` | Pemeriksaan selesai | Popup hijau + suara selesai |

---

### UC-51 · Mengelola Rekam Medis
**Aktor:** Admin  
**Cakupan:** Buat rekam medis baru (diagnosis, tindakan, resep, catatan, foto), lihat, edit, hapus  
**Include:** Saat rekam medis dibuat → status kunjungan otomatis berubah ke *selesai* (UC-42)

---

### UC-111 · Memproses Transaksi POS
**Aktor:** Admin

| # | Alur |
|---|---|
| 1 | Pilih pasien; sistem opsional memuat tindakan dari rekam medis terakhir |
| 2 | Tambah tindakan dan/atau item/obat |
| 3 | Pilih metode bayar (Cash / QRIS), masukkan nominal |
| 4 | Sistem simpan transaksi, potong stok FIFO, catat mutasi stok |

---

### UC-92 · Mencatat Penerimaan Stok
**Aktor:** Admin  
**Include:** Sistem otomatis mencatat pengeluaran (Expense) saat stok masuk

---

### UC-114 · Membatalkan (Void) Transaksi
**Aktor:** Admin  
**Include:** Semua mutasi stok dikembalikan ke batch asal (UC-93)

---

## Ringkasan

| Kelompok | Jumlah UC |
|---|---|
| Autentikasi | 4 |
| Halaman Publik | 3 |
| Dashboard | 1 |
| Manajemen Pasien | 1 |
| Kunjungan | 2 |
| Rekam Medis | 2 |
| Master Tindakan & BOM | 2 |
| Inventori & Stok | 3 |
| POS & Transaksi | 4 |
| Laporan | 3 |
| Pengaturan | 1 |
| **Total** | **26** |
