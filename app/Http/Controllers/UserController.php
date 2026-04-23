<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'role'       => 'required|in:admin,operator,pelanggan',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'no_telepon' => $request->no_telepon,
            'alamat'     => $request->alamat,
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $user->load('pesanan');
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'role'       => 'required|in:admin,operator,pelanggan',
            'no_telepon' => 'nullable|string|max:20',
            'alamat'     => 'nullable|string',
            'password'   => 'nullable|min:6|confirmed',
        ]);

        $data = $request->only('name', 'email', 'role', 'no_telepon', 'alamat');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->pesanan()->exists()) {
            return back()->with('error', 'Pengguna tidak dapat dihapus karena memiliki data pesanan.');
        }

        $user->delete();

        return redirect()->route('users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}
