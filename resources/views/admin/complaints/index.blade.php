<x-app-layout pageTitle="Complaints">
    <x-page-header title="Complaints" subtitle="Manage and resolve guest complaints" />

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Pending" :value="$stats['pending']" icon="hourglass-split" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="In Progress" :value="$stats['in_progress']" icon="tools" accent />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="High Priority Open" :value="$stats['high_priority_open']" icon="exclamation-triangle" meta="Needs attention" metaType="negative" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Unassigned" :value="$stats['unassigned']" icon="question-circle" />
        </div>
    </div>

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="open" @selected(request('status') === 'open')>Open (any active)</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="in_progress" @selected(request('status') === 'in_progress')>In Progress</option>
                    <option value="resolved" @selected(request('status') === 'resolved')>Resolved</option>
                    <option value="reopened" @selected(request('status') === 'reopened')>Reopened</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    <option value="">All</option>
                    <option value="high" @selected(request('priority') === 'high')>High</option>
                    <option value="medium" @selected(request('priority') === 'medium')>Medium</option>
                    <option value="low" @selected(request('priority') === 'low')>Low</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All</option>
                    @foreach(['room', 'food', 'service', 'billing', 'noise', 'cleanliness', 'other'] as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Assignment</label>
                <select name="assigned" class="form-select">
                    <option value="">All</option>
                    <option value="unassigned" @selected(request('assigned') === 'unassigned')>Unassigned</option>
                    <option value="me" @selected(request('assigned') === 'me')>Assigned to me</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Apply Filters</button>
            </div>
        </form>
    </x-card>

    <x-data-table>
        <thead>
            <tr>
                <th>Reference</th>
                <th>Title</th>
                <th>Guest</th>
                <th>Room</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Submitted</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $c)
                <tr>
                    <td><a href="{{ route('admin.complaints.show', $c) }}"><strong>{{ $c->complaint_reference }}</strong></a></td>
                    <td>
                        {{ $c->title }}
                        <div><small class="text-secondary-custom">{{ $c->category_label }}</small></div>
                    </td>
                    <td>{{ $c->guest->full_name }}</td>
                    <td>{{ $c->room ? 'Room ' . $c->room->room_number : '—' }}</td>
                    <td><x-status-badge :status="ucfirst($c->priority)" :type="$c->priority_color" /></td>
                    <td><x-status-badge :status="$c->status_label" :type="$c->status_color" /></td>
                    <td>{{ $c->assignedTo?->name ?? '—' }}</td>
                    <td class="text-secondary-custom"><small>{{ $c->created_at->diffForHumans() }}</small></td>
                    <td class="text-end">
                        <a href="{{ route('admin.complaints.show', $c) }}" class="btn btn-link btn-sm">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9"><x-empty-state icon="check-circle" title="No complaints" message="All clear!" /></td></tr>
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-3">{{ $complaints->links() }}</div>
</x-app-layout>
