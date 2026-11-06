CREATE TABLE pengumuman (
  pengumuman_id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NOT NULL,
  judul VARCHAR(255) NOT NULL,
  isi TEXT NOT NULL,
  kategori ENUM('pengumuman', 'edukasi') NOT NULL DEFAULT 'pengumuman',
  gambar VARCHAR(255) NULL,
  tanggal_publikasi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status_publikasi ENUM('draft', 'published') NOT NULL DEFAULT 'published',
  created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_pengumuman_kategori (kategori),
  INDEX idx_pengumuman_status (status_publikasi)
);

