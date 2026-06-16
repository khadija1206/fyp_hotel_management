(function () {
    const container = document.getElementById('fp-container');
    if (!container) return;

    let currentFloor = parseInt(container.dataset.currentFloor, 10);
    let selectedRoomId = null;
    let pollInterval = null;

    document.querySelectorAll('#fp-tabs .fp-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            const newFloor = parseInt(tab.dataset.floor, 10);
            if (newFloor === currentFloor) return;

            document.querySelectorAll('#fp-tabs .fp-tab').forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            currentFloor = newFloor;
            container.dataset.currentFloor = newFloor;
            loadFloor(newFloor);
        });
    });

    function loadFloor(floor) {
        fetch(`/floor-plan/floor/${floor}`)
            .then(r => r.json())
            .then(data => {
                renderRooms(data.rooms);
                updateStats(data.stats);
                const label = document.querySelector('.fp-floor-label');
                if (label) label.textContent = `Floor ${floor}`;
                resetSidePanel();
                restartPolling();
            })
            .catch(err => {
                console.error('Failed to load floor:', err);
                window.showToast && window.showToast('Failed to load floor data', 'danger');
            });
    }

    function roomLayout(room, idx) {
        const hasLayout = room.width > 0 && room.height > 0;
        return {
            x: hasLayout ? room.position_x : (idx % 4) * 3,
            y: hasLayout ? room.position_y : Math.floor(idx / 4) * 2,
            w: hasLayout ? room.width : 3,
            h: hasLayout ? room.height : 2,
        };
    }

    function renderRooms(rooms) {
        const grid = document.getElementById('fp-grid');
        grid.innerHTML = '';

        rooms.forEach((room, idx) => {
            const layout = roomLayout(room, idx);
            const div = document.createElement('div');
            div.className = `fp-room fp-${room.status}`;
            div.dataset.roomId = room.id;
            div.style.gridColumn = `${layout.x + 1} / span ${layout.w}`;
            div.style.gridRow = `${layout.y + 1} / span ${layout.h}`;
            div.tabIndex = 0;
            div.setAttribute('role', 'button');
            div.setAttribute('aria-label', `Room ${room.room_number}, ${room.status}`);

            div.innerHTML = `
                <div class="fp-room-num">${room.room_number}</div>
                <div class="fp-room-bed">${bedIcon(room.bed_layout)}</div>
                <div class="fp-room-status">${room.status_label}</div>
            `;

            grid.appendChild(div);
        });

        const corridor = document.createElement('div');
        corridor.className = 'fp-corridor';
        corridor.style.gridColumn = '1 / -1';
        corridor.textContent = 'corridor';
        grid.appendChild(corridor);
    }

    document.addEventListener('click', (e) => {
        const room = e.target.closest('.fp-room');
        if (!room) return;

        document.querySelectorAll('.fp-room').forEach(r => r.classList.remove('selected'));
        room.classList.add('selected');

        selectedRoomId = room.dataset.roomId;
        loadRoomDetail(selectedRoomId);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            const room = e.target.closest('.fp-room');
            if (room) {
                e.preventDefault();
                room.click();
            }
        }
    });

    function loadRoomDetail(roomId) {
        const panel = document.getElementById('fp-side-panel');
        panel.innerHTML = '<div class="text-center p-4"><span class="text-secondary-custom">Loading…</span></div>';

        fetch(`/floor-plan/rooms/${roomId}/detail`)
            .then(r => r.json())
            .then(data => renderSidePanel(data))
            .catch(err => {
                console.error('Failed to load room:', err);
                window.showToast && window.showToast('Failed to load room details', 'danger');
            });
    }

    function renderSidePanel(data) {
        const panel = document.getElementById('fp-side-panel');
        const r = data.room;
        const b = data.booking;

        let html = `
            <div class="fp-side-header">
                <div>
                    <h2 class="fp-side-room-num">Room ${r.room_number}</h2>
                    <small class="text-secondary-custom">${r.type} — Floor ${r.floor}</small>
                </div>
                <span class="status-badge ${r.status_color}">${r.status_label}</span>
            </div>
            <div class="fp-side-section">
                <h5>Room Information</h5>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Type</span><span class="fw-medium">${r.type}</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Capacity</span><span class="fw-medium">${r.capacity} guests</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Bed Layout</span><span class="fw-medium">${r.bed_layout.charAt(0).toUpperCase() + r.bed_layout.slice(1)} (${r.bed_count})</span></div>
                <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Rate</span><span class="fw-medium">${r.price}/night</span></div>
            </div>
        `;

        if (b) {
            html += `
                <div class="fp-side-section">
                    <h5>Current ${b.status === 'checked_in' ? 'Stay' : 'Reservation'}</h5>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Guest</span><a href="/guests/${b.guest.id}" class="fw-medium">${escapeHtml(b.guest.name)}</a></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Phone</span><span class="fw-medium">${escapeHtml(b.guest.phone)}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Check-In</span><span class="fw-medium">${b.check_in_date}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Check-Out</span><span class="fw-medium">${b.check_out_date}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Booking Ref</span><a href="/bookings/${b.id}" class="fw-medium">${b.reference}</a></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-secondary-custom">Total</span><span class="fw-medium">${b.total}</span></div>
                </div>
            `;
        }

        if (r.amenities && r.amenities.length > 0) {
            html += '<div class="fp-side-section"><h5>Amenities</h5><div>';
            r.amenities.forEach(a => { html += `<span class="fp-amenity-chip">${escapeHtml(a)}</span>`; });
            html += '</div></div>';
        }

        if (r.notes) {
            html += `<div class="fp-side-section"><h5>Notes</h5><p class="mb-0 text-secondary-custom small">${escapeHtml(r.notes)}</p></div>`;
        }

        if (data.links && data.links.length > 0) {
            html += '<div class="fp-actions">';
            data.links.forEach(link => {
                html += `<a href="${link.url}" class="btn btn-${link.variant} btn-sm"><i class="bi bi-${link.icon}"></i> ${link.label}</a>`;
            });
            html += '</div>';
        }

        panel.innerHTML = html;
    }

    function resetSidePanel() {
        selectedRoomId = null;
        document.getElementById('fp-side-panel').innerHTML = `
            <div class="fp-side-panel-empty">
                <i class="bi bi-hand-index-thumb"></i>
                <h5>Select a Room</h5>
                <p class="mb-0">Click any room on the floor plan to see details and available actions.</p>
            </div>
        `;
    }

    function updateStats(stats) {
        Object.keys(stats).forEach(key => {
            const el = document.querySelector(`[data-stat="${key}"]`);
            if (el) el.textContent = stats[key];
        });
    }

    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(pollStatuses, 15000);
    }

    function restartPolling() {
        startPolling();
    }

    function pollStatuses() {
        fetch(`/floor-plan/poll/${currentFloor}`)
            .then(r => r.json())
            .then(data => {
                data.rooms.forEach(room => {
                    const el = document.querySelector(`.fp-room[data-room-id="${room.id}"]`);
                    if (!el) return;

                    if (!el.classList.contains(`fp-${room.status}`)) {
                        el.classList.remove('fp-available', 'fp-occupied', 'fp-reserved', 'fp-maintenance');
                        el.classList.add(`fp-${room.status}`);
                        const label = el.querySelector('.fp-room-status');
                        if (label) label.textContent = room.status_label;
                    }
                });
                updateStats(data.stats);
            })
            .catch(err => console.warn('Poll failed:', err));
    }

    function bedIcon(layout) {
        const icons = {
            single: '<svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="6" width="30" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><rect x="5" y="9" width="10" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/><line x1="2" y1="20" x2="2" y2="23" stroke="currentColor" stroke-width="1.5"/><line x1="32" y1="20" x2="32" y2="23" stroke="currentColor" stroke-width="1.5"/></svg>',
            double: '<svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="6" width="36" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><rect x="5" y="9" width="14" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/><rect x="21" y="9" width="14" height="6" rx="1" stroke="currentColor" stroke-width="1.2"/></svg>',
            twin: '<svg viewBox="0 0 40 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><rect x="22" y="6" width="16" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/></svg>',
            suite: '<svg viewBox="0 0 48 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="4" width="26" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><rect x="32" y="12" width="14" height="8" rx="1.5" stroke="currentColor" stroke-width="1.5"/></svg>',
        };
        return icons[layout] || icons.single;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    startPolling();

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            if (pollInterval) clearInterval(pollInterval);
        } else {
            startPolling();
            pollStatuses();
        }
    });
})();
