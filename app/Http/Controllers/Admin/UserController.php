<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Show all users
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
        // Validation rules
        $rules = [
            'nik' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_GURU . ',' . User::ROLE_KEPALA_SEKOLAH,
        ];

        // Add conditional validation for guru and kepala_sekolah
        if ($request->role === User::ROLE_GURU || $request->role === User::ROLE_KEPALA_SEKOLAH) {
            $rules['tingkat'] = 'required|in:SD,SMP';
        }

        if ($request->role === User::ROLE_GURU) {
            $rules['mata_pelajaran'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $defaultPassword = env('DEFAULT_USER_PASSWORD', 'pass123456');
        $userData = [
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($defaultPassword),
            'role' => $request->role,
            'tingkat' => ($request->role === User::ROLE_GURU || $request->role === User::ROLE_KEPALA_SEKOLAH) ? $request->tingkat : null,
            'mata_pelajaran' => ($request->role === User::ROLE_GURU) ? $request->mata_pelajaran : null,
            'is_active' => true,
            'must_change_password' => true
        ];

        User::create($userData);

        $roleName = [
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_GURU => 'Guru',
            User::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah'
        ];

        return redirect()->route('admin.users.index')
            ->with('success', $roleName[$request->role] . ' berhasil ditambahkan dengan password default: ' . $defaultPassword);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $isEditingSelf = Auth::id() == $user->id;
        
        return view('admin.users.edit', compact('user', 'isEditingSelf'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isEditingSelf = Auth::id() == $user->id;

        // Validation rules
        $rules = [
            'nik' => 'required|string|size:16|regex:/^[0-9]{16}$/|unique:users,nik,' . $id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_GURU . ',' . User::ROLE_KEPALA_SEKOLAH,
        ];

        // Add conditional validation for guru and kepala_sekolah
        if ($request->role === User::ROLE_GURU || $request->role === User::ROLE_KEPALA_SEKOLAH) {
            $rules['tingkat'] = 'required|in:SD,SMP';
        }

        if ($request->role === User::ROLE_GURU) {
            $rules['mata_pelajaran'] = 'required|string|max:255';
        }

        $request->validate($rules);

        // Data yang akan diupdate
        $userData = [
            'nik' => $request->nik,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'tingkat' => ($request->role === User::ROLE_GURU || $request->role === User::ROLE_KEPALA_SEKOLAH) ? $request->tingkat : null,
            'mata_pelajaran' => ($request->role === User::ROLE_GURU) ? $request->mata_pelajaran : null,
        ];

        $user->update($userData);

        if ($isEditingSelf) {
            return redirect()->route('admin.users.index')
                ->with('success', 'Data Anda berhasil diupdate');
        }

        $roleName = [
            User::ROLE_ADMIN => 'Admin',
            User::ROLE_GURU => 'Guru',
            User::ROLE_KEPALA_SEKOLAH => 'Kepala Sekolah'
        ];

        return redirect()->route('admin.users.index')
            ->with('success', $roleName[$request->role] . ' berhasil diupdate');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $newPassword = env('DEFAULT_USER_PASSWORD', 'pass123456'); // Default password
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
            if ($user->id === Auth::id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
            }

            // Nullify foreign key references before deleting
            // This handles the supervisi.reviewed_by constraint
            \App\Models\Supervisi::where('reviewed_by', $user->id)->update(['reviewed_by' => null]);
            
            // Also handle user_id in supervisi (supervisi owned by this user)
            \App\Models\Supervisi::where('user_id', $user->id)->delete();
            
            // Handle feedback created by this user
            \App\Models\Feedback::where('user_id', $user->id)->delete();

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