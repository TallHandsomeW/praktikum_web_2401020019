<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $cari = request('cari');

        $buku = Buku::with('kategori')
            ->when($cari, function ($query) use ($cari) {
                $query->where('judul', 'like', '%' . $cari . '%');
            })
            ->get();

        return view('buku.index', compact('buku', 'cari'));
    }

    public function create()
    {
        $kategori = \App\Models\Kategori::all();

        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:bukus,kode',
            'judul' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Buku::create([
            'kode' => $request->kode,
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('/buku')->with('success', 'Data buku berhasil ditambahkan.');
    }

    public function edit($buku)
    {
        $buku = Buku::with('kategori')->findOrFail($buku);
        $kategori = \App\Models\Kategori::all();

        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $buku)
    {
        $buku = Buku::findOrFail($buku);

        $request->validate([
            'kode' => 'required|unique:bukus,kode,' . $buku->id,
            'judul' => 'required',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku->update([
            'kode' => $request->kode,
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('/buku')->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy($buku)
    {
        $buku = Buku::findOrFail($buku);
        $buku->delete();

        return redirect('/buku')->with('success', 'Data buku berhasil dihapus.');
    }

    public function show($kode)
    {
        $buku = Buku::with('kategori')
            ->where('kode', $kode)
            ->firstOrFail();

        return view('buku.show', compact('buku'));
    }
}
