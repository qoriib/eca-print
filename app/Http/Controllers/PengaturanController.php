<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::first();
        return view('pengaturan.index', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'catatan_footer' => 'nullable|string',
            'bank_1' => 'nullable|string|max:255',
            'norek_1' => 'nullable|string|max:255',
            'atas_nama_1' => 'nullable|string|max:255',
            'bank_2' => 'nullable|string|max:255',
            'norek_2' => 'nullable|string|max:255',
            'atas_nama_2' => 'nullable|string|max:255',
        ]);

        $pengaturan = Pengaturan::first() ?? new Pengaturan();
        
        $data = $request->all();

        $pengaturan->fill($data)->save();

        return back()->with('success', 'Pengaturan profil usaha berhasil diperbarui.');
    }
}
