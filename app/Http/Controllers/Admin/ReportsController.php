<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date_from ?? now()->subDays(30)->toDateString();
        $to = $request->date_to ?? today()->toDateString();

        $dailyRevenue = Payment::whereNull('voided_at')
            ->whereBetween('payment_date', [$from, $to])
            ->selectRaw('payment_date, SUM(CASE WHEN type = "payment" THEN amount ELSE -amount END) as net_revenue')
            ->groupBy('payment_date')
            ->orderBy('payment_date')
            ->get();

        $paymentMethods = Payment::whereNull('voided_at')
            ->where('type', 'payment')
            ->whereBetween('payment_date', [$from, $to])
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');

        $summary = [
            'total_received' => Payment::whereNull('voided_at')->where('type', 'payment')->whereBetween('payment_date', [$from, $to])->sum('amount'),
            'total_refunded' => Payment::whereNull('voided_at')->where('type', 'refund')->whereBetween('payment_date', [$from, $to])->sum('amount'),
            'transaction_count' => Payment::whereNull('voided_at')->whereBetween('payment_date', [$from, $to])->count(),
        ];
        $summary['net_revenue'] = $summary['total_received'] - $summary['total_refunded'];

        $roomsByType = Room::with('roomType')->get()->groupBy('roomType.name')->map(fn ($g) => $g->count());

        return view('admin.reports.index', compact('dailyRevenue', 'paymentMethods', 'summary', 'roomsByType', 'from', 'to'));
    }
}
