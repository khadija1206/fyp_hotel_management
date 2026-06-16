<x-app-layout pageTitle="Create Booking">
    <x-breadcrumb :items="[
        'Bookings' => route('bookings.index'),
        'New Booking' => null,
    ]" />

    <x-page-header title="Create Booking" />

    <form method="POST" action="{{ route('bookings.store') }}" id="booking-form">
        @csrf

        <x-card title="1. Select Guest">
            <div class="row align-items-end">
                <div class="col-md-9">
                    <x-form-select label="Guest" name="guest_id" required
                                   :selected="$selectedGuest?->id"
                                   :options="$guests->mapWithKeys(fn($g) => [$g->id => $g->full_name . ' — ' . $g->phone])->toArray()" />
                </div>
                <div class="col-md-3">
                    <a href="{{ route('guests.create') }}" class="btn btn-secondary w-100 mb-3">
                        <i class="bi bi-person-plus"></i> Register New
                    </a>
                </div>
            </div>
        </x-card>

        <x-card title="2. Select Dates">
            <div class="row">
                <div class="col-md-6">
                    <x-form-field label="Check-In Date" name="check_in_date" type="date" required />
                </div>
                <div class="col-md-6">
                    <x-form-field label="Check-Out Date" name="check_out_date" type="date" required />
                </div>
            </div>
            <button type="button" id="find-rooms" class="btn btn-secondary">
                <i class="bi bi-search"></i> Find Available Rooms
            </button>
        </x-card>

        <x-card title="3. Select Room">
            <div id="rooms-container">
                <div class="text-secondary-custom small">Pick dates and click "Find Available Rooms".</div>
            </div>
            <input type="hidden" name="room_id" id="selected-room-id">
        </x-card>

        <x-card title="4. Booking Details">
            <div class="row">
                <div class="col-md-4">
                    <x-form-field label="Number of Guests" name="num_guests" type="number" :value="1" required />
                </div>
            </div>
            <x-form-field label="Notes" name="notes" hint="Special requests, dietary preferences, etc." />

            <div class="alert alert-info mt-3" id="price-preview" style="display: none;">
                <strong>Estimated total:</strong> <span id="estimated-total">—</span>
                <div><small id="price-breakdown"></small></div>
            </div>
        </x-card>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>
                <i class="bi bi-check-lg"></i> Create Booking
            </button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>

    @push('scripts')
    <script>
        $(function() {
            const TAX_RATE = {{ \App\Models\Setting::get('tax_rate', 13) }};
            let selectedRoomPrice = 0;

            $('#find-rooms').on('click', function() {
                const checkIn = $('input[name=check_in_date]').val();
                const checkOut = $('input[name=check_out_date]').val();
                if (!checkIn || !checkOut) {
                    showToast('Please select both dates.', 'warning');
                    return;
                }

                $('#rooms-container').html('<div class="text-secondary-custom">Loading…</div>');

                $.get('{{ route("bookings.available-rooms") }}', { check_in_date: checkIn, check_out_date: checkOut })
                    .done(res => {
                        if (res.rooms.length === 0) {
                            $('#rooms-container').html('<div class="alert alert-warning">No rooms available for these dates.</div>');
                            return;
                        }

                        let html = '<div class="row g-2">';
                        res.rooms.forEach(r => {
                            html += `
                                <div class="col-md-4">
                                    <label class="d-block p-3 border rounded cursor-pointer room-option" style="cursor:pointer;">
                                        <input type="radio" name="room_choice" value="${r.id}" data-price="${r.price}" class="me-2">
                                        <strong>Room ${r.room_number}</strong> <small class="text-secondary-custom">Floor ${r.floor}</small>
                                        <div>${r.type} (${r.capacity} guests)</div>
                                        <div class="fw-bold text-primary">${r.price_formatted}<small>/night</small></div>
                                    </label>
                                </div>`;
                        });
                        html += '</div>';
                        $('#rooms-container').html(html);
                    })
                    .fail(() => showToast('Could not load rooms.', 'danger'));
            });

            $(document).on('change', 'input[name=room_choice]', function() {
                $('#selected-room-id').val($(this).val());
                selectedRoomPrice = parseFloat($(this).data('price'));
                $('#submit-btn').prop('disabled', false);
                updatePricePreview();
            });

            function updatePricePreview() {
                const checkIn = new Date($('input[name=check_in_date]').val());
                const checkOut = new Date($('input[name=check_out_date]').val());
                if (!checkIn || !checkOut) return;

                const nights = Math.max(1, Math.round((checkOut - checkIn) / 86400000));
                const subtotal = nights * selectedRoomPrice;
                const tax = subtotal * (TAX_RATE / 100);
                const total = subtotal + tax;

                $('#estimated-total').text('PKR ' + Math.round(total).toLocaleString());
                $('#price-breakdown').text(`${nights} night(s) × PKR ${selectedRoomPrice.toLocaleString()} = PKR ${subtotal.toLocaleString()} + ${TAX_RATE}% tax`);
                $('#price-preview').show();
            }
        });
    </script>
    @endpush
</x-app-layout>
