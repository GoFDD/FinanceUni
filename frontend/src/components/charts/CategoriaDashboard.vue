<template>
  <div class="w-full h-64 md:h-96">
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js'

Chart.register(PieController, ArcElement, Tooltip, Legend)

// Props do componente
const props = defineProps({
  chartData: {
    type: Object,
    required: true,
  },
})

const canvas = ref(null)
let chartInstance = null

// Cria o gráfico
onMounted(() => {
  chartInstance = new Chart(canvas.value, {
    type: 'pie',
    data: {
      ...props.chartData,
      // Garantir que datasets seja um array novo para evitar erros de readonly
      datasets: props.chartData.datasets.map((d) => ({ ...d })),
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
      },
    },
  })
})

// Atualiza o gráfico se os dados mudarem
watch(
  () => props.chartData,
  (newData) => {
    if (chartInstance) {
      chartInstance.data.labels = [...newData.labels]
      chartInstance.data.datasets = newData.datasets.map((d) => ({ ...d }))
      chartInstance.update()
    }
  },
  { deep: true },
)
</script>
