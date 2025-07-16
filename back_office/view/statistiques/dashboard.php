<div class="container">
    <h2>Tableau de bord des Statistiques</h2>
    <canvas id="statsChart"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo "'" . implode("','", array_column($stats, 'titre')) . "'"; ?>],
                datasets: [{
                    label: 'Vues',
                    data: [<?php echo implode(',', array_column($stats, 'vues')); ?>],
                    backgroundColor: '#007bff'
                }, {
                    label: 'Clics',
                    data: [<?php echo implode(',', array_column($stats, 'clics')); ?>],
                    backgroundColor: '#28a745'
                }]
            },
            options: {
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</div>