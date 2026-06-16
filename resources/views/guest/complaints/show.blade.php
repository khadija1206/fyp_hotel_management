<x-guest-portal-layout :title="$complaint->complaint_reference">
    <x-breadcrumb :items="[
        'My Complaints' => route('guest.complaints.index'),
        $complaint->complaint_reference => null,
    ]" />

    <x-page-header :title="$complaint->title" :subtitle="'Reference: ' . $complaint->complaint_reference" />

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

                @if($complaint->room)
                    <x-info-row label="Room">Room {{ $complaint->room->room_number }}</x-info-row>
                @endif
                @if($complaint->booking)
                    <x-info-row label="Booking">{{ $complaint->booking->booking_reference }}</x-info-row>
                @endif
                <x-info-row label="Submitted">{{ formatDateTime($complaint->created_at) }}</x-info-row>

                @if($complaint->assignedTo)
                    <x-info-row label="Handling Staff">{{ $complaint->assignedTo->name }}</x-info-row>
                @endif

                @if($complaint->isResolved())
                    <h5 class="mt-4">Resolution</h5>
                    <div class="alert alert-success">
                        <strong>Resolved on {{ formatDateTime($complaint->resolved_at) }}</strong>
                        @if($complaint->resolvedBy)
                            by {{ $complaint->resolvedBy->name }}
                        @endif
                    </div>
                    <p>{{ $complaint->resolution_notes }}</p>

                    <hr>
                    <p class="text-secondary-custom">
                        If you're not satisfied with this resolution, you can reopen the complaint and our team will look into it again.
                    </p>
                    <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reopen-modal">
                        <i class="bi bi-arrow-counterclockwise"></i> Reopen Complaint
                    </button>
                @endif
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Status Timeline">
                <div class="d-flex align-items-start gap-2 mb-3">
                    <div class="rounded-circle bg-success" style="width: 12px; height: 12px; margin-top: 6px;"></div>
                    <div>
                        <strong>Submitted</strong>
                        <div><small class="text-secondary-custom">{{ formatDateTime($complaint->created_at) }}</small></div>
                    </div>
                </div>

                @if($complaint->assigned_at)
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle bg-info" style="width: 12px; height: 12px; margin-top: 6px;"></div>
                        <div>
                            <strong>Assigned</strong>
                            <div><small class="text-secondary-custom">{{ formatDateTime($complaint->assigned_at) }}</small></div>
                        </div>
                    </div>
                @endif

                @if(in_array($complaint->status, ['in_progress', 'resolved', 'reopened']))
                    <div class="d-flex align-items-start gap-2 mb-3">
                        <div class="rounded-circle bg-warning" style="width: 12px; height: 12px; margin-top: 6px;"></div>
                        <div>
                            <strong>In Progress</strong>
                            <div><small class="text-secondary-custom">Work started</small></div>
                        </div>
                    </div>
                @endif

                @if($complaint->resolved_at)
                    <div class="d-flex align-items-start gap-2">
                        <div class="rounded-circle bg-success" style="width: 12px; height: 12px; margin-top: 6px;"></div>
                        <div>
                            <strong>{{ $complaint->isReopened() ? 'Was Resolved' : 'Resolved' }}</strong>
                            <div><small class="text-secondary-custom">{{ formatDateTime($complaint->resolved_at) }}</small></div>
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    @if($complaint->isResolved())
        <div class="modal fade" id="reopen-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('guest.complaints.reopen', $complaint) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Reopen Complaint?</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>If the issue is not resolved, please tell us why so we can address it properly.</p>
                            <x-form-field label="Why are you reopening this?" name="reopen_reason" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-warning">Reopen Complaint</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</x-guest-portal-layout>
