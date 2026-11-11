<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Buku</title>
</head>
<body>
    <h1>Data Buku</h1>

    <a href="{{ route('buku.create') }}">Tambah Buku</a>
    <br><br>
    <p>Praktikum Pemrograman Web</p>

    <form method="GET" action="{{ url('/buku') }}">
        <p>
            Cari judul
            <input name="cari" value="{{ $cari }}">
        </p>

        <p>
            <button type="submit">Cari</button>
        </p>
    </form>

    <table border="1">
        <tr>
            <th>Kode</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Detail</th>
            <th>Aksi</th>
        </tr>

        
        @foreach ($buku as $item)
            <tr>
                <td>{{ $item->kode }}</td>
                <td>{{ $item->judul }}</td>
                <td>{{ $item->kategori->nama }}</td>
                <td>
                    <a href="{{ url('/buku/'.$item->kode) }}">
                        Lihat
                    </a>
                </td>
                <td>
                    <a href="{{ route('buku.edit', $item->id) }}">
                        Edit
                    </a>

                    <form action="{{ route('buku.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    @if (count($buku) === 0)
        <p>Data buku tidak ditemukan.</p>
    @endif
</body>
</html>