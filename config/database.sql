CREATE TABLE jabatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_jabatan VARCHAR(100) NOT NULL
);

CREATE TABLE kpi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  jabatan_id INT,
  kpi VARCHAR(255),
  jenis_hasil VARCHAR(100),
  jumlah_dokumen INT,
  bobot DECIMAL(5,2),
  pencapaian DECIMAL(5,2),
  target DECIMAL(5,2),
  skor DECIMAL(5,2),
  FOREIGN KEY (jabatan_id) REFERENCES jabatan(id)
);
