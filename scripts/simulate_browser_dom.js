const { exec } = require('child_process');

// Minimal document / element stubs
const document = {
    elements: {},
    getElementById(id) {
        return this.elements[id] || null;
    },
    createElement(tag) {
        const el = {
            tag,
            className: '',
            id: '',
            innerHTML: '',
            textContent: '',
            children: [],
            appendChild(child) { this.children.push(child); },
            insertBefore(child, before) { this.children.unshift(child); },
            querySelectorAll(sel) {
                // For our simulation, if called on item, return text-muted smalls
                if (sel === '.text-muted') return this._textMuted || [];
                return [];
            },
            querySelector(sel) { return null; }
        };
        return el;
    },
    querySelector(sel) { return null; }
};

global.document = document;

function callPhpSim(id) {
    return new Promise((resolve, reject) => {
        exec(`php scripts/simulate_poll_status.php ${id}`, { cwd: process.cwd() }, (err, stdout, stderr) => {
            if (err) return reject(err);
            try {
                const j = JSON.parse(stdout);
                resolve(j);
            } catch (e) {
                reject(e);
            }
        });
    });
}

// fetch stub that calls the PHP script
function fetchStub(url, opts) {
    // Extract last number as id
    const m = url.match(/(\d+)$/);
    const id = m ? parseInt(m[1], 10) : null;
    return callPhpSim(id).then(json => ({ json: () => Promise.resolve(json) }));
}

// Copy of relevant JS from Blade, adapted to our minimal DOM and fetch stub
function pollNotificationStatus(notificationId, attempts = 0) {
    const maxAttempts = 20;
    const intervalMs = 1000;

    return new Promise((resolve) => {
        function attempt(attempts) {
            if (attempts >= maxAttempts) {
                const statusEl = document.getElementById('notification-status-' + notificationId);
                if (statusEl) statusEl.textContent = 'Gagal (timeout)';
                return resolve({ timeout: true });
            }

            const urlTemplate = `/notifications/NOTIF_ID/status`;
            const url = urlTemplate.replace('NOTIF_ID', notificationId);

            fetchStub(url, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(json => {
                    if (!json.success) throw new Error('No data');

                    const statusEl = document.getElementById('notification-status-' + notificationId);
                    if (json.sent_date || (json.data && json.data.sent)) {
                        if (statusEl) statusEl.textContent = 'Terkirim';
                        // Optionally update timestamp shown
                        const item = document.getElementById('notification-' + notificationId);
                        if (item) {
                            const smalls = item.querySelectorAll('.text-muted');
                            if (smalls && smalls[0]) smalls[0].textContent = (new Date(json.sent_date || json.created_at)).toLocaleDateString('en-GB').replace(/\//g, '/');
                        }
                        return resolve({ sent: true, json });
                    } else if (json.data && json.data.error) {
                        if (statusEl) statusEl.textContent = 'Gagal: ' + json.data.error;
                        return resolve({ error: true, json });
                    } else {
                        setTimeout(() => attempt(attempts + 1), intervalMs);
                    }
                })
                .catch(err => {
                    setTimeout(() => attempt(attempts + 1), intervalMs);
                });
        }

        attempt(attempts);
    });
}

// Prepare simulated DOM nodes for notification id provided as argv[2]
const notifId = parseInt(process.argv[2] || '2', 10);
const statusEl = { id: 'notification-status-' + notifId, textContent: 'Queued' };
const item = {
    id: 'notification-' + notifId,
    _textMuted: [{ textContent: 'Created: --' }],
    querySelectorAll(sel) { if (sel === '.text-muted') return this._textMuted; return []; }
};

// Register in document
document.elements[statusEl.id] = statusEl;
document.elements[item.id] = item;

console.log('Before poll: status=', statusEl.textContent, '; date=', item._textMuted[0].textContent);

pollNotificationStatus(notifId).then(result => {
    console.log('Poll result:', result);
    console.log('After poll: status=', statusEl.textContent, '; date=', item._textMuted[0].textContent);
}).catch(err => {
    console.error('Simulation error', err);
});