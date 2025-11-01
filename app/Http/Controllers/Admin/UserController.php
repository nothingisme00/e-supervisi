<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter (with proper grouping)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Tingkat filter
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Validate sort columns
        $allowedSortColumns = ['nik', 'name', 'created_at'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'created_at';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $users = $query->orderBy($sortBy, $sortDirection)->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:18|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users',
            'role' => 'required|in:admin,guru,kepala_sekolah',
            'tingkat' => 'required_if:role,guru|in:SD,SMP,SMA',
            'mata_pelajaran' => 'required_if:role,guru|string'
        ]);

        User::create([
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('pass123456'), // Password default
            'role' => $request->role,
            'tingkat' => $request->tingkat,
            'mata_pelajaran' => $request->mata_pelajaran,
            'must_change_password' => true // Wajib ganti password saat login pertama
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan dengan password default: pass123456');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nik' => 'required|string|size:18|unique:users,nik,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,guru,kepala_sekolah',
            'tingkat' => 'required_if:role,guru|in:SD,SMP,SMA',
            'mata_pelajaran' => 'required_if:role,guru|string'
        ]);

        $user->update($request->except('password'));

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = 'pass123456'; // Default password
        $user->update([
            'password' => Hash::make($newPassword),
            'must_change_password' => true // Wajib ganti password saat login
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset ke: ' . $newPassword
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $user->is_active
        ]);
    }
}