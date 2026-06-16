<x-app-layout pageTitle="Design System">
    <x-breadcrumb :items="[
        'Dashboard' => route('dashboard'),
        'Design System' => null,
    ]" />

    <x-page-header title="Design System Test Page" subtitle="Visual showcase of all reusable components">
        <x-slot:actions>
            <a href="#" class="btn btn-secondary">Secondary</a>
            <a href="#" class="btn btn-primary">Primary Action</a>
        </x-slot:actions>
    </x-page-header>

    <h2 class="mb-3">Stat Cards</h2>
    <div class="row g-3 mb-5">
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Revenue" value="PKR 245,000" icon="cash-stack" meta="+12% vs last month" metaType="positive" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Occupancy" value="73%" icon="building-fill-check" meta="-3% vs last week" metaType="negative" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Bookings" value="142" icon="calendar-check" meta="Stable" />
        </div>
        <div class="col-md-3 col-sm-6">
            <x-stat-card label="Complaints" value="3" icon="exclamation-triangle" meta="2 resolved" metaType="positive" accent />
        </div>
    </div>

    <h2 class="mb-3">Status Badges</h2>
    <div class="mb-5 d-flex gap-2 flex-wrap">
        <x-status-badge status="Available" type="success" />
        <x-status-badge status="Occupied" type="danger" />
        <x-status-badge status="Reserved" type="warning" />
        <x-status-badge status="Maintenance" type="neutral" />
        <x-status-badge status="Pending" type="info" />
    </div>

    <h2 class="mb-3">Buttons</h2>
    <div class="mb-5 d-flex gap-2 flex-wrap">
        <button class="btn btn-primary">Primary</button>
        <button class="btn btn-secondary">Secondary</button>
        <button class="btn btn-outline-primary">Outline</button>
        <button class="btn btn-success">Success</button>
        <button class="btn btn-danger">Danger</button>
        <button class="btn btn-link">Link</button>
        <button class="btn btn-primary btn-sm">Small</button>
    </div>

    <h2 class="mb-3">Form Fields</h2>
    <x-card>
        <div class="row">
            <div class="col-md-6">
                <x-form-field label="Guest Name" name="test_name" required hint="Full legal name" />
            </div>
            <div class="col-md-6">
                <x-form-field label="Email" name="test_email" type="email" placeholder="guest@example.com" />
            </div>
            <div class="col-md-6">
                <x-form-select label="Room Type" name="test_room" :options="['single' => 'Single', 'double' => 'Double', 'suite' => 'Suite']" />
            </div>
        </div>
    </x-card>

    <h2 class="mb-3 mt-4">Data Table</h2>
    <x-data-table searchable searchPlaceholder="Search...">
        <x-slot:toolbar>
            <a href="#" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Add Row</a>
        </x-slot:toolbar>
        <thead>
            <tr>
                <th>Room</th>
                <th>Guest</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>201</strong></td>
                <td>Ahmed Khan</td>
                <td><x-status-badge status="Occupied" type="danger" /></td>
                <td>{{ formatPKR(15000) }}</td>
                <td><a href="#" class="btn btn-link btn-sm">View</a></td>
            </tr>
            <tr>
                <td><strong>202</strong></td>
                <td>Sarah Malik</td>
                <td><x-status-badge status="Reserved" type="warning" /></td>
                <td>{{ formatPKR(25000) }}</td>
                <td><a href="#" class="btn btn-link btn-sm">View</a></td>
            </tr>
            <tr>
                <td><strong>203</strong></td>
                <td>—</td>
                <td><x-status-badge status="Available" type="success" /></td>
                <td>—</td>
                <td><a href="#" class="btn btn-link btn-sm">View</a></td>
            </tr>
        </tbody>
    </x-data-table>

    <h2 class="mb-3 mt-4">Empty State</h2>
    <x-card>
        <x-empty-state icon="inbox" title="Nothing to show" message="This is what an empty state looks like.">
            <x-slot:action>
                <a href="#" class="btn btn-primary">Add Something</a>
            </x-slot:action>
        </x-empty-state>
    </x-card>

    <h2 class="mb-3 mt-4">Toast Notifications (click to test)</h2>
    <div class="d-flex gap-2 flex-wrap mb-5">
        <button class="btn btn-success" onclick="showToast('Booking saved successfully!', 'success')">Success Toast</button>
        <button class="btn btn-danger" onclick="showToast('Failed to save. Please try again.', 'danger')">Danger Toast</button>
        <button class="btn btn-warning" onclick="showToast('Room is almost full.', 'warning')">Warning Toast</button>
        <button class="btn btn-outline-primary" onclick="showToast('System maintenance at 10 PM.', 'info')">Info Toast</button>
    </div>
</x-app-layout>
