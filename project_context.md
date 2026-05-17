Product Requirement Document (PRD): Sistem POS & Inventory Klinik Gigi

1. Ikhtisar Proyek
   Sistem ini adalah aplikasi berbasis web yang dirancang untuk mengelola transaksi operasional klinik, mengontrol stok bahan/obat habis pakai, serta mencetak struk dan laporan. Sistem ini dirancang untuk mempercepat proses checkout melalui penggunaan Template BOM (Bill of Materials) Tindakan, di mana pemakaian bahan dapat terpotong otomatis dari stok inventaris.

Penting: Sistem ini difokuskan pada POS dan Inventaris. Modul Rekam Medis (RM) atau riwayat tindakan klinis tidak termasuk dalam cakupan sistem ini (akan dikembangkan terpisah).

2. Spesifikasi Teknis & Arsitektur
   Framework: Laravel 12 (Arsitektur Monolitik, tidak perlu membuat API internal).

Database: MySQL.

Frontend/Styling: Tailwind CSS. Pendekatan desain menggunakan prinsip mobile-first, mengutamakan estetika Clean UI dan Glassmorphism untuk antarmuka yang modern dan bersih.

Aktor/Role: Hanya ada 1 Aktor (Single User), yaitu Dokter/Owner. Cukup gunakan skema migration user bawaan Laravel yang disederhanakan.

3. Daftar Modul & Fitur MVP
   A. Dashboard
   Ringkasan kondisi klinik hari ini.

Total transaksi dan pendapatan hari ini.

Indikator stok: jumlah item menipis, mendekati expired, dan sudah expired.

Shortcut: Transaksi baru, Stok, Laporan, Template tindakan.

B. Master Data
Master Tindakan: Nama, kategori, tarif dasar, status aktif/nonaktif.

Master Bahan & Obat:

Nama item, jenis (bahan/obat/produk).

Satuan pakai (pcs, ml, gram) & Satuan beli (box, botol, pack).

Konversi satuan (misal: 1 box = 100 pcs).

Stok saat ini, batas stok minimum, tanggal expired, status.

Master Pasien: (Sangat Sederhana) Hanya migration dasar yang memuat Nama Pasien (karena modul detail dikerjakan terpisah).

C. Template BOM Tindakan (Modul Inti)
Menyimpan template standar pemakaian bahan untuk setiap tindakan guna mempercepat input transaksi.

Satu tindakan dapat memiliki lebih dari satu template (Contoh: Tambal Kecil, Tambal Besar).

Berisi daftar item, qty standar, dan satuan pakai.

D. Transaksi / POS Klinik
Halaman kasir untuk mencatat tindakan, obat, dan pemakaian bahan.

Alur: Pilih pasien -> Pilih tindakan -> Sistem memuat Template BOM otomatis -> User mengedit bahan aktual yang dipakai -> Tambah produk lain jika ada -> Simpan -> Potong stok otomatis -> Cetak struk.

Fleksibilitas: Template BOM yang muncul otomatis wajib bisa diedit qty-nya, ditambah, atau dihapus itemnya menyesuaikan kondisi real pasien sebelum transaksi disimpan.

E. Stok / Inventory
Mengontrol ketersediaan barang.

Stok Masuk: Input item, qty, satuan masuk, dan tanggal expired.

Koreksi Stok Manual: Penyesuaian stok dengan catatan alasan.

Kartu Stok / Mutasi: Riwayat barang masuk, keluar (karena transaksi), dan koreksi.

Monitoring: Peringatan stok minimum, mendekati expired, dan daftar expired.

F. Cetak Struk
Bukti transaksi pasien.

Format struk sederhana menampilkan: Nama klinik, tanggal, nama pasien, daftar tindakan, daftar obat tambahan, total biaya.

Dapat dicetak langsung setelah transaksi atau dicetak ulang dari riwayat.

G. Laporan
Pendapatan: Harian/periode beserta rincian item/tindakan.

Penggunaan Bahan: Item yang terpakai dan jumlahnya per periode.

Stok & Expired: Sisa stok, item menipis, dan status kedaluwarsa.

Fitur Export: PDF dan Excel.

H. Pengaturan Sederhana
Profil Klinik (Nama, alamat, kontak, footer struk).

Konfigurasi batas notifikasi (misal: pengingat expired H-30, batas stok minimum default).

4. Aturan Bisnis (Business Logic)
   Satu tindakan boleh memiliki satu atau lebih template BOM.

Template BOM hanya sebagai standar awal saat transaksi, bukan hasil final wajib. Isi BOM bebas diedit di halaman kasir.

Stok di-deduksi (dipotong) berdasarkan pemakaian final di halaman transaksi, bukan dari hitungan template awal.

Hanya bahan/obat yang terdaftar sebagai item stok yang akan mengurangi inventaris.

Item yang sudah expired tetap tercatat di sistem dan tidak hilang, agar bisa dipantau di menu laporan.

Setiap perubahan stok manual (koreksi) wajib menyimpan log alasan perubahan.

Transaksi yang sudah berstatus final otomatis akan meng-generate struk dan memicu mutasi stok keluar.

5. Fitur yang Ditunda (Out of Scope untuk MVP)
   Mohon JANGAN membuat fitur berikut dalam iterasi ini:
   Multi-user, Multi-cabang, Pembelian/Hutang Supplier lengkap, Role Management, Margin/HPP, Rekam Medis (RM), Riwayat klinis pasien, Appointment, Notifikasi WhatsApp, dan Mode Offline.
