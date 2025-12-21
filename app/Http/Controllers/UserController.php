<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'role' => 'required|in:admin,kasir',
            'password' => 'required|min:6',
        ]);

        User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('user.index')
            ->with('success', 'Akun berhasil ditambahkan');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'nama' => 'required|string|max:100',
            'email' => 'required|email',
            'role' => 'required|in:admin,kasir',
            'password' => 'nullable|min:6', // Password boleh kosong (nullable)
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Jika input password diisi, masukkan ke array $data
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        User::where('id_user', $request->id_user)->update($data);

        return redirect()
            ->route('user.index')
            ->with('success', 'Akun berhasil diperbarui' . ($request->filled('password') ? ' termasuk password baru' : ''));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun {$user->nama} berhasil $status");
    }

}
