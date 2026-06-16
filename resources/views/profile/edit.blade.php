<x-app-layout pageTitle="Profile">
    <x-page-header title="Profile" subtitle="Manage your account settings" />

    <div class="d-flex flex-column gap-4">
        <x-card title="Profile Information">
            @include('profile.partials.update-profile-information-form')
        </x-card>

        <x-card title="Update Password">
            @include('profile.partials.update-password-form')
        </x-card>

        <x-card title="Delete Account">
            @include('profile.partials.delete-user-form')
        </x-card>
    </div>
</x-app-layout>
