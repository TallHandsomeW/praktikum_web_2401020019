<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Buku</title>
</head>
<body>
    <h1>Edit Buku</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('buku.update', $buku->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="kode">Kode Buku:</label>
        <input type="text" name="kode" id="kode"
               value="{{ old('kode', $buku->kode) }}">
        <br><br>

        <label for="judul">Judul Buku:</label>
        <input type="text" name="judul" id="judul"
               value="{{ old('judul', $buku->judul) }}">
        <br><br>

        <label for="kategori_id">Kategori:</label>
        <select name="kategori_id" id="kategori_id">
            @foreach ($kategori as $item)
                <option value="{{ $item->id }}"
                    {{ old('kategori_id', $buku->kategori_id) == $item->id ? 'selected' : '' }}>
                    {{ $item->nama }}
                </option>
            @endforeach
        </select>
        <br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="{{ route('buku.index') }}">Kembali</a>
</body>
</html>