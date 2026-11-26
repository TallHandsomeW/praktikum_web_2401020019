<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Buku</title>
</head>
<body>
    <h1>Data Buku</h1>

    <p>Masuk sebagai {{ auth()->user()->name }} ({{ auth()->user()->role }})</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Keluar</button>
    </form>

    @can('admin')
        <a href="{{ route('buku.create') }}">Tambah Buku</a>
    @endcan

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

            @can('admin')
                <th>Aksi</th>
            @endcan
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

                @can('admin')
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
                @endcan
            </tr>
        @endforeach
    </table>

    @if (count($buku) === 0)
        <p>Data buku tidak ditemukan.</p>
    @endif
</body>
</html>