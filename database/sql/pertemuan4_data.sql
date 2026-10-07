
USE praktikum_web_2401020019;

-- ====== MENGISI DATA PROGRAM STUDI ======
INSERT INTO program_studi (nama_prodi) VALUES
    ('Teknik Informatika'),
    ('Teknik Elektro');

-- ====== MENGISI DATA MAHASISWA ======
INSERT INTO mahasiswa
    (nim, nama, email, usia, program_studi_id)
VALUES
    ('2401020019', 'Willy Hadipermana',
     'willyhadipermanaa@gmail.com', 19, 1),
    ('2401020021', 'Rina Marlina',
     'rina.marlina@example.com', 19, 1),
    ('2402010005', 'Dimas Prakoso',
     'dimas.prakoso@example.com', 20, 2),
    ('2402010099', 'Data Sementara',
     'sementara@example.com', 18, 2);

-- ====== MEMPERBARUI EMAIL MAHASISWA ======
UPDATE mahasiswa
SET email = 'rina.marlina21@example.com'
WHERE nim = '2401020021';

-- ====== MENGHAPUS DATA SEMENTARA ======
DELETE FROM mahasiswa
WHERE nim = '2402010099';

-- ====== MENAMPILKAN DATA DENGAN SELECT JOIN ======
SELECT m.nim, m.nama, m.email, m.usia,
       p.nama_prodi
FROM mahasiswa AS m
JOIN program_studi AS p
    ON p.id = m.program_studi_id
ORDER BY m.nim;