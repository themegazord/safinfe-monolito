<div>
  <h5>Valores total por dia de todas as notas mensais</h5>
  <div class="chart-container" style="position: relative; width:80vw">
    <canvas id="relatorioTotalNotasPorDiaMes"></canvas>
  </div>
  @assets
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @endassets
  @script
  <script>
    const ctx = document.getElementById('relatorioTotalNotasPorDiaMes');
    const dados = $wire.dadosAtual;
    const labels = dados.map(item => item.y)
    const values = dados.map(item => item.value)
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Tota de vendas por dia',
          data: values,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
  @endscript
</div>
