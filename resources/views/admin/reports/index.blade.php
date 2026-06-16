<x-app-layout pageTitle="Reports & Analytics">
    <x-page-header title="Reports & Analytics" subtitle="Financial and operational insights" />

    <x-card>
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">From</label>
                <input type="date" name="date_from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label">To</label>
                <input type="date" name="date_to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-3"><button class="btn btn-primary"><i class="bi bi-funnel"></i> Apply Filter</button></div>
            <div class="col-md-3 text-end small text-secondary-custom">
                Showing data from {{ formatDate($from) }} to {{ formatDate($to) }}
            </div>
        </form>
    </x-card>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <x-stat-card label="Net Revenue" :value="formatPKR($summary['net_revenue'])" icon="cash-stack" accent />
        </div>
        <div class="col-md-3">
            <x-stat-card label="Total Received" :value="formatPKR($summary['total_received'])" icon="arrow-down-circle" />
        </div>
        <div class="col-md-3">
            <x-stat-card label="Total Refunded" :value="formatPKR($summary['total_refunded'])" icon="arrow-up-circle" />
        </div>
        <div class="col-md-3">
            <x-stat-card label="Transactions" :value="$summary['transaction_count']" icon="receipt" />
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <x-card title="Daily Revenue" subtitle="Net revenue per day in selected range">
                @if($dailyRevenue->isEmpty())
                    <x-empty-state icon="graph-up" title="No revenue data" message="No transactions in the selected date range." />
                @else
                    <canvas id="revenueChart" height="300"></canvas>
                @endif
            </x-card>
        </div>
        <div class="col-lg-4">
            <x-card title="Payment Methods" subtitle="Distribution of payment methods">
                @if($paymentMethods->isEmpty())
                    <x-empty-state icon="credit-card" title="No data" message="No payments in this range." />
                @else
                    <canvas id="methodsChart" height="250"></canvas>
                @endif
            </x-card>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-card title="Rooms by Type">
                @if($roomsByType->isEmpty())
                    <x-empty-state icon="door-closed" title="No rooms" message="Add rooms to see breakdown." />
                @else
                    <canvas id="roomsByTypeChart" height="220"></canvas>
                @endif
            </x-card>
        </div>
        <div class="col-lg-6">
            <x-card title="Coming Soon">
                <ul style="line-height: 2; padding-left: var(--space-4);">
                    <li>Booking volume trends</li>
                    <li>Average length of stay</li>
                    <li>Top guests by spend</li>
                    <li>Complaint resolution metrics (after complaint module)</li>
                    <li>Occupancy heatmap</li>
                </ul>
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revCtx = document.getElementById('revenueChart');
            @if($dailyRevenue->isNotEmpty())
                new Chart(revCtx, {
                    type: 'bar',
                    data: {
                        labels: @json($dailyRevenue->pluck('payment_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
                        datasets: [{
                            label: 'Net Revenue (PKR)',
                            data: @json($dailyRevenue->pluck('net_revenue')),
                            backgroundColor: '#1A4D5C',
                            borderRadius: 4,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            @endif

            const methCtx = document.getElementById('methodsChart');
            @if($paymentMethods->isNotEmpty())
                new Chart(methCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($paymentMethods->keys()->map(fn($m) => ucwords(str_replace('_', ' ', $m)))),
                        datasets: [{
                            data: @json($paymentMethods->values()),
                            backgroundColor: ['#1A4D5C', '#C28840', '#2A6B7C', '#4A7CC4'],
                            borderWidth: 0,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                });
            @endif

            const roomCtx = document.getElementById('roomsByTypeChart');
            @if($roomsByType->isNotEmpty())
                new Chart(roomCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($roomsByType->keys()),
                        datasets: [{
                            data: @json($roomsByType->values()),
                            backgroundColor: ['#1A4D5C', '#C28840', '#2A6B7C', '#9E6E33', '#4A7CC4'],
                            borderWidth: 0,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>
