# 🚗 AURO MOTORS — Website Showroom Mobil Premium

Website showroom mobil lengkap dengan tema mewah (gold/hitam/putih), frontend statis (HTML/CSS/JS) yang kompatibel dengan **GitHub Pages**, dan backend **PHP + MySQL**.

## 📁 Struktur Folder
```
showroom/
├── index.html              # Landing page
├── katalog.html            # Katalog mobil + filter
├── detail.html             # Detail mobil + tombol WA
├── login.html              # Login admin
├── dashboard/              # Halaman manajemen (CRUD)
│   ├── index.html          # Ringkasan + grafik
│   ├── mobil.html
│   ├── pelanggan.html
│   ├── transaksi.html
│   ├── pembayaran.html
│   ├── stok.html
│   ├── sales.html
│   └── supplier.html
├── assets/
│   ├── css/  (style.css, dashboard.css)
│   └── js/   (main.js, api.js, dashboard.js)
├── api/      (PHP REST API endpoints)
└── database.sql            # Skema + data dummy
```

## 🚀 Cara Pakai

### 1. Frontend (GitHub Pages)
- Upload semua file (kecuali folder `api/`) ke repo GitHub
- Enable GitHub Pages dari Settings → Pages
- Frontend akan jalan dengan **data dummy** jika API belum disetting

### 2. Database
- Import `database.sql` ke MySQL (phpMyAdmin / CLI):
```bash
mysql -u root -p < database.sql
```

### 3. Backend PHP
- Upload folder `api/` ke hosting PHP (InfinityFree, 000webhost, hosting cPanel)
- Edit `api/config.php` sesuaikan kredensial DB
- Edit `assets/js/api.js`, ganti `API_BASE` ke URL hosting PHP Anda

### 4. Login Demo
- **Username:** `admin`
- **Password:** `admin123`
- (Bekerja sebagai fallback offline; untuk production, hash password dengan `password_hash()` PHP)

## 📱 WhatsApp
Edit nomor di `assets/js/main.js`:
```js
const WA_NUMBER = '6282298028685'; // ganti nomor showroom Anda
```

## 🎨 Tema Warna
- Gold: `#FFD700` / `#B8860B`
- Hitam: `#0A0A0A`
- Putih: `#FFFFFF`
- Font: Playfair Display (heading) + Inter (body)

## 🔐 Keamanan
- ✅ Semua query PHP menggunakan **prepared statements**
- ✅ Password disimpan via `password_hash()`
- ✅ CORS headers sudah di-set
- ⚠️ Untuk production, gunakan JWT untuk token & HTTPS

## 📋 Catatan
- Tabel ADMIN dummy memerlukan password yang di-hash. Generate via PHP:
  ```php
  echo password_hash('admin123', PASSWORD_DEFAULT);
  ```
  Lalu update field `password` di tabel ADMIN.
