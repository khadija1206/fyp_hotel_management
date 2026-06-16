<x-app-layout pageTitle="Reports & Analytics">
    <x-page-header title="Reports & Analytics" subtitle="Hotel performance insights" />

    <div class="alert alert-info mb-4">
        <i class="bi bi-info-circle"></i> <strong>Note:</strong> Revenue and booking reports will become fully functional after the Booking and Billing modules are implemented. For now, this section shows room-related analytics.
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <x-card title="Rooms by Type" subtitle="Distribution across categories">
                @if($roomsByType->isEmpty())
                    <x-empty-state icon="bar-chart" title="No data" message="Add rooms to see the breakdown." />
                @else
                    <canvas id="roomsByTypeChart" height="250"></canvas>
                @endif
            </x-card>
        </div>

        <div class="col-lg-6">
            <x-card title="Coming Soon">
                <ul style="font-size: var(--text-base); line-height: 2; padding-left: var(--space-4);">
                    <li>Daily revenue chart (after billing module)</li>
                    <li>Monthly bookings trend (after booking module)</li>
                    <li>Top-performing room types</li>
                    <li>Average length of stay</li>
                    <li>Guest demographic breakdown</li>
                    <li>Complaint resolution rate</li>
                </ul>
            </x-card>
        </div>
    </div>

    @push('scripts')
    @if($roomsByType->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('roomsByTypeChart');
            if (!ctx || typeof Chart === 'undefined') return;

            const root = getComputedStyle(document.documentElement);
            const colors = [
                root.getPropertyValue('--color-primary').trim(),
                root.getPropertyValue('--color-accent').trim(),
                root.getPropertyValue('--color-primary-light').trim(),
                root.getPropertyValue('--color-accent-dark').trim(),
                root.getPropertyValue('--color-info').trim(),
                root.getPropertyValue('--color-warning').trim(),
            ].filter(Boolean);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($roomsByType->keys()),
                    datasets: [{
                        data: @json($roomsByType->values()),
                        backgroundColor: colors.length ? colors : ['#1A4D5C', '#C28840', '#2A6B7C', '#9E6E33'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
    @endif
    @endpush
</x-app-layout>
