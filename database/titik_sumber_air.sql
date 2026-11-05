CREATE TABLE titik_sumber_air (
  titik_air_id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(255) NOT NULL,
  jenis VARCHAR(255) NOT NULL,
  alamat VARCHAR(255) NOT NULL,
  wilayah VARCHAR(255) NOT NULL,
  latitude DECIMAL(10,7) NOT NULL,
  longitude DECIMAL(10,7) NOT NULL,
  foto VARCHAR(255) NULL,
  status_terkini VARCHAR(255) NULL,
  pengukuran_terakhir TIMESTAMP NULL
);
