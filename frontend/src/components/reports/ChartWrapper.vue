<template>
  <div class="w-full h-full">
    <canvas ref="canvas" class="w-full h-full"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, onBeforeUnmount } from 'vue'
import Chart from 'chart.js/auto'
import ChartDataLabels from 'chartjs-plugin-datalabels'

Chart.register(ChartDataLabels)

const props = defineProps({
  type: { type: String, required: true },
  labels: { type: Array, default: () => [] },
  datasets: { type: Array, default: () => [] },
  options: { type: Object, default: () => ({}) }
})

const emit = defineEmits(['ready'])
const canvas = ref(null)
let chart = null

function buildConfig() {
  // clone datasets to avoid mutation side effects
  const datasets = props.datasets.map(ds => ({ ...ds }))
  return {
    type: props.type,
    data: { labels: props.labels, datasets },
    options: {
      ...props.options,
      plugins: {
        datalabels: { display: false },
        ...(props.options.plugins || {})
      }
    }
  }
}

function renderChart() {
  if (!canvas.value) return
  if (chart) chart.destroy()
  const cfg = buildConfig()
  chart = new Chart(canvas.value.getContext('2d'), cfg)
  emit('ready', chart)
}

watch(() => [props.type, props.labels, props.datasets, props.options], () => {
  renderChart()
}, { deep: true })

onMounted(renderChart)
onBeforeUnmount(() => { if (chart) chart.destroy() })

// methods to be used by parent via ref
const downloadImage = () => {
  if (!chart) return
  const link = document.createElement('a')
  link.href = chart.toBase64Image()
  link.download = `relatorio-${Date.now()}.png`
  link.click()
}

defineExpose({ downloadImage, getChart: () => chart })
</script>

<style scoped>
canvas { width: 100% !important; height: 100% !important; }
</style>
