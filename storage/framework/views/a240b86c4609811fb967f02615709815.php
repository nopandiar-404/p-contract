<!-- Shared partial: WhatsApp health card + script -->
<div class="mb-3" id="whatsappHealthCard">
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex align-items-center">
            <div>
                <strong>WhatsApp Integration</strong>
                <div id="whatsappHealth" class="text-muted">Checking configuration&hellip;</div>
            </div>
            <div class="ms-auto d-flex align-items-center">
                <button id="whatsappHealthRefresh" class="btn btn-sm btn-outline-secondary me-2" type="button"
                    title="Refresh WhatsApp status">
                    <i class="fas fa-sync-alt"></i>
                </button>
                <span id="whatsappHealthBadge" class="badge bg-secondary">Unknown</span>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function maskPhoneId(id) {
            if (!id) return null;
            const s = String(id);
            if (s.length <= 4) return '••••' + s;
            return '••••' + s.slice(-4);
        }

        function setCheckingState() {
            const badgeEl = document.getElementById('whatsappHealthBadge');
            const healthEl = document.getElementById('whatsappHealth');
            if (!badgeEl || !healthEl) return;
            badgeEl.className = 'badge bg-secondary';
            badgeEl.textContent = 'Checking...';
            healthEl.textContent = 'Checking configuration…';
        }

        function updateState(data) {
            const badgeEl = document.getElementById('whatsappHealthBadge');
            const healthEl = document.getElementById('whatsappHealth');
            if (!badgeEl || !healthEl) return;

            if (data && data.configured) {
                const masked = maskPhoneId(data.phone_number_id) || 'set';
                healthEl.textContent = 'Configured — phone id: ' + masked;
                badgeEl.textContent = 'Configured';
                badgeEl.className = 'badge bg-success';
            } else {
                healthEl.textContent = 'Not configured';
                badgeEl.textContent = 'Not configured';
                badgeEl.className = 'badge bg-danger';
            }
        }

        function handleError(err) {
            const badgeEl = document.getElementById('whatsappHealthBadge');
            const healthEl = document.getElementById('whatsappHealth');
            if (!badgeEl || !healthEl) return;
            console.error('Health check failed', err);
            healthEl.textContent = 'Health check error';
            badgeEl.textContent = 'Error';
            badgeEl.className = 'badge bg-secondary';
        }

        function checkWhatsAppHealth() {
            setCheckingState();
            fetch('/debug/health/whatsapp', {
                    headers: {
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(updateState)
                .catch(handleError);
        }

        // Expose for manual refresh
        window.checkWhatsAppHealth = checkWhatsAppHealth;

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('whatsappHealthRefresh');
            if (btn) btn.addEventListener('click', function() {
                checkWhatsAppHealth();
            });
            // initial check
            checkWhatsAppHealth();
        });
    })();
</script>
<?php /**PATH D:\DASHBOARD\p-contract\resources\views\dashboard\partials\whatsapp-health.blade.php ENDPATH**/ ?>