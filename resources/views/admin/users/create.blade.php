<x-app-layout pageTitle="Add User">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Users' => route('admin.users.index'),
        'Add User' => null,
    ]" />

    <x-page-header title="Add New User" subtitle="Create a staff account" />

    <x-card>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-form-field label="Full Name" name="name" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Email Address" name="email" type="email" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Password" name="password" type="password" required
                                  hint="Min 8 characters, must include letters and numbers" />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Confirm Password" name="password_confirmation" type="password" required />
                </div>
                <div class="col-md-6">
                    <x-form-select label="Role" name="role" required
                                   :options="['admin' => 'Admin', 'receptionist' => 'Receptionist']" />
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Account active</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
</x-app-layout>
