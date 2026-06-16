(function () {
    const gridEl = document.getElementById('layout-grid');
    if (!gridEl || typeof GridStack === 'undefined') return;

    const grid = GridStack.init({
        column: 12,
        cellHeight: 90,
        margin: 6,
        float: true,
        animate: true,
        resizable: { handles: 'se, sw, ne, nw' },
    }, '#layout-grid');

    const saveBtn = document.getElementById('save-layout-btn');
    const saveUrl = document.querySelector('meta[name="layout-save-url"]')?.content;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!saveBtn || !saveUrl) return;

    let hasChanges = false;
    grid.on('change', () => { hasChanges = true; });

    saveBtn.addEventListener('click', () => {
        const savedItems = grid.save(false);

        const positions = savedItems.map(item => {
            const roomId = item.el?.dataset?.roomId;
            return {
                id: parseInt(roomId, 10),
                x: item.x,
                y: item.y,
                w: item.w,
                h: item.h,
            };
        }).filter(p => p.id);

        if (positions.length === 0) {
            window.showToast && window.showToast('Nothing to save', 'warning');
            return;
        }

        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving…';

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ positions }),
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    hasChanges = false;
                    window.showToast && window.showToast(data.message || 'Layout saved!', 'success');
                } else {
                    window.showToast && window.showToast('Save failed. Please try again.', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                window.showToast && window.showToast('Network error. Please try again.', 'danger');
            })
            .finally(() => {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="bi bi-cloud-arrow-up"></i> Save Layout';
            });
    });

    window.addEventListener('beforeunload', (e) => {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved layout changes. Are you sure you want to leave?';
        }
    });
})();
