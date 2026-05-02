<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Arsip;

class PetugasController extends Controller
{
    public function index()
    {
        return view('petugas.dashboard');
    }

    public function inputSurat()
    {
        return view('petugas.surat.create');
    }

    public function storeSurat(Request $request)
    {
        // Logika simpan surat & upload file dokumen
        // Sesuai ERD: simpan ke tabel 'surat'
        return redirect()->route('petugas.surat.status')->with('success', 'Surat berhasil diinput!');
    }

    public function statusSurat()
    {
        $surats = Surat::where('id_user', auth()->id())->get();
        return view('petugas.surat.status', compact('surats'));
    }

    public function kelolaArsip()
    {
        $arsips = Arsip::all();
        return view('petugas.arsip.index', compact('arsips'));
    }
}