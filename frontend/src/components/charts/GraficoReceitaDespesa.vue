<template>
  <div class="bg-card border border-gray-700 rounded-xl p-4">
    <h3 class="text-lg font-semibold mb-2">Receitas vs Despesas</h3>
    <canvas ref="chart" class="w-full h-80"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import {
  Chart,
  BarController,
  BarElement,
  LinearScale,
  CategoryScale,
  Tooltip,
  LineController,
  LineElement,
  PointElement,
} from 'chart.js'

Chart.register(
  BarController,
  BarElement,
  LinearScale,
  CategoryScale,
  Tooltip,
  LineController,
  LineElement,
  PointElement,
)

const props = defineProps({
  data: Object, // { labels: [], datasets: [{ receitas: [], despesas: [], saldo: [] }] }
})

const chart = ref(null)
let chartInstance = null

onMounted(() => {
  const dataset = props.data.datasets[0]
  chartInstance = new Chart(chart.value, {
    type: 'bar',
    data: {
      labels: props.data.labels,
      datasets: [
        { label: 'Receitas', data: dataset.receitas, backgroundColor: '#22c55e' },
        { label: 'Despesas', data: dataset.despesas, backgroundColor: '#ef4444' },
        {
          label: 'Saldo',
          data: dataset.saldo,
          type: 'line',
          borderColor: '#eab308',
          backgroundColor: 'transparent',
          borderWidth: 2,
          tension: 0.4,
          fill: false,
          pointRadius: 4,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'top', labels: { color: '#e5e7eb' } } },
      scales: {
        y: { beginAtZero: true, ticks: { color: '#e5e7eb' } },
        x: { ticks: { color: '#e5e7eb' } },
      },
    },
  })
})

watch(
  () => props.data,
  (newData) => {
    if (chartInstance) {
      const ds = newData.datasets[0]
      chartInstance.data.labels = newData.labels
      chartInstance.data.datasets[0].data = ds.receitas
      chartInstance.data.datasets[1].data = ds.despesas
      chartInstance.data.datasets[2].data = ds.saldo
      chartInstance.update()
    }
  },
  { deep: true },
)
</script>
