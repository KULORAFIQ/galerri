<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('foto.index')->with([
            'foto' => Foto::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $album = Album::all();
        return view('foto.create', compact('album'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'judul_foto' => 'required',
        'deskripsi_foto' => 'required',
        'lokasi_file' => 'required|image', // Validasi untuk memastikan file adalah gambar
        'album_id' => 'required|exists:album,id',
    ]);

    // Mendapatkan nama file asli
    $namaFile = $request->file('lokasi_file')->getClientOriginalName();
    
    // Menyimpan file gambar ke direktori yang diinginkan
    $namaFile = $request->file('lokasi_file')->store('public/img');

    // Mendapatkan ID pengguna yang sedang masuk
    $user_id = Auth::id();

    // Membuat foto baru dengan atribut yang diberikan dan user_id yang sesuai
    Foto::create([
        'judul_foto' => $request->judul_foto,
        'deskripsi_foto' => $request->deskripsi_foto,
        'lokasi_file' => $namaFile, // Menyimpan nama file dalam database
        'album_id' => $request->album_id,
        'user_id' => $user_id, // Menggunakan id pengguna yang sedang masuk
    ]);

    return redirect()->route('foto.index')->with('success','Foto berhasil ditambahkan.');
}


    /**
     * Display the specified resource.
     */
    public function show(Foto $foto)
    {
        return view('foto.show', compact('foto')); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Foto $foto)
    {
        return view('foto.edit', compact('foto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Foto $foto)
{
    $request->validate([
        'judul_foto' => 'required',
        'deskripsi_foto' => 'required',
        'lokasi_file' => 'required',
        'album_id' => 'required|exists:album,id',
    ]);

    $foto->update($request->all());

    return redirect()->route('foto.index')->with('success','Foto berhasil diperbarui.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Foto $foto)
    {
        $foto->delete();

        return redirect()->route('foto.index')
                         ->with('success','Foto berhasil dihapus.');
    
    }
}
