<template>
  <div>
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js'

Chart.register(PieController, ArcElement, Tooltip, Legend)

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
})

const canvas = ref(null)

onMounted(() => {
  new Chart(canvas.value, {
    type: 'pie',
    data: props.data,
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
    },
  })
})
</script>
