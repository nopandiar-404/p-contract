const fs = require('fs');
const path = require('path');

const filePath = path.join(__dirname, '..', 'resources', 'views', 'dashboard', 'index.blade.php');
let content = fs.readFileSync(filePath, 'utf8');

// Normalize CRLF to LF temporarily for matching
const isCRLF = content.includes('\r\n');
if (isCRLF) {
    content = content.replace(/\r\n/g, '\n');
}

const oldBlock = `            function updateContractsChart(labels, values) {
                if (contractsChart) {
                    contractsChart.data.labels = labels;
                    contractsChart.data.datasets[0].data = values;
                    contractsChart.options.scales.y.suggestedMax = Math.max(...values.map(Number), 0) + 1;
                    contractsChart.update();
                    return;
                }

                renderFallbackLineChart(chartCanvas, labels, values);
            }

            async function fetchTimeSeries(period) {
                try {
                    const res = await fetch(\`/reports/distribution?period=\${encodeURIComponent(period)}\`, {
                        credentials: 'include'
                    });
                    if (!res.ok) {
                        showToast('Gagal memuat data chart', 'error');
                        return;
                    }
                    const json = await res.json();
                    if (!json || !json.success || !json.data) {
                        showToast('Data chart tidak valid', 'error');
                        return;
                    }

                    const payload = json.data;
                    // payload expected { labels: [...], counts: [...] }
                    if (payload && Array.isArray(payload.labels) && Array.isArray(payload.counts)) {
                        updateContractsChart(payload.labels, payload.counts);
                        return;
                    }

                    // fallback: associative counts -> simple pie-like labels
                    if (payload && typeof payload === 'object') {
                        const labels = ['Aktif', 'Masa Tenggang', 'Kadaluarsa'];
                        const counts = [payload.active || 0, payload.grace_period || 0, payload.expired || 0];
                        updateContractsChart(labels, counts);
                        return;
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Kesalahan saat memuat chart', 'error');
                }
            }

            function changeChartPeriod(period) {
                if (!['monthly', 'quarterly'].includes(period)) period = 'monthly';
                currentChartPeriod = period;
                const btn = document.getElementById('contractsChartPeriodBtn');
                if (btn) btn.querySelector('.period-label').textContent = period === 'monthly' ? 'Per Bulan' : 'Per Kuartal';
                fetchTimeSeries(period);
            }

            // Initialize chart: request server time-series
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-progress-width]').forEach(progressBar => {
                    const width = Number(progressBar.dataset.progressWidth);
                    progressBar.style.width = \`\${Math.min(100, Math.max(0, width))}%\`;
                });

                changeChartPeriod(currentChartPeriod);
            });`;

const newBlock = `            function updateContractsChart(labels, values) {
                if (!Array.isArray(labels) || !Array.isArray(values)) return;
                const numValues = values.map(v => Number(v) || 0);

                if (contractsChart) {
                    contractsChart.data.labels = labels;
                    if (contractsChart.data.datasets && contractsChart.data.datasets[0]) {
                        contractsChart.data.datasets[0].data = numValues;
                    }
                    if (contractsChart.options && contractsChart.options.scales && contractsChart.options.scales.y) {
                        contractsChart.options.scales.y.suggestedMax = Math.max(...numValues, 0) + 1;
                    }
                    contractsChart.update();
                    return;
                }

                renderFallbackLineChart(chartCanvas, labels, numValues);
            }

            async function fetchTimeSeries(period) {
                try {
                    const url = "{{ route('reports.distribution') }}?period=" + encodeURIComponent(period);
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });

                    if (!res.ok) {
                        console.warn('Gagal memuat data chart dari server. Status:', res.status);
                        return;
                    }

                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        console.warn('Respons chart bukan format JSON:', contentType);
                        return;
                    }

                    const json = await res.json();
                    if (!json || !json.success || !json.data) {
                        console.warn('Data chart tidak valid dari server:', json);
                        return;
                    }

                    const payload = json.data;
                    // payload expected { labels: [...], counts: [...] }
                    if (payload && Array.isArray(payload.labels) && Array.isArray(payload.counts)) {
                        updateContractsChart(payload.labels, payload.counts);
                        return;
                    }

                    // fallback: associative counts -> simple pie-like labels
                    if (payload && typeof payload === 'object') {
                        const labels = ['Aktif', 'Masa Tenggang', 'Kadaluarsa'];
                        const counts = [payload.active || 0, payload.grace_period || 0, payload.expired || 0];
                        updateContractsChart(labels, counts);
                        return;
                    }
                } catch (err) {
                    console.error('Kesalahan saat memuat data chart:', err);
                }
            }

            function changeChartPeriod(period) {
                if (!['monthly', 'quarterly'].includes(period)) period = 'monthly';
                currentChartPeriod = period;
                const btn = document.getElementById('contractsChartPeriodBtn');
                if (btn) {
                    const labelSpan = btn.querySelector('.period-label');
                    if (labelSpan) labelSpan.textContent = period === 'monthly' ? 'Per Bulan' : 'Per Kuartal';
                }
                fetchTimeSeries(period);
            }

            // Initialize chart
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-progress-width]').forEach(progressBar => {
                    const width = Number(progressBar.dataset.progressWidth);
                    progressBar.style.width = \`\${Math.min(100, Math.max(0, width))}%\`;
                });

                // Chart awal sudah dirender langsung dengan data default (monthly) dari server HTML
                // fetchTimeSeries dipanggil hanya saat user memilih filter periode
            });`;

if (!content.includes(oldBlock)) {
    console.error('oldBlock not found in content!');
    process.exit(1);
}

content = content.replace(oldBlock, newBlock);

if (isCRLF) {
    content = content.replace(/\n/g, '\r\n');
}

fs.writeFileSync(filePath, content, 'utf8');
console.log('Successfully patched index.blade.php');

