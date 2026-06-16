<x-app-layout :pageTitle="$complaint->complaint_reference">
    <x-breadcrumb :items="[
        'Complaints' => route('admin.complaints.index'),
        $complaint->complaint_reference => null,
    ]" />

    <x-page-header :title="$complaint->title" :subtitle="$complaint->complaint_reference">
        <x-slot:actions>
            @if($complaint->isPending() || $complaint->isReopened())
                <form method="POST" action="{{ route('admin.complaints.start', $complaint) }}" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-info"><i class="bi bi-play"></i> Start Work</button>
                </form>
            @endif

            @if($complaint->isInProgress() || $complaint->isReopened())
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#resolve-modal">
                    <i class="bi bi-check-lg"></i> Resolve
                </button>
            @endif
        </x-slot:actions>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-8">
            <x-card>
                <div class="d-flex gap-2 mb-3">
                    <x-status-badge :status="$complaint->status_label" :type="$complaint->status_color" />
                    <x-status-badge :status="ucfirst($complaint->priority) . ' Priority'" :type="$complaint->priority_color" />
                    <x-status-badge :status="$complaint->category_label" type="info" />
                </div>

                <h5>Description</h5>
                <p>{{ $complaint->description }}</p>

                @if($complaint->isResolved())
                    <h5 class="mt-4">Resolution Notes</h5>
                    <div class="alert alert-success">
                        <strong>Resolved on {{ formatDateTime($complaint->resolved_at) }}</strong>
                        @if($complaint->resolvedBy) by {{ $complaint->resolvedBy->name }} @endif
                    </div>
                    <p>{{ $complaint->resolution_notes }}</p>
                @endif

                @if($complaint->isReopened() && $complaint->resolution_notes)
                    <h5 class="mt-4">Previous Resolution (Guest Reopened)</h5>
                    <div class="alert alert-warning">
                        <p class="mb-0">{{ $complaint->resolution_notes }}</p>
                    </div>
                @endif
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Guest">
                <x-info-row label="Name"><a href="{{ route('guests.show', $complaint->guest) }}">{{ $complaint->guest->full_name }}</a></x-info-row>
                <x-info-row label="Phone">{{ $complaint->guest->phone }}</x-info-row>
                @if($complaint->room)
                    <x-info-row label="Room">Room {{ $complaint->room->room_number }}</x-info-row>
                @endif
                @if($complaint->booking)
                    <x-info-row label="Booking"><a href="{{ route('bookings.show', $complaint->booking) }}">{{ $complaint->booking->booking_reference }}</a></x-info-row>
                @endif
            </x-card>

            @if(auth()->user()->isAdmin())
                <x-card title="Assignment">
                    <form method="POST" action="{{ route('admin.complaints.assign', $complaint) }}">
                        @csrf
                        @method('PUT')
                        <x-form-select label="Assign to" name="assigned_to" required
                                       :selected="$complaint->assigned_to"
                                       :options="$staff->mapWithKeys(fn($s) => [$s->id => $s->name . ' (' . ucfirst($s->role) . ')'])->toArray()" />
                        <x-form-select label="Update Priority" name="priority"
                                       :selected="$complaint->priority"
                                       :options="['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']" />
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> {{ $complaint->assigned_to ? 'Reassign' : 'Assign' }}
                        </button>
                    </form>
                </x-card>
            @else
                <x-card title="Assignment">
                    @if($complaint->assignedTo)
                        <x-info-row label="Assigned to">{{ $complaint->assignedTo->name }}</x-info-row>
                        <x-info-row label="Assigned at">{{ formatDateTime($complaint->assigned_at) }}</x-info-row>
                    @else
                        <p class="text-secondary-custom mb-0">Not yet assigned. Admin will assign soon.</p>
                    @endif
                </x-card>
            @endif

            <x-card title="Timeline">
                <small class="text-secondary-custom">
                    <strong>Submitted</strong> {{ formatDateTime($complaint->created_at) }}
                    @if($complaint->submittedBy) by {{ $complaint->submittedBy->name }} @endif
                </small>
                @if($complaint->assigned_at)
                    <hr class="my-2">
                    <small class="text-secondary-custom">
                        <strong>Assigned</strong> {{ formatDateTime($complaint->assigned_at) }}
                    </small>
                @endif
                @if($complaint->resolved_at)
                    <hr class="my-2">
                    <small class="text-secondary-custom">
                        <strong>Resolved</strong> {{ formatDateTime($complaint->resolved_at) }}
                    </small>
                @endif
            </x-card>
        </div>
    </div>

    @if($complaint->isInProgress() || $complaint->isReopened())
        <div class="modal fade" id="resolve-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.complaints.resolve', $complaint) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Resolve Complaint</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Describe what was done to resolve this complaint. The guest will see these notes.</p>
                            <div class="mb-3">
                                <label class="form-label">Resolution Notes <span class="text-danger">*</span></label>
                                <textarea name="resolution_notes" rows="5" class="form-control" required minlength="10" maxlength="2000" placeholder="Example: AC repaired, technician inspected, refrigerant added. Tested for 30 minutes, working correctly."></textarea>
                                <small class="form-text text-secondary-custom">Min 10 characters. Be specific about actions taken.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-success"><i class="bi bi-check2-circle"></i> Mark Resolved</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
