<div class="space-y-6">
    <flux:heading size="xl">Reports — {{ now()->year }}</flux:heading>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="lg" class="mb-4">Monthly Revenue & Appointments</flux:heading>
        <canvas id="reportsChart" height="100"></canvas>
    </div>
</div>

@script
<script>
    const data = @json($this->chartData);

    const ctx = document.getElementById('reportsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [
                {
                    label: 'Revenue (₱)',
                    data: data.revenue,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    yAxisID: 'y',
                },
                {
                    label: 'Appointments',
                    data: data.appointments,
                    type: 'line',
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    yAxisID: 'y1',
                },
            ],
        },
        options: {
            responsive: true,
            scales: {
                y: { type: 'linear', position: 'left', title: { display: true, text: 'Revenue (₱)' } },
                y1: { type: 'linear', position: 'right', title: { display: true, text: 'Appointments' }, grid: { drawOnChartArea: false } },
            },
        },
    });
</script>
@endscript
