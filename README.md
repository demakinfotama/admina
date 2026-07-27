# Admina

PHP Native MVC Admin Panel — aman, ringan, dan ramah shared hosting (cPanel).

## Fitur
- MVC murni tanpa framework berat
- Keamanan: CSRF protection, password bcrypt, session hardening, PDO prepared statements
- Export/Import Excel via **PhpSpreadsheet**
- Generate PDF via **Dompdf**
- Template UI menggunakan **Tabler Bootstrap 5**
- Mudah di-deploy di shared hosting cPanel

## Instalasi

```bash
git clone https://github.com/demakinfotama/admina.git
cd admina
composer install
cp .env.example .env
# Edit .env sesuai konfigurasi database Anda
```

## Struktur Folder

```
admina/
├── app/
│   ├── Controllers/
│   ├── Core/          # Router, Controller, Model, DB, Security, Session
│   ├── Models/
│   └── Views/
├── config/            # routes.php, app.php
├── database/migrations/
├── public/assets/
├── storage/           # logs, cache, sessions (writable)
├── vendor/            # Composer (gitignore)
├── .env.example
├── .htaccess
├── composer.json
└── index.php
```

## Keamanan
- `.htaccess` memblokir akses langsung ke folder `app/`, `config/`, `storage/`, `vendor/`
- CSRF token di setiap form POST
- Session cookie: httponly, samesite=Strict, secure (production)
- Password di-hash dengan bcrypt cost 12
- Semua query pakai PDO prepared statements
- Error tidak ditampilkan ke browser, dicatat di `storage/logs/`

## Shared Hosting (cPanel)
1. Upload semua file via File Manager atau FTP
2. Jalankan `composer install` via SSH terminal cPanel
3. Pastikan folder `storage/` writeable: `chmod -R 755 storage/`
4. Buat database di cPanel → phpMyAdmin → import migration SQL
5. Salin `.env.example` → `.env`, isi konfigurasi
