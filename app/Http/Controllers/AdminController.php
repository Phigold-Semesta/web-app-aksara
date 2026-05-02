<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\KategoriSurat;
use App\Models\InstruksiDisposisi;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function kelolaUser()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    public function masterKategori()
    {
        $kategori = KategoriSurat::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function masterInstruksi()
    {
        $instruksi = InstruksiDisposisi::all();
        return view('admin.instruksi.index', compact('instruksi'));
    }

    public function auditLog()
    {
        $logs = AuditLog::with('user')->latest()->get();
        return view('admin.audit.index', compact('logs'));
    }

    // Admin juga bisa akses fitur operasional jika diperlukan
    public function inputSurat() { return view('admin.surat.create'); }
    public function kelolaArsip() { return view('admin.arsip.index'); }
}