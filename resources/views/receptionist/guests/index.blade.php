<x-app-layout pageTitle="Guests">
    <x-page-header title="Guests" subtitle="All registered guests">
        <x-slot:actions>
            <a href="{{ route('guests.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Register Guest
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-10">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by name, phone, CNIC, or email..." class="form-control">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Identity</th>
                        <th>Nationality</th>
                        <th>Total Stays</th>
                        <th>Registered</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $guest)
                        <tr>
                            <td>
                                <a href="{{ route('guests.show', $guest) }}" class="fw-bold">
                                    {{ $guest->full_name }}
                                </a>
                                @if($guest->email)
                                    <div><small class="text-secondary-custom">{{ $guest->email }}</small></div>
                                @endif
                            </td>
                            <td>{{ $guest->phone }}</td>
                            <td><small>{{ $guest->identity_document }}</small></td>
                            <td>{{ $guest->nationality }}</td>
                            <td>
                                <x-status-badge :status="$guest->bookings_count . ' stays'" type="info" />
                            </td>
                            <td class="text-secondary-custom">{{ formatDate($guest->created_at) }}</td>
                            <td class="text-end">
                                <a href="{{ route('guests.show', $guest) }}" class="btn btn-link btn-sm">
                                    <i class="bi bi-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-empty-state icon="people" title="No guests yet" message="Register your first guest to get started." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-3">{{ $guests->links() }}</div>
</x-app-layout>
