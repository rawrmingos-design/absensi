# Sistem Absensi Karyawan Archemi

Sistem manajemen absensi karyawan yang dibangun dengan Laravel 10 dan Blade templating engine untuk perusahaan Archemi.

## 🚀 Fitur Utama

### 👥 Manajemen Karyawan
- CRUD data karyawan lengkap
- Upload foto profil
- Manajemen departemen
- Role-based access control

### ⏰ Sistem Absensi
- Clock in/out real-time
- Input absensi manual (Admin/HR)
- Tracking jam kerja otomatis
- Status kehadiran (Hadir, Terlambat, Sakit, Izin, dll)
- Lokasi absensi

### 🏖️ Manajemen Cuti
- Pengajuan cuti online
- Sistem approval (Admin/HR)
- Multiple jenis cuti (Tahunan, Sakit, Melahirkan, dll)
- Upload dokumen pendukung
- Tracking status pengajuan

### 📊 Dashboard & Laporan
- Statistik real-time
- Overview kehadiran harian
- Pengajuan cuti pending
- Quick actions

## 🛠️ Teknologi

- **Framework**: Laravel 10
- **Frontend**: Blade Templates + Bootstrap 5
- **Database**: MySQL/SQLite
- **Icons**: Bootstrap Icons
- **Authentication**: Laravel UI

## 📦 Instalasi

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/SQLite

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd absensi
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=absensi_archemi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Assets**
   ```bash
   npm run dev
   ```

7. **Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Run Server**
   ```bash
   php artisan serve
   ```

## 👤 Default Users

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| Admin | admin@archemi.com | admin123 | Full access |
| HR | hr@archemi.com | hr123 | Employee & Leave management |
| Employee | john@archemi.com | employee123 | Personal attendance & leave |

## 🏗️ Struktur Database

### Tables
- `users` - Data pengguna sistem
- `departments` - Data departemen
- `employees` - Data karyawan
- `attendances` - Data absensi
- `leaves` - Data pengajuan cuti

### Relationships
- User belongsTo Employee
- Employee belongsTo Department
- Employee hasMany Attendances
- Employee hasMany Leaves
- Department hasMany Employees

## 🎯 Penggunaan

### Untuk Admin/HR:
1. **Manajemen Karyawan**: Tambah, edit, hapus data karyawan
2. **Manajemen Departemen**: Kelola struktur departemen
3. **Input Absensi Manual**: Input absensi untuk karyawan
4. **Approval Cuti**: Setujui/tolak pengajuan cuti
5. **Monitoring**: Pantau kehadiran dan statistik

### Untuk Karyawan:
1. **Clock In/Out**: Absen masuk dan keluar
2. **Pengajuan Cuti**: Ajukan berbagai jenis cuti
3. **Riwayat**: Lihat riwayat absensi dan cuti
4. **Dashboard**: Overview personal

## 🔧 Konfigurasi

### Jam Kerja Standard
Default jam kerja adalah 08:00. Dapat diubah di:
- `app/Models/Attendance.php` method `getIsLateAttribute()`
- `app/Http/Controllers/AttendanceController.php` method `clockIn()`

### Upload Limits
- Foto profil: max 2MB (JPG, PNG, GIF)
- Dokumen cuti: max 2MB (PDF, JPG, PNG)

## 🚀 Deployment

### Production Setup
1. Set `APP_ENV=production` di `.env`
2. Set `APP_DEBUG=false`
3. Configure proper database
4. Run `php artisan config:cache`
5. Run `php artisan route:cache`
6. Run `php artisan view:cache`
7. Set proper file permissions

### Web Server Configuration
Pastikan document root mengarah ke folder `public/`

## 📱 Screenshots

### Dashboard
- Overview statistik kehadiran
- Quick actions untuk clock in/out
- Recent activities

### Employee Management
- Daftar karyawan dengan filter
- Form tambah/edit karyawan
- Upload foto profil

### Attendance System
- Clock in/out interface
- Manual attendance input
- Attendance history

### Leave Management
- Leave application form
- Approval workflow
- Leave history and status

## 🤝 Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License.

## 📞 Support

Untuk pertanyaan dan dukungan, silakan hubungi tim development.

---

**Archemi Attendance System** - Built with ❤️ using Laravel
