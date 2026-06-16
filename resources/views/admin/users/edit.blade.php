<x-app-layout pageTitle="Edit User">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Users' => route('admin.users.index'),
        'Edit User' => null,
    ]" />

    <x-page-header title="Edit User" subtitle="{{ $user->name }}" />

    <x-card>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <x-form-field label="Full Name" name="name" :value="$user->name" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Email Address" name="email" type="email" :value="$user->email" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="New Password" name="password" type="password"
                                  hint="Leave blank to keep current password" />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Confirm New Password" name="password_confirmation" type="password" />
                </div>
                <div class="col-md-6">
                    <x-form-select label="Role" name="role" required :selected="$user->role"
                                   :options="['admin' => 'Admin', 'receptionist' => 'Receptionist', 'guest' => 'Guest']" />
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                   {{ $user->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Account active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update User</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
