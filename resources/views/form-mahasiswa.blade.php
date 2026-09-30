lanjuttt<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Mahasiswa</title>
</head>
<body>
    <h1>Form Mahasiswa</h1>

    @if ($errors->any())
        <h3>Data belum valid</h3>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/form-mahasiswa" novalidate>
        @csrf

        <label for="nama">Nama:</label><br>
        <input
            type="text"
            id="nama"
            name="nama"
            value="{{ old('nama') }}"
        >
        <br><br>

        <label for="email">Email:</label><br>
        <input
            type="text"
            id="email"
            name="email"
            value="{{ old('email') }}"
        >
        <br><br>

        <label for="usia">Usia:</label><br>
        <input
            type="text"
            id="usia"
            name="usia"
            value="{{ old('usia') }}"
        >
        <br><br>

        <label for="nim">NIM:</label><br>
        <input
            type="text"
            id="nim"
            name="nim"
            value="{{ old('nim') }}"
        >
        <br><br>

        <button type="submit">Kirim</button>
    </form>
</body>
</html>