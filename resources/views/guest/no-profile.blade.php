<x-guest-portal-layout title="Profile Not Set Up">
    <x-card>
        <x-empty-state
            icon="exclamation-triangle"
            title="Profile Not Set Up"
            message="Your guest profile hasn't been created yet. Please contact reception or complete your profile.">
            <x-slot:action>
                <a href="{{ route('guest.profile.edit') }}" class="btn btn-primary">Complete Profile</a>
            </x-slot:action>
        </x-empty-state>
    </x-card>
</x-guest-portal-layout>
