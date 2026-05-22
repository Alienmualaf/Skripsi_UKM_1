# Deskripsi Peran dan Tanggung Jawab Aktor (Akses Kontrol) dalam Sistem Informasi Manajemen UKM

Dokumen ini menjelaskan secara analitis dan terperinci mengenai klasifikasi, fungsi, serta wewenang masing-masing aktor yang berinteraksi di dalam Sistem Informasi Manajemen Unit Kegiatan Mahasiswa (UKM). Pembagian peran ini didasarkan pada prinsip *Role-Based Access Control* (RBAC) dan pemisahan fungsi (*Segregation of Duties* - SoD) guna menjamin akuntabilitas, transparansi, serta validitas data organisasi di lingkungan universitas.

Secara khusus, sistem ini menegaskan batas operasional yang jelas antara **Admin UKM** (selaku pengelola dan penginput data taktis) dengan **Pengurus UKM** (selaku validator, pengawas, dan pengambil keputusan otorisasi).

---

## 1. Administrator (Super Admin)
Administrator merupakan entitas pengguna dengan tingkat otoritas tertinggi dalam sistem (*root/global privileges*). Aktor ini memegang kendali penuh terhadap konfigurasi sistem, pemeliharaan basis data, dan pengelolaan seluruh entitas di tingkat universitas.

Tanggung jawab dan tugas Administrator meliputi:
* **Pengelolaan Entitas UKM Global**: Melakukan registrasi, pembaharuan, pembekuan, maupun penghapusan data seluruh UKM yang terdaftar secara resmi di universitas.
* **Manajemen Akun dan Kredensial Pengguna**: Mengelola siklus hidup akun pengguna, mulai dari pembuatan akun baru, pengaturan ulang kata sandi, penangguhan akun, hingga penghapusan akun untuk semua level aktor (Admin UKM, Pengurus UKM, dan Anggota).
* **Otorisasi dan Pengaturan Peran (RBAC)**: Menetapkan dan memodifikasi hak akses serta penugasan peran (*role assignment*) pengguna untuk memastikan setiap aktor bergerak dalam batas wewenang yang sah.
* **Pemantauan Aktivitas Sistem (System Audit Trail)**: Melakukan monitoring real-time terhadap kinerja server, log aktivitas transaksi data, serta melakukan audit keamanan sistem secara berkala.
* **Konsolidasi Laporan Global**: Mengakses, menganalisis, dan mengekspor seluruh laporan administratif, keuangan, dan kegiatan dari semua UKM guna kebutuhan pelaporan tingkat universitas.

---

## 2. Admin UKM (Operator / Penginput Data)
Admin UKM bertugas sebagai pelaksana teknis operasional sistem di tingkat internal organisasi UKM masing-masing. Peran utama aktor ini adalah sebagai **penginput, pemelihara, dan pengelola data operasional** (data generator & operator). Admin UKM berinteraksi langsung dengan formulir input untuk merekam segala transaksi administrasi sebelum diajukan ke tahap berikutnya.

Tanggung jawab dan tugas Admin UKM meliputi:
* **Pengelolaan Profil Organisasi**: Menginput dan memperbarui informasi identitas UKM yang meliputi narasi sejarah, visi dan misi, serta mengunggah bagan struktur organisasi.
* **Manajemen Kegiatan dan Program Kerja (Proker)**: Menyusun rancangan program kerja, merumuskan detail agenda kegiatan (judul, deskripsi, waktu, dan lokasi), serta menetapkan penugasan dalam kepanitiaan.
* **Administrasi Keanggotaan**: Merekam data anggota aktif, mengelola berkas pendaftaran calon anggota baru, serta memproses administrasi rekrutmen.
* **Pencatatan Transaksi Keuangan**: Mencatat setiap detail arus kas masuk dan keluar (seperti uang kas anggota, pengeluaran operasional, log honorarium pelatih, dll.) lengkap dengan bukti transaksi.
* **Inventarisasi Aset**: Menginput data inventaris, memperbarui status kuantitas, melacak lokasi penyimpanan, serta melaporkan kondisi fisik aset (baik, rusak, atau hilang).
* **Penyusunan Draf Laporan**: Menyusun dan mengunggah dokumen draf laporan keuangan periodik, laporan pertanggungjawaban (LPJ) program kerja, serta laporan rekrutmen untuk diajukan kepada Pengurus UKM.

### Wewenang Khusus pada UKM Paduan Suara (Padus):
* **Manajemen Pelatih**: Menginput profil pelatih, kategori keahlian, dan mengelola kehadiran (absensi) pelatih dalam sesi latihan.
* **Repositori Partitur dan Repertoar**: Mengunggah berkas partitur lagu, mengategorikan repertoar musik, serta memperbarui daftar lagu latihan.
* **Klasifikasi Suara Anggota**: Mengidentifikasi dan menginput klasifikasi jenis suara anggota (Sopran, Alto, Tenor, Bass) berdasarkan hasil audisi.
* **Manajemen Media Latihan**: Mengunggah dan membagikan materi latihan berupa berkas audio (panduan suara per bagian) dan video penampilan untuk diakses oleh anggota.

---

## 3. Pengurus UKM (Validator / Pengambil Keputusan)
Pengurus UKM (seperti Ketua UKM, Sekretaris Umum, dan Bendahara Umum) memegang peran sebagai **validator, pengawas, dan otoritas pengambil keputusan**. Sesuai dengan prinsip kepemimpinan organisasi, aktor ini **tidak melakukan penginputan data operasional** secara langsung demi mencegah terjadinya konflik kepentingan (*conflict of interest*). Sebaliknya, fokus utama mereka adalah melakukan verifikasi terhadap kualitas, akurasi, dan kepatuhan data yang diinput oleh Admin UKM.

Tanggung jawab dan tugas Pengurus UKM meliputi:
* **Persetujuan Program Kerja dan Agenda**: Meninjau rancangan proker dan agenda kegiatan yang diajukan oleh Admin UKM, serta memberikan persetujuan (*approval*) atau penolakan.
* **Validasi Laporan Pertanggungjawaban (LPJ)**: Memeriksa akurasi laporan kegiatan dan LPJ Proker, serta memvalidasi keselarasan antara rencana anggaran dengan realisasi di lapangan.
* **Otorisasi dan Audit Transaksi Keuangan**: Melakukan verifikasi terhadap keabsahan bukti transaksi kas yang diinput oleh Admin UKM serta memberikan persetujuan akhir atas pengeluaran dana UKM.
* **Pemberian Umpan Balik dan Revisi**: Memberikan catatan evaluasi, instruksi revisi, atau koreksi secara langsung di dalam sistem terhadap draf laporan atau data kegiatan yang dinilai belum valid.
* **Supervisi Rekrutmen dan Aset**: Memantau perkembangan pendaftaran anggota baru, mengawasi hasil seleksi klasifikasi suara, serta memantau mutasi dan status kelayakan inventaris UKM.

---

## 4. Anggota (Pengguna Umum / Konsumen Informasi)
Anggota merupakan entitas pengguna akhir (*end-user*) yang bertindak sebagai konsumen informasi dan peserta aktif dalam kegiatan pembelajaran atau pelatihan (*LMS participant*). Hak akses anggota dibatasi secara ketat hanya pada pembacaan informasi publik dan partisipasi kegiatan personal.

Tanggung jawab dan tugas Anggota meliputi:
* **Akses Informasi Publik**: Membaca profil UKM, mengikuti pembaruan berita/pengumuman penting, serta memantau kalender program kerja organisasi.
* **Partisipasi Kegiatan**: Mendaftar pada agenda kegiatan yang dibuka oleh pengurus serta melakukan pengisian kehadiran mandiri (*self check-in attendance*) sesuai sesi aktif.
* **Konsumsi Materi Pembelajaran (LMS)**: Mengunduh materi pelatihan yang diunggah oleh pengelola.
* **Akses Materi Khusus UKM Padus**: Membaca partitur yang dibagikan serta mengunduh/memutar audio dan video latihan suara per jenis vokal.
* **Pemantauan Status Individu**: Memeriksa status keanggotaan aktif mereka, klasifikasi divisi/suara, serta melacak riwayat partisipasi kehadiran mereka sendiri dalam kegiatan UKM.

---

## Tabel Komparasi Pemisahan Fungsi (Admin UKM vs. Pengurus UKM)

Untuk mempertegas perbedaan peran dalam operasional basis data, berikut adalah matriks perbandingan hak akses antara Admin UKM dan Pengurus UKM:

| Modul Data / Aktivitas | Admin UKM (Operator) | Pengurus UKM (Validator) |
| :--- | :---: | :---: |
| **Profil & Visi Misi UKM** | Input & Edit | Lihat & Evaluasi |
| **Pembuatan Proker / Kegiatan** | Buat & Simpan Draf | Validasi & Setujui (*Publish*) |
| **Pencatatan Kas Masuk / Keluar** | Input & Unggah Bukti | Audit & Otorisasi Transaksi |
| **Berkas Laporan Pertanggungjawaban (LPJ)**| Susun & Unggah | Tinjau, Beri Catatan & Setujui |
| **Input Data Anggota Baru** | Proses Pendaftaran | Pengawasan Hasil Seleksi |
| **Data Inventaris & Aset** | Input & Update Kondisi | Monitoring & Pengawasan |
| **Data Pelatih & Repertoar (Padus)** | Input & Upload File | Lihat & Evaluasi Pemanfaatan |
