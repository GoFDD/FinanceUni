<template>
  <div class="bg-card border border-gray-700 rounded-xl p-4">
    <h3 class="text-lg font-semibold mb-2">Evolução do Patrimônio</h3>
    <p class="text-sm text-gray-400 mb-4">Últimos 12 meses</p>
    <canvas ref="chart" class="w-full h-80"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import {
  Chart,
  LineController,
  LineElement,
  PointElement,
  LinearScale,
  Title,
  CategoryScale,
  Filler,
  Tooltip,
} from 'chart.js'

Chart.register(
  LineController,
  LineElement,
  PointElement,
  LinearScale,
  Title,
  CategoryScale,
  Filler,
  Tooltip,
)

const props = defineProps({
  data: Object,
})

const chart = ref(null)
let chartInstance = null

onMounted(() => {
  chartInstance = new Chart(chart.value, {
    type: 'line',
    data: {
      labels: props.data.labels,
      datasets: props.data.datasets.map((ds) => ({
        label: ds.label || '',
        data: ds.data,
        borderColor: ds.color || '#22c55e',
        backgroundColor: ds.fillColor || 'rgba(34, 197, 94, 0.3)',
        fill: true,
        tension: 0.4,
      })),
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true },
        x: { ticks: { color: '#e5e7eb' } },
      },
    },
  })
})

watch(
  () => props.data,
  (newData) => {
    if (chartInstance) {
      chartInstance.data.labels = newData.labels
      chartInstance.data.datasets = newData.datasets.map((ds) => ({
        label: ds.label || '',
        data: ds.data,
        borderColor: ds.color || '#22c55e',
        backgroundColor: ds.fillColor || 'rgba(34, 197, 94, 0.3)',
        fill: true,
        tension: 0.4,
      }))
      chartInstance.update()
    }
  },
  { deep: true },
)
</script>
