<x-app-layout pageTitle="{{ $payment->payment_reference }}">
    <div class="d-flex justify-content-between mb-3 d-print-none">
        <x-breadcrumb :items="[
            'Payments' => route('payments.index'),
            $payment->payment_reference => null,
        ]" />
        <div>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Print Receipt
            </button>
            @if(!$payment->isVoided() && !$payment->isRefund())
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#void-modal">
                    <i class="bi bi-x-circle"></i> Void
                </button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded p-5 shadow-sm" id="printable-receipt" style="max-width: 700px; margin: 0 auto;">

        <div class="d-flex justify-content-between border-bottom pb-3 mb-4">
            <div>
                <h2 class="mb-1" style="color: var(--color-primary);">{{ $hotel['name'] }}</h2>
                <small class="text-secondary-custom">
                    {{ $hotel['address'] }}<br>
                    {{ $hotel['phone'] }} | {{ $hotel['email'] }}
                </small>
            </div>
            <div class="text-end">
                <h3 class="mb-1">{{ $payment->isRefund() ? 'REFUND' : 'RECEIPT' }}</h3>
                <small class="text-secondary-custom">
                    Ref: <strong>{{ $payment->payment_reference }}</strong><br>
                    Date: {{ formatDate($payment->payment_date) }}
                </small>
            </div>
        </div>

        @if($payment->isVoided())
            <div class="alert alert-danger text-center mb-4">
                <strong>VOIDED</strong> on {{ formatDateTime($payment->voided_at) }}<br>
                Reason: {{ $payment->void_reason }}
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="text-secondary-custom">Received From</h5>
                <strong>{{ $payment->guest->full_name }}</strong><br>
                {{ $payment->guest->phone }}
            </div>
            <div class="col-md-6 text-md-end">
                <h5 class="text-secondary-custom">Booking</h5>
                <strong>{{ $payment->booking->booking_reference }}</strong><br>
                Room {{ $payment->booking->room->room_number }} — {{ $payment->booking->room->roomType->name }}<br>
                {{ formatDate($payment->booking->check_in_date) }} → {{ formatDate($payment->booking->check_out_date) }}
            </div>
        </div>

        <table class="table">
            <tr>
                <th>Description</th>
                <th class="text-end">Amount</th>
            </tr>
            <tr>
                <td>
                    {{ $payment->isRefund() ? 'Refund' : 'Payment' }} via <strong>{{ $payment->method_label }}</strong>
                    @if($payment->transaction_id)<br><small class="text-secondary-custom">Transaction ID: {{ $payment->transaction_id }}</small>@endif
                    @if($payment->notes)<br><small class="text-secondary-custom">{{ $payment->notes }}</small>@endif
                </td>
                <td class="text-end">
                    <strong style="font-size: var(--text-xl); color: var(--color-primary);">
                        {{ $payment->isRefund() ? '-' : '' }}{{ formatPKR($payment->amount) }}
                    </strong>
                </td>
            </tr>
        </table>

        <div class="p-3 rounded mt-3" style="background-color: var(--color-bg-subtle);">
            <div class="row">
                <div class="col-6">Total Bill:</div>
                <div class="col-6 text-end">{{ formatPKR($payment->booking->total_amount) }}</div>
            </div>
            <div class="row">
                <div class="col-6">Total Paid (to date):</div>
                <div class="col-6 text-end">{{ formatPKR($payment->booking->amount_paid) }}</div>
            </div>
            <div class="row pt-2 mt-2 border-top">
                <div class="col-6"><strong>Balance Due:</strong></div>
                <div class="col-6 text-end"><strong>{{ formatPKR($payment->booking->amount_due) }}</strong></div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
            <small class="text-secondary-custom">
                Received by: <strong>{{ $payment->receivedBy?->name ?? 'System' }}</strong>
            </small>
            <small class="text-secondary-custom">Thank you!</small>
        </div>
    </div>

    @if(!$payment->isVoided() && !$payment->isRefund())
        <div class="modal fade" id="void-modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('payments.void', $payment) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Void Payment?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>This will void payment <strong>{{ $payment->payment_reference }}</strong> of {{ formatPKR($payment->amount) }}. The record will be preserved but won't count toward the booking total.</p>
                            <x-form-field label="Reason for void" name="void_reason" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger">Yes, Void Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <style>
        @media print {
            body { background: white !important; }
            .app-sidebar, .app-topbar, .breadcrumb-custom, .d-print-none { display: none !important; }
            .app-main { margin-left: 0 !important; }
            #printable-receipt { box-shadow: none !important; max-width: 100% !important; }
            .app-content { padding: 0 !important; }
        }
    </style>
    @endpush
</x-app-layout>
