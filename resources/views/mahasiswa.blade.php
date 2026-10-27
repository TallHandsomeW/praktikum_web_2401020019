<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
</head>
<body>
    <h1>Data Mahasiswa</h1>

    @if (session('berhasil'))
        <p>{{ session('berhasil') }}</p>
    @endif

    @if (session('gagal'))
        <p>{{ session('gagal') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Tambah Mahasiswa</h2>

    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf

        <p>
            <label>NIM:</label><br>
            <input type="text" name="nim" value="{{ old('nim') }}" required>
        </p>

        <p>
            <label>Nama:</label><br>
            <input type="text" name="nama" value="{{ old('nama') }}" required>
        </p>

        <p>
            <label>Email:</label><br>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </p>

        <p>
            <label>Usia:</label><br>
            <input type="number" name="usia" value="{{ old('usia') }}" required>
        </p>

        <p>
            <label>Program Studi:</label><br>
            <select name="program_studi_id" required>
                <option value="">Pilih Program Studi</option>
                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ old('program_studi_id') == $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Tambah Data</button>
    </form>

    <hr>

    <h2>Daftar Mahasiswa</h2>

    <form action="{{ route('mahasiswa.index') }}" method="GET">
        <p>
            <label for="q">Cari mahasiswa (NIM, nama, atau email):</label><br>
            <input
                type="text"
                id="q"
                name="q"
                value="{{ $q }}"
                placeholder="Masukkan kata kunci"
            >
        </p>

        <p>
            <label for="program_studi_id">Filter Program Studi:</label><br>
            <select id="program_studi_id" name="program_studi_id">
                <option value="">Semua Program Studi</option>
                @foreach ($daftarProgramStudi as $prodi)
                    <option value="{{ $prodi['id'] }}"
                        {{ (string) $programStudiId === (string) $prodi['id'] ? 'selected' : '' }}>
                        {{ $prodi['nama_prodi'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Cari / Filter</button>
        <a href="{{ route('mahasiswa.index') }}">Reset</a>
    </form>

    <br>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Usia</th>
                <th>Program Studi</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($daftarMahasiswa as $mahasiswa)
                <tr>
                    <td>{{ $mahasiswa['nim'] }}</td>
                    <td>{{ $mahasiswa['nama'] }}</td>
                    <td>{{ $mahasiswa['email'] }}</td>
                    <td>{{ $mahasiswa['usia'] }}</td>
                    <td>{{ $mahasiswa['nama_prodi'] }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.edit', ['nim' => $mahasiswa['nim']]) }}">
                            Ubah
                        </a>

                        <form
                            action="{{ route('mahasiswa.destroy', ['nim' => $mahasiswa['nim']]) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus data mahasiswa ini?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>