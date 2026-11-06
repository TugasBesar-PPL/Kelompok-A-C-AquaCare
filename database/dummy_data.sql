-- Data Dummy Titik Sumber Air (PBI-01 & PBI-10)
INSERT INTO titik_sumber_air (nama, jenis, alamat, wilayah, latitude, longitude, foto, status_terkini, pengukuran_terakhir) VALUES
('Sumur Bor RW 03', 'Sumur Bor', 'Jl. Melati No. 12, RW 03', 'RW 03', -6.9745210, 107.6301240, 'sumur_rw03.jpg', 'Waspada', '2026-10-24 08:30:00'),
('Mata Air Kenanga', 'Mata Air', 'Jl. Kenanga No. 5, RW 01', 'RW 01', -6.9712500, 107.6324800, 'mata_air_kenanga.jpg', 'Layak', '2026-10-24 09:15:00'),
('Sumur Gali RW 05', 'Sumur Gali', 'Gg. Anggrek No. 8, RW 05', 'RW 05', -6.9789100, 107.6285400, 'sumur_rw05.jpg', 'Tidak Layak', '2026-10-24 10:00:00'),
('PDAM Blok A', 'PDAM', 'Komplek Permata Blok A4', 'RW 02', -6.9698400, 107.6351200, 'pdam_bloka.jpg', 'Layak', '2026-10-24 10:45:00'),
('Sumur Bor RW 06', 'Sumur Bor', 'Jl. Flamboyan No. 17, RW 06', 'RW 06', -6.9763200, 107.6342100, 'sumur_rw06.jpg', 'Layak', '2026-10-24 11:30:00');

-- Data Dummy Pengumuman & Edukasi (PBI-12)
INSERT INTO pengumuman (admin_id, judul, isi, kategori, gambar, status_publikasi) VALUES
(1, 'Pentingnya Menjaga Kebersihan Sumber Air di Lingkungan Rumah', 
 'Air bersih merupakan kebutuhan esensial sehari-hari. Pastikan jarak antara sumur gali/bor dengan septic tank minimal 10 meter untuk mencegah kontaminasi bakteri E. Coli. Lakukan pengurasan bak penampungan minimal seminggu sekali.', 
 'edukasi', 'edukasi_air_bersih.jpg', 'published'),

(1, 'Kenali Ciri Fisik Air Bersih yang Layak Konsumsi', 
 'Secara kasat mata, air bersih harus memenuhi kriteria fisik: tidak berwarna (jernih), tidak berbau menyengat, dan tidak memiliki rasa yang aneh. Apabila mendapati air keruh atau berbau besi, segera laporkan melalui portal laporan warga.', 
 'edukasi', 'ciri_air_layak.jpg', 'published'),

(1, 'Jadwal Pemeliharaan Pipa Distribusi Wilayah RW 02 dan RW 03', 
 'Diberitahukan kepada seluruh warga RW 02 dan RW 03 bahwa akan dilakukan pembersihan dan flushing pipa distribusi pada hari Sabtu mendatang pukul 09.00 - 12.00 WIB. Aliran air mungkin akan mengecil untuk sementara waktu.', 
 'pengumuman', 'pengumuman_pipa.jpg', 'published');

