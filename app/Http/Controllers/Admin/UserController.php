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
        // Only show admin users
        $query = User::where('role', 'admin');

        // Search filter (with proper grouping)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter - removed since we only show admin users
        // if ($request->filled('role')) {
        //     $query->where('role', $request->role);
        // }

        // Tingkat filter - removed since admin doesn't have tingkat
        // if ($request->filled('tingkat')) {
        //     $query->where('tingkat', $request->tingkat);
        // }

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

        // No need for supervisi count since admin users don't have supervisi

        // Pagination ditingkatkan dari 10 menjadi 15
        $users = $query->orderBy($sortBy, $sortDirection)
                      ->paginate(15)
                      ->withQueryString();

        return view('admin.users.index', compact('users', 'sortBy', 'sortDirection'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Only allow creating admin users
        $rules = [
            'nik' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ];

        $request->validate($rules);

        $defaultPassword = env('DEFAULT_USER_PASSWORD', 'admin123');
        $userData = [
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($defaultPassword),
            'role' => 'admin', // Force role to admin
            'tingkat' => null, // Admin doesn't have tingkat
            'mata_pelajaran' => null, // Admin doesn't have mata_pelajaran
            'is_active' => true,
            'must_change_password' => true
        ];

        User::create($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin berhasil ditambahkan dengan password default: ' . $defaultPassword);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $isEditingSelf = auth()->id() == $user->id;
        
        return view('admin.users.edit', compact('user', 'isEditingSelf'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isEditingSelf = auth()->id() == $user->id;

        // Only allow updating admin users
        $rules = [
            'nik' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users,nik,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        $request->validate($rules);

        // Data yang akan diupdate
        $userData = [
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'admin', // Keep role as admin
            'tingkat' => null,
            'mata_pelajaran' => null,
        ];

        $user->update($userData);

        if ($isEditingSelf) {
            return redirect()->route('admin.users.index')
                ->with('success', 'Data Anda berhasil diupdate');
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin berhasil diupdate');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = env('DEFAULT_USER_PASSWORD', 'admin123'); // Default admin password
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
        try {
            $user = User::findOrFail($id);

            // Prevent deleting self
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
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