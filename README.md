# User Activity Dashboard

Aplikasi dashboard analitik sederhana untuk mencatat dan menganalisis aktivitas user.
Project ini dibuat menggunakan Laravel, Blade, dan MySQL, dengan fokus pada struktur kode yang rapi, 
efisiensi query database, dan integrasi backend–frontend.

---

## Setup

### 1. Clone Repository
```bash
git clone https://github.com/r-zaa/user-activity-dashboard.git
cd user-activity-dashboard
```
### 2. Install Dependencies
```bash
composer install
```
### 3. Setup Environment 
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Atur konfigurasi database di .env
```bash
DB_DATABASE=user_activity
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Jalankan migration & seeder
```bash
php artisan migrate:fresh --seed
```

### 6. Jalankan Tailwind
```bash
npm run dev
```

### 7. Jalankan aplikasi
```bash
php artisan serve
```




