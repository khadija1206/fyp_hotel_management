<x-guest-portal-layout title="My Complaints">
    <x-page-header title="My Complaints" subtitle="Submit and track your complaints">
        <x-slot:actions>
            <a href="{{ route('guest.complaints.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> New Complaint
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        @if($complaints->isEmpty())
            <x-empty-state icon="chat-square-text" title="No complaints yet" message="If something needs attention, we're here to help.">
                <x-slot:action>
                    <a href="{{ route('guest.complaints.create') }}" class="btn btn-primary">Submit Complaint</a>
                </x-slot:action>
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($complaints as $c)
                            <tr>
                                <td><strong>{{ $c->complaint_reference }}</strong></td>
                                <td>{{ $c->title }}</td>
                                <td>{{ $c->category_label }}</td>
                                <td><x-status-badge :status="ucfirst($c->priority)" :type="$c->priority_color" /></td>
                                <td><x-status-badge :status="$c->status_label" :type="$c->status_color" /></td>
                                <td class="text-secondary-custom">{{ formatDate($c->created_at) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('guest.complaints.show', $c) }}" class="btn btn-link btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

    <div class="mt-3">{{ $complaints->links() }}</div>
</x-guest-portal-layout>
