CREATE DATABASE IF NOT EXISTS sim_barang
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sim_barang;

-- Hapus tabel lama (urutan: anak dulu, induk belakangan)
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS kategori;
DROP TABLE IF EXISTS users;

-- ===== DDL =====
CREATE TABLE users (
  id_user       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,
  nama_lengkap  VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE kategori (
  id_kategori    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama_kategori  VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE barang (
  id_barang    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  kode_barang  VARCHAR(20)  NOT NULL UNIQUE,
  nama_barang  VARCHAR(100) NOT NULL,
  id_kategori  INT UNSIGNED NOT NULL,
  jumlah       INT UNSIGNED NOT NULL,
  harga        DECIMAL(15,2) NOT NULL DEFAULT 0,
  kondisi      ENUM('baik','rusak') NOT NULL,
  id_user      INT UNSIGNED NULL,
  CONSTRAINT fk_barang_kategori FOREIGN KEY (id_kategori)
    REFERENCES kategori(id_kategori) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT fk_barang_user FOREIGN KEY (id_user)
    REFERENCES users(id_user) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- ===== DML (data awal) =====
INSERT INTO users (username, password, nama_lengkap) VALUES
  ('admin', '$2y$10$DOPSG7Ku2hhdQOwPSxMpQO3L3Na.gH9LRJrgCcHPo02Pd7GQOVuCu', 'Administrator');

INSERT INTO kategori (nama_kategori) VALUES
  ('Elektronik'), ('Furnitur'), ('Alat Tulis');

INSERT INTO barang (kode_barang, nama_barang, id_kategori, jumlah, harga, kondisi, id_user) VALUES
  ('BRG-001', 'Proyektor',   1, 3,  5000000, 'baik',  1),
  ('BRG-002', 'Meja Lab',    2, 20, 750000,  'baik',  1),
  ('BRG-003', 'Kursi Putar', 2, 15, 450000,  'rusak', 1);