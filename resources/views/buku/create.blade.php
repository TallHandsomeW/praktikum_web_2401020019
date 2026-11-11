
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
</head>
<body>
    <h1>Tambah Buku</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('buku.store') }}" method="POST">
        @csrf

        <label for="kode">Kode Buku:</label>
        <input type="text" name="kode" id="kode" value="{{ old('kode') }}">
        <br><br>

        <label for="judul">Judul Buku:</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul') }}">
        <br><br>

        <label for="kategori_id">Kategori:</label>
        <select name="kategori_id" id="kategori_id">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategori as $item)
                <option value="{{ $item->id }}"
                    {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Simpan</button>
    </form>

    <br>
    <a href="{{ route('buku.index') }}">Kembali</a>
</body>
</html>
