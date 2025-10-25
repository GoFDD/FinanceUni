<template>
  <div class="bg-[#18181b] border border-[#27272a] rounded-lg p-6 shadow">
    <h3 class="text-lg font-semibold mb-4">Fluxo de Caixa</h3>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const chart = ref(null)

onMounted(() => {
  new Chart(chart.value, {
    type: 'line',
    data: {
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
      datasets: [
        {
          label: 'Receitas',
          data: [1200, 1900, 3000, 2500, 3200, 4000],
          borderColor: '#22c55e',
          backgroundColor: 'rgba(34, 197, 94, 0.2)',
          fill: true,
          tension: 0.4,
        },
        {
          label: 'Despesas',
          data: [1000, 1500, 2000, 2200, 1800, 2400],
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239, 68, 68, 0.2)',
          fill: true,
          tension: 0.4,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#fafafa' } } },
      scales: {
        x: { ticks: { color: '#a1a1aa' }, grid: { color: '#27272a' } },
        y: { ticks: { color: '#a1a1aa' }, grid: { color: '#27272a' } },
      },
    },
  })
})
</script>
