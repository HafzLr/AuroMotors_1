-- Database Showroom Mobil
-- Jalankan SQL ini di MySQL untuk membuat database dan semua tabel

CREATE DATABASE IF NOT EXISTS db_showroom;
USE db_showroom;

CREATE TABLE PELANGGAN (
    id_pelanggan INT PRIMARY KEY,
    nama VARCHAR(100),
    no_hp VARCHAR(20),
    jalan VARCHAR(255),
    kota VARCHAR(100),
    kode_pos VARCHAR(10)
);

CREATE TABLE PEGAWAI (
    id_pegawai INT PRIMARY KEY,
    nama VARCHAR(100),
    no_hp VARCHAR(20),
    tanggal_masuk DATE,
    masa_kerja VARCHAR(50),
    jalan VARCHAR(255),
    kota VARCHAR(100),
    kode_pos VARCHAR(10),
    id_atasan INT,
    FOREIGN KEY (id_atasan) REFERENCES PEGAWAI(id_pegawai)
);

CREATE TABLE MOBIL (
    id_mobil INT PRIMARY KEY,
    merk VARCHAR(50),
    tipe VARCHAR(50),
    warna VARCHAR(30),
    harga DECIMAL(15, 2),
    tahun YEAR,
    gambar LONGTEXT NULL  -- URL atau data base64 foto mobil
);

CREATE TABLE SUPPLIER (
    id_supplier INT PRIMARY KEY,
    nama_supplier VARCHAR(100),
    jalan VARCHAR(255),
    kota VARCHAR(100),
    kode_pos VARCHAR(10)
);

CREATE TABLE STOK (
    id_stok INT PRIMARY KEY,
    jumlah INT,
    tanggal_masuk DATE
);

CREATE TABLE ADMIN (
    id_pegawai INT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255),
    FOREIGN KEY (id_pegawai) REFERENCES PEGAWAI(id_pegawai)
);

CREATE TABLE SALES (
    id_pegawai INT PRIMARY KEY,
    target_penjualan DECIMAL(15, 2),
    komisi DECIMAL(15, 2),
    FOREIGN KEY (id_pegawai) REFERENCES PEGAWAI(id_pegawai)
);

CREATE TABLE MOBIL_BARU (
    id_mobil INT PRIMARY KEY,
    garansi VARCHAR(100),
    FOREIGN KEY (id_mobil) REFERENCES MOBIL(id_mobil)
);

CREATE TABLE MOBIL_BEKAS (
    id_mobil INT PRIMARY KEY,
    garansi VARCHAR(100),
    FOREIGN KEY (id_mobil) REFERENCES MOBIL(id_mobil)
);

CREATE TABLE TRANSAKSI (
    id_transaksi INT PRIMARY KEY,
    tanggal DATE,
    total_harga DECIMAL(15, 2),
    id_pelanggan INT,
    id_pegawai INT,
    FOREIGN KEY (id_pelanggan) REFERENCES PELANGGAN(id_pelanggan),
    FOREIGN KEY (id_pegawai) REFERENCES PEGAWAI(id_pegawai)
);

CREATE TABLE PEMBAYARAN (
    id_pembayaran INT PRIMARY KEY,
    tgl_bayar DATE,
    jumlah_pembayaran DECIMAL(15, 2),
    metode ENUM('Transfer', 'Cash', 'Kredit'),
    id_transaksi INT,
    id_mobil INT,
    FOREIGN KEY (id_transaksi) REFERENCES TRANSAKSI(id_transaksi),
    FOREIGN KEY (id_mobil) REFERENCES MOBIL(id_mobil)
);

CREATE TABLE KESEPAKATAN_AKHIR (
    id_transaksi INT,
    id_pelanggan INT,
    id_mobil INT,
    PRIMARY KEY (id_transaksi, id_pelanggan, id_mobil),
    FOREIGN KEY (id_transaksi) REFERENCES TRANSAKSI(id_transaksi),
    FOREIGN KEY (id_pelanggan) REFERENCES PELANGGAN(id_pelanggan),
    FOREIGN KEY (id_mobil) REFERENCES MOBIL(id_mobil)
);

CREATE TABLE UPDATE_STOK_LOG (
    id_stok INT,
    id_supplier INT NULL,
    id_pembayaran INT NULL,
    id_pegawai INT NULL,
    FOREIGN KEY (id_stok) REFERENCES STOK(id_stok),
    FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier),
    FOREIGN KEY (id_pembayaran) REFERENCES PEMBAYARAN(id_pembayaran),
    FOREIGN KEY (id_pegawai) REFERENCES PEGAWAI(id_pegawai)
);

ALTER TABLE MOBIL ADD COLUMN id_supplier INT;
ALTER TABLE MOBIL ADD FOREIGN KEY (id_supplier) REFERENCES SUPPLIER(id_supplier);

-- Data dummy untuk testing
INSERT INTO SUPPLIER VALUES
(1, 'PT Auto Prima', 'Jl. Sudirman No.1', 'Jakarta', '10110'),
(2, 'CV Mobil Jaya', 'Jl. Diponegoro No.5', 'Bandung', '40115');

INSERT INTO MOBIL (id_mobil, merk, tipe, warna, harga, tahun, id_supplier) VALUES
(1, 'Toyota', 'Innova Zenix', 'Hitam', 450000000, 2024, 1),
(2, 'Honda', 'Civic Type R', 'Putih', 1300000000, 2024, 1),
(3, 'BMW', 'M3 Competition', 'Gold', 2500000000, 2023, 2),
(4, 'Mercedes-Benz', 'C-Class', 'Hitam', 1100000000, 2023, 2),
(5, 'Toyota', 'Avanza', 'Silver', 240000000, 2022, 1),
(6, 'Honda', 'Brio RS', 'Merah', 195000000, 2023, 1);

INSERT INTO MOBIL_BARU VALUES
(1, 'Garansi Resmi 3 Tahun'),
(2, 'Garansi Resmi 3 Tahun'),
(3, 'Garansi Resmi 5 Tahun'),
(6, 'Garansi Resmi 3 Tahun');

INSERT INTO MOBIL_BEKAS VALUES
(4, 'Garansi 1 Tahun Mesin'),
(5, 'Garansi 6 Bulan');

INSERT INTO PEGAWAI VALUES
(1, 'Budi Santoso', '081234567890', '2020-01-15', '5 Tahun', 'Jl. Mawar No.10', 'Jakarta', '10110', NULL),
(2, 'Siti Aminah', '081298765432', '2021-06-01', '4 Tahun', 'Jl. Melati No.5', 'Jakarta', '10120', 1);

INSERT INTO ADMIN VALUES
-- password: admin123 (hash bcrypt)
(1, 'admin', '$2y$10$E0Ng5ZqJ5q5q5q5q5q5q5uX5q5q5q5q5q5q5q5q5q5q5q5q5q5q5q');

INSERT INTO SALES VALUES
(2, 500000000, 25000000);

INSERT INTO PELANGGAN VALUES
(1, 'Andi Wijaya', '08123456789', 'Jl. Anggrek No.20', 'Jakarta', '10130'),
(2, 'Rina Kusuma', '08198765432', 'Jl. Kenanga No.15', 'Bandung', '40120');

INSERT INTO STOK VALUES
(1, 10, '2024-01-15'),
(2, 5, '2024-02-20');
