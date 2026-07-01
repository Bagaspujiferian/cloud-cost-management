<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->get('search');
        
        $query = User::where('role', 'admin');
        
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('email', 'like', "%{$searchQuery}%");
            });
        }
        
        $karyawans = $query->orderBy('created_at', 'desc')->get();
        return view('owner.karyawan.index', compact('karyawans', 'searchQuery'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => true,
        ]);

        return redirect()->route('owner.karyawan.index')->with('success', 'Akun admin berhasil dibuat.');
    }

    public function update(Request $request, User $karyawan)
    {
        if ($karyawan->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($karyawan->id)],
            'is_active' => 'required|boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8';
        }

        $request->validate($rules);

        $karyawan->name = $request->name;
        $karyawan->email = $request->email;
        $karyawan->is_active = $request->is_active;

        if ($request->filled('password')) {
            $karyawan->password = Hash::make($request->password);
        }

        $karyawan->save();

        return redirect()->route('owner.karyawan.index')->with('success', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(User $karyawan)
    {
        if ($karyawan->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        $karyawan->delete();

        return redirect()->route('owner.karyawan.index')->with('success', 'Akun admin berhasil dihapus secara permanen.');
    }
}
