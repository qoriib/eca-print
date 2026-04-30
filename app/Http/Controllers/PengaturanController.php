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
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'catatan_footer' => 'nullable|string',
        ]);

        $pengaturan = Pengaturan::first() ?? new Pengaturan();
        
        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($pengaturan->logo) {
                Storage::disk('public')->delete($pengaturan->logo);
            }
            $data['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $pengaturan->fill($data)->save();

        return back()->with('success', 'Pengaturan profil usaha berhasil diperbarui.');
    }
}
