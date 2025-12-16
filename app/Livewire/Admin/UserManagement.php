<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Filter properties
    public $search = '';
    public $role = '';
    public $tingkat = '';
    public $status = '';
    
    // Sorting properties
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Query string untuk URL
    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'tingkat' => ['except' => ''],
        'status' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    // Reset pagination saat filter berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function updatingTingkat()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    // Sorting function
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Reset all filters
    public function resetFilters()
    {
        $this->search = '';
        $this->role = '';
        $this->tingkat = '';
        $this->status = '';
        $this->resetPage();
    }

    // Toggle user status
    public function toggleStatus($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        
        $this->dispatch('status-updated', [
            'message' => $user->is_active ? 'User diaktifkan' : 'User dinonaktifkan'
        ]);
    }

    // Check if any filter is active
    public function getHasFiltersProperty()
    {
        return !empty($this->search) || !empty($this->role) || !empty($this->tingkat) || !empty($this->status);
    }

    public function render()
    {
        $query = User::query();

        // Search filter
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if (!empty($this->role)) {
            $query->where('role', $this->role);
        }

        // Tingkat filter
        if (!empty($this->tingkat)) {
            $query->where('tingkat', $this->tingkat);
        }

        // Status filter
        if (!empty($this->status)) {
            $isActive = $this->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Sorting
        $allowedSortColumns = ['nik', 'name', 'created_at'];
        $sortBy = in_array($this->sortBy, $allowedSortColumns) ? $this->sortBy : 'created_at';
        $sortDirection = in_array($this->sortDirection, ['asc', 'desc']) ? $this->sortDirection : 'desc';

        $users = $query->orderBy($sortBy, $sortDirection)->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users,
            'hasFilters' => $this->hasFilters,
        ]);
    }
}
