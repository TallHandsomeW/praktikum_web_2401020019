
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Menampilkan daftar mahasiswa
Route::get('/mahasiswa/{nim?}', function (?string $nim = null) {
    try {
        $pdo = DB::connection()->getPdo();

        $sql = 'SELECT m.nim, m.nama, m.email, m.usia,
                    p.nama_prodi
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id';

        if ($nim !== null) {
            $sql .= ' WHERE m.nim = :nim';
        }

        $sql .= ' ORDER BY m.nim';

        $statement = $pdo->prepare($sql);
        $statement->execute($nim !== null ? ['nim' => $nim] : []);

        $daftarMahasiswa = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $statementProdi = $pdo->prepare(
            'SELECT id, nama_prodi FROM program_studi ORDER BY nama_prodi'
        );
        $statementProdi->execute();

        $daftarProgramStudi = $statementProdi->fetchAll(\PDO::FETCH_ASSOC);

        return view('mahasiswa', compact(
            'daftarMahasiswa',
            'daftarProgramStudi',
            'nim'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data tidak dapat dibaca. Periksa koneksi dan query.',
            500
        );
    }
})->name('mahasiswa.index');

// Menyimpan data mahasiswa
Route::post('/mahasiswa', function (Request $request) {
    $data = $request->validate([
        'nim' => 'required|digits_between:8,12',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'usia' => 'required|integer|min:1',
        'program_studi_id' => 'required|integer',
    ], [
        'nim.required' => 'NIM wajib diisi.',
        'nim.digits_between' => 'NIM harus terdiri dari 8 sampai 12 digit.',
        'nama.required' => 'Nama wajib diisi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'usia.required' => 'Usia wajib diisi.',
        'program_studi_id.required' => 'Program studi wajib dipilih.',
    ]);

    try {
        $pdo = DB::connection()->getPdo();

        $statementCek = $pdo->prepare(
            'SELECT COUNT(*) FROM mahasiswa
             WHERE nim = :nim OR email = :email'
        );

        $statementCek->execute([
            'nim' => $data['nim'],
            'email' => $data['email'],
        ]);

        if ((int) $statementCek->fetchColumn() > 0) {
            return back()
                ->withInput()
                ->with('gagal', 'NIM atau email sudah digunakan.');
        }

        $statement = $pdo->prepare(
            'INSERT INTO mahasiswa
                (nim, nama, email, usia, program_studi_id)
             VALUES
                (:nim, :nama, :email, :usia, :program_studi_id)'
        );

        $statement->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'usia' => $data['usia'],
            'program_studi_id' => $data['program_studi_id'],
        ]);

        return redirect()
            ->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil ditambahkan.');
    } catch (\Throwable $error) {
        report($error);

        return back()
            ->withInput()
            ->with('gagal', 'Data gagal disimpan. Periksa koneksi dan data.');
    }
})->name('mahasiswa.store');
