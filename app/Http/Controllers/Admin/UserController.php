<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'unique:users'],
            'nip' => ['nullable', 'string', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:admin,guru,kepala'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'mata_pelajaran' => ['nullable', 'required_if:role,guru', 'string'],
            'tingkatan' => ['nullable', 'required_if:role,guru', 'string'],
            'jabatan' => ['nullable', 'string'],
            'unit_kerja' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'nik' => $validated['nik'],
                'nip' => $validated['nip'] ?? null,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'mata_pelajaran' => $validated['mata_pelajaran'] ?? null,
                'tingkatan' => $validated['tingkatan'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nik' => ['required', 'string', 'unique:users,nik,' . $user->id],
            'nip' => ['nullable', 'string', 'unique:users,nip,' . $user->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,guru,kepala'],
            'mata_pelajaran' => ['nullable', 'required_if:role,guru', 'string'],
            'tingkatan' => ['nullable', 'required_if:role,guru', 'string'],
            'jabatan' => ['nullable', 'string'],
            'unit_kerja' => ['nullable', 'string'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    public function resetPassword(User $user)
    {
        $defaultPassword = 'password123';
        $user->update([
            'password' => Hash::make($defaultPassword)
        ]);

        return back()->with('success', "Password berhasil direset menjadi: {$defaultPassword}");
    }
}