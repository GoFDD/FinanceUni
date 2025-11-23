<template>
  <div class="chart-container">
    <canvas ref="canvasEl"></canvas>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, watch, ref } from "vue";
import Chart from "chart.js/auto";
import { getCategoryColor } from "@/utils/categoryColors";

const props = defineProps({
  type: { type: String, default: "doughnut" },
  labels: { type: Array, default: () => [] },
  datasets: { type: Array, default: () => [] },
});

const canvasEl = ref(null);
let chart = null;

// remove undefined / null
function sanitize(arr) {
  return arr.map((v) => (v == null || isNaN(v) ? 0 : Number(v)));
}

function buildColors(labels) {
  return labels.map((label) => getCategoryColor(label));
}

function render() {
  if (!canvasEl.value) return;
  const ctx = canvasEl.value.getContext("2d");

  if (chart) chart.destroy();

  const safeLabels = props.labels.map((l) => l ?? "—");
  const bg = buildColors(safeLabels);

  const ds = props.datasets.map((d) => ({
    ...d,
    data: sanitize(d.data ?? []),
    backgroundColor: bg,
    borderWidth: 0,
  }));

  // 🔥 RADAR fix — precisa borda e pontos
  if (props.type === "radar") {
    ds.forEach((set, i) => {
      set.borderColor = bg[i] || "#38bdf8";
      set.pointBackgroundColor = bg[i] || "#38bdf8";
      set.pointBorderColor = "#fff";
      set.borderWidth = 2;
      set.fill = true;
    });
  }

  // 🔥 LINE fix — linha roxa gigante + animação quebrando
  if (props.type === "line") {
    ds.forEach((set, i) => {
      set.borderColor = bg[i] || "#38bdf8";
      set.backgroundColor = (bg[i] || "#38bdf8") + "40";
      set.fill = false;
      set.tension = 0.3;
    });
  }

  // 🔥 POLAR fix — aceita undefined e precisa borda mínima
  if (props.type === "polarArea") {
    ds.forEach((set) => {
      set.borderWidth = 1;
      set.borderColor = "#0f172a";
    });
  }

  chart = new Chart(ctx, {
    type: props.type,
    data: {
      labels: safeLabels,
      datasets: ds,
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: false, // 🔥 impede animação descendo

      plugins: {
        legend: {
          display: true,
          position: "bottom",
          labels: { color: "#e2e8f0" },
        },
      },

      // 🔥 escalas apenas para bar / line
      scales:
        props.type === "bar" || props.type === "line"
          ? {
              y: {
                beginAtZero: true,
                ticks: { color: "#cbd5e1" },
                grid: { color: "#1e293b" },
              },
              x: {
                ticks: { color: "#cbd5e1" },
                grid: { color: "#1e293b" },
              },
            }
          : {},
    },
  });
}

watch(() => props, render, { deep: true });
onMounted(render);
onBeforeUnmount(() => chart?.destroy());

function downloadImage() {
  const link = document.createElement("a");
  link.href = chart.toBase64Image();
  link.download = "grafico.png";
  link.click();
}

defineExpose({ downloadImage });
</script>

<style scoped>
.chart-container {
  width: 100%;
  height: 420px; /* 🔥 tamanho fixo evita gráfico gigante */
  position: relative;
}
</style>
