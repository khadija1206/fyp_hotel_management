<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receptionist\PaymentStoreRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $service) {}

    public function index(Request $request)
    {
        $query = Payment::with(['booking', 'guest', 'receivedBy']);

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $summaryQuery = clone $query;

        $payments = $query->latest('payment_date')->latest()->paginate(20)->withQueryString();

        $summary = [
            'total_received' => (clone $summaryQuery)->whereNull('voided_at')->where('type', 'payment')->sum('amount'),
            'total_refunded' => (clone $summaryQuery)->whereNull('voided_at')->where('type', 'refund')->sum('amount'),
            'transaction_count' => (clone $summaryQuery)->count(),
        ];

        return view('receptionist.payments.index', compact('payments', 'summary'));
    }

    public function create(Booking $booking)
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Cannot record payment for a cancelled booking.');
        }

        $booking->load(['guest', 'room', 'allPayments']);

        return view('receptionist.payments.create', compact('booking'));
    }

    public function store(PaymentStoreRequest $request, Booking $booking)
    {
        $data = $request->validated();

        if ($data['amount'] > $booking->amount_due + 0.01) {
            return back()->withInput()->with('error',
                'Payment amount exceeds remaining balance of '.formatPKR($booking->amount_due));
        }

        $payment = $this->service->recordPayment($booking, $data);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment of '.formatPKR($payment->amount).' recorded successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.guest', 'booking.room.roomType', 'receivedBy']);

        $hotel = [
            'name' => Setting::get('hotel_name'),
            'address' => Setting::get('hotel_address'),
            'phone' => Setting::get('hotel_phone'),
            'email' => Setting::get('hotel_email'),
        ];

        return view('receptionist.payments.show', compact('payment', 'hotel'));
    }

    public function refundForm(Booking $booking)
    {
        if ($booking->amount_paid <= 0) {
            return back()->with('error', 'No payments to refund.');
        }

        $booking->load('guest', 'room');

        return view('receptionist.payments.refund', compact('booking'));
    }

    public function refund(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$booking->amount_paid,
            'method' => 'required|in:cash,card,bank_transfer,mobile_wallet',
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'required|string|max:500',
        ]);

        $refund = $this->service->recordRefund($booking, $data);

        return redirect()->route('payments.show', $refund)
            ->with('success', 'Refund of '.formatPKR($refund->amount).' issued.');
    }

    public function void(Request $request, Payment $payment)
    {
        if ($payment->isVoided()) {
            return back()->with('error', 'Payment is already voided.');
        }

        $request->validate(['void_reason' => 'required|string|max:500']);

        $this->service->voidPayment($payment, $request->void_reason);

        return back()->with('success', 'Payment voided.');
    }
}
