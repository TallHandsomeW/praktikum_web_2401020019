<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Menampilkan, mencari, dan memfilter data mahasiswa
Route::get('/mahasiswa', function (Request $request) {
    try {
        $pdo = DB::connection()->getPdo();

        $q = trim($request->query('q', ''));
        $programStudiId = $request->query('program_studi_id');

        $sql = 'SELECT m.nim, m.nama, m.email, m.usia,
                       p.nama_prodi, m.program_studi_id
                FROM mahasiswa AS m
                JOIN program_studi AS p
                    ON p.id = m.program_studi_id';

        $conditions = [];
        $params = [];

        if ($q !== '') {
            $conditions[] = '(m.nim LIKE :q_nim
                              OR m.nama LIKE :q_nama
                              OR m.email LIKE :q_email)';

            $keyword = '%' . $q . '%';

            $params['q_nim'] = $keyword;
            $params['q_nama'] = $keyword;
            $params['q_email'] = $keyword;
        }

        if ($programStudiId !== null && $programStudiId !== '') {
            $conditions[] = 'm.program_studi_id = :program_studi_id';
            $params['program_studi_id'] = $programStudiId;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY m.nim';

        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        $daftarMahasiswa = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $statementProdi = $pdo->prepare(
            'SELECT id, nama_prodi
             FROM program_studi
             ORDER BY nama_prodi'
        );
        $statementProdi->execute();
        $daftarProgramStudi = $statementProdi->fetchAll(\PDO::FETCH_ASSOC);

        return view('mahasiswa', compact(
            'daftarMahasiswa',
            'daftarProgramStudi',
            'q',
            'programStudiId'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data tidak dapat dibaca. Periksa koneksi dan query.',
            500
        );
    }
})->name('mahasiswa.index');


// Menyimpan mahasiswa baru
Route::post('/mahasiswa', function (Request $request) {
    $data = $request->validate([
        'nim' => 'required|digits_between:8,12',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'usia' => 'required|integer|min:1',
        'program_studi_id' => 'required|integer|exists:program_studi,id',
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

        $statement->execute($data);

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


// Menampilkan form edit mahasiswa
Route::get('/mahasiswa/{nim}/edit', function (string $nim) {
    try {
        $pdo = DB::connection()->getPdo();

        $statement = $pdo->prepare(
            'SELECT nim, nama, email, usia, program_studi_id
             FROM mahasiswa
             WHERE nim = :nim'
        );
        $statement->execute(['nim' => $nim]);
        $mahasiswa = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$mahasiswa) {
            return redirect()
                ->route('mahasiswa.index')
                ->with('gagal', 'Data mahasiswa tidak ditemukan.');
        }

        $statementProdi = $pdo->prepare(
            'SELECT id, nama_prodi
             FROM program_studi
             ORDER BY nama_prodi'
        );
        $statementProdi->execute();
        $daftarProgramStudi = $statementProdi->fetchAll(\PDO::FETCH_ASSOC);

        return view('mahasiswa-edit', compact(
            'mahasiswa',
            'daftarProgramStudi'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data tidak dapat dibaca. Periksa koneksi dan query.',
            500
        );
    }
})->name('mahasiswa.edit');


// Memperbarui data mahasiswa
Route::put('/mahasiswa/{nim}', function (Request $request, string $nim) {
    $data = $request->validate([
        'nim' => 'required|digits_between:8,12',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'usia' => 'required|integer|min:1',
        'program_studi_id' => 'required|integer|exists:program_studi,id',
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
             WHERE (nim = :nim OR email = :email)
               AND nim <> :nim_lama'
        );

        $statementCek->execute([
            'nim' => $data['nim'],
            'email' => $data['email'],
            'nim_lama' => $nim,
        ]);

        if ((int) $statementCek->fetchColumn() > 0) {
            return back()
                ->withInput()
                ->with('gagal', 'NIM atau email sudah digunakan mahasiswa lain.');
        }

        $statement = $pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim,
                 nama = :nama,
                 email = :email,
                 usia = :usia,
                 program_studi_id = :program_studi_id
             WHERE nim = :nim_lama'
        );

        $statement->execute([
            'nim' => $data['nim'],
            'nama' => $data['nama'],
            'email' => $data['email'],
            'usia' => $data['usia'],
            'program_studi_id' => $data['program_studi_id'],
            'nim_lama' => $nim,
        ]);

        return redirect()
            ->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil diperbarui.');
    } catch (\Throwable $error) {
        report($error);

        return back()
            ->withInput()
            ->with('gagal', 'Data gagal diperbarui. Periksa koneksi dan data.');
    }
})->name('mahasiswa.update');


// Menghapus data mahasiswa
Route::delete('/mahasiswa/{nim}', function (string $nim) {
    try {
        $pdo = DB::connection()->getPdo();

        $statement = $pdo->prepare(
            'DELETE FROM mahasiswa WHERE nim = :nim'
        );
        $statement->execute(['nim' => $nim]);

        if ($statement->rowCount() === 0) {
            return redirect()
                ->route('mahasiswa.index')
                ->with('gagal', 'Data mahasiswa tidak ditemukan.');
        }

        return redirect()
            ->route('mahasiswa.index')
            ->with('berhasil', 'Data mahasiswa berhasil dihapus.');
    } catch (\Throwable $error) {
        report($error);

        return redirect()
            ->route('mahasiswa.index')
            ->with('gagal', 'Data gagal dihapus. Periksa koneksi dan data.');
    }
})->name('mahasiswa.destroy');