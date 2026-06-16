<x-app-layout pageTitle="User Management">
    <x-page-header title="User Management" subtitle="Manage staff accounts and roles">
        <x-slot:actions>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Add User
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Name or email..." class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">All roles</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="receptionist" @selected(request('role') === 'receptionist')>Receptionist</option>
                    <option value="guest" @selected(request('role') === 'guest')>Guest</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
            </div>
        </form>
    </x-card>

    <x-data-table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <x-status-badge status="Admin" type="info" />
                        @elseif($user->role === 'receptionist')
                            <x-status-badge status="Receptionist" type="warning" />
                        @else
                            <x-status-badge status="Guest" type="neutral" />
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <x-status-badge status="Active" type="success" />
                        @else
                            <x-status-badge status="Inactive" type="danger" />
                        @endif
                    </td>
                    <td class="text-secondary-custom">{{ formatDate($user->created_at) }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-link btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>

                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-secondary-custom">
                                    <i class="bi bi-{{ $user->is_active ? 'pause-circle' : 'play-circle' }}"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <button type="button" class="btn btn-link btn-sm text-danger"
                                    data-bs-toggle="modal" data-bs-target="#delete-user-{{ $user->id }}">
                                <i class="bi bi-trash"></i>
                            </button>

                            <x-confirm-modal
                                id="delete-user-{{ $user->id }}"
                                title="Delete User?"
                                message="Are you sure you want to delete '{{ $user->name }}'? This cannot be undone."
                                action="{{ route('admin.users.destroy', $user) }}"
                                method="DELETE"
                                confirmText="Delete"
                            />
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <x-empty-state icon="people" title="No users found" message="Try adjusting your filters or add a new user." />
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-3">{{ $users->links() }}</div>
</x-app-layout>
