<template>
  <div class="min-h-screen bg-[#0B1220] text-white p-6 md:p-10 space-y-6">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold">Relatórios Financeiros</h1>
        <p class="text-slate-400 mt-1">Análise completa de receitas, despesas e categorias.</p>
      </div>

      <div class="flex items-center gap-3">
        <button @click="toggleFilters" class="px-4 py-2 rounded-md bg-sky-600 hover:bg-sky-500 transition">
          Filtros
        </button>

        <select v-model="chartPreset" class="bg-slate-800 text-sm rounded-md p-2">
          <option value="categorias">Por categoria</option>
          <option value="tempo">Série temporal</option>
        </select>
      </div>
    </div>

    <!-- FILTERS -->
    <filters-panel
      v-show="showFilters"
      :categories="categories"
      v-model:period="period"
      v-model:typeFilter="typeFilter"
      @apply="fetchReport"
      @reset="resetFilters"
    />

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <summary-card title="Total Receitas" :value="formatCurrency(summary.total_income)" icon="⬆️" />
      <summary-card title="Total Despesas" :value="formatCurrency(summary.total_expense)" icon="⬇️" />
      <summary-card title="Saldo" :value="formatCurrency(summary.balance)" icon="💰" />
      <summary-card title="Total de Transações" :value="summary.count" icon="🧾" />
    </div>

    <!-- CHART AREA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- GRAPH -->
      <div class="lg:col-span-2 bg-slate-900/60 p-4 rounded-xl shadow">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold">{{ chartTitle }}</h3>

          <div class="flex items-center gap-2">
            <select v-model="chartType" class="bg-slate-800 p-1 rounded-md text-sm">
              <option value="doughnut">Donut</option>
              <option value="pie">Pizza</option>
              <option value="bar">Barras</option>
            </select>

            <button @click="downloadPNG" class="px-2 py-1 text-sm bg-slate-800 rounded-md">
              Exportar PNG
            </button>
          </div>
        </div>

        <chart-wrapper
          ref="chartWrapper"
          :type="chartType"
          :labels="chartData.labels"
          :datasets="chartData.datasets"
          class="w-full h-[420px]"
        />
      </div>

      <!-- LISTA RESUMO -->
      <div class="bg-slate-900/60 p-4 rounded-xl shadow">
        <h4 class="text-lg font-semibold mb-3">Resumo</h4>

        <div class="space-y-2 max-h-[420px] overflow-auto pr-2">
          <div
            v-for="(c, idx) in chartData.items"
            :key="c.key"
            class="flex items-center justify-between p-2 rounded-md hover:bg-slate-800/50"
          >
            <div class="flex items-center gap-3">
              <div
                class="w-3 h-3 rounded-full"
                :style="{ backgroundColor: chartData.datasets[0].backgroundColor[idx] }"
              ></div>

              <div>
                <div class="text-sm font-medium">{{ c.label }}</div>
                <div class="text-xs text-slate-400">{{ c.subtitle }}</div>
              </div>
            </div>

            <div class="font-semibold">{{ formatCurrency(c.value) }}</div>
          </div>
        </div>
      </div>
    </div>
    <!-- MINI CHARTS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

      <div class="bg-slate-900/60 p-4 rounded-xl">
        <h5 class="text-sm font-semibold mb-2">Receita x Despesa</h5>
        <chart-wrapper :type="'line'" :labels="miniCharts.months" :datasets="miniCharts.incomeExpense" class="h-44" />
      </div>

      <div class="bg-slate-900/60 p-4 rounded-xl">
        <h5 class="text-sm font-semibold mb-2">Top categorias</h5>
        <chart-wrapper :type="'bar'" :labels="miniCharts.topLabels" :datasets="miniCharts.topDataset" class="h-44" />
      </div>

    </div>
  </div>
</template>


<script setup>
import { ref, reactive, onMounted, computed } from "vue"
import FiltersPanel from "@/components/reports/FiltersPanel.vue"
import SummaryCard from "@/components/reports/SummaryCard.vue"
import reportService from "@/services/ReportService"
import ChartWrapper from "@/components/reports/ChartWrapper.vue"

// UI states
const showFilters = ref(true)
const chartType = ref("doughnut")
const chartPreset = ref("categorias")
const chartWrapper = ref(null)

// filters
const period = ref({ from: null, to: null })
const typeFilter = ref("todas")

// API
const categories = ref([])

// summary
const summary = reactive({
  total_income: 0,
  total_expense: 0,
  balance: 0,
  count: 0,
})

// main chart
const chartData = reactive({
  labels: [],
  datasets: [{ data: [], backgroundColor: [] }],
  items: [],
})

// mini charts
const miniCharts = reactive({
  months: [],
  incomeExpense: [],
  topLabels: [],
  topDataset: [{ data: [] }],
})

// utils
const formatCurrency = (v) =>
  new Intl.NumberFormat("pt-BR", {
    style: "currency",
    currency: "BRL",
  }).format(v ?? 0)

const toggleFilters = () => (showFilters.value = !showFilters.value)

const resetFilters = () => {
  period.value = { from: null, to: null }
  typeFilter.value = "todas"
}

const colors = (n) => {
  const base = ["#3b82f6", "#22c55e", "#ef4444", "#f59e0b", "#8b5cf6"]
  return Array.from({ length: n }, (_, i) => base[i % base.length])
}

const chartTitle = computed(() => {
  if (chartPreset.value === "categorias") return "Gastos por Categoria"
  return "Evolução no tempo"
})

// load categories
async function loadMeta() {
  const res = await reportService.getCategories()
  categories.value = res
}

// fetch report
async function fetchReport() {
  const params = {
    from: period.value.from,
    to: period.value.to,
    type: typeFilter.value,
    preset: chartPreset.value,
  }

  const res = await reportService.getReport(params)

  summary.total_income = res.summary.total_income
  summary.total_expense = res.summary.total_expense
  summary.balance = res.summary.balance
  summary.count = res.summary.count

  const items = res.data
  chartData.labels = items.map((i) => i.label)
  chartData.items = items

  chartData.datasets = [
    {
      data: items.map((i) => i.value),
      backgroundColor: colors(items.length),
    },
  ]

  if (res.mini) {
    miniCharts.months = res.mini.months
    miniCharts.incomeExpense = res.mini.incomeExpense

    miniCharts.topLabels = res.mini.top.map((i) => i.label)
    miniCharts.topDataset = [
      { data: res.mini.top.map((i) => i.value), backgroundColor: colors(res.mini.top.length) },
    ]
  }
}

function downloadPNG() {
  chartWrapper.value.downloadImage()
}

onMounted(async () => {
  await loadMeta()

  const today = new Date()
  period.value.from = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().slice(0, 10)
  period.value.to = new Date(today.getFullYear(), today.getMonth() + 1, 0).toISOString().slice(0, 10)

  fetchReport()
})
</script>


<style scoped></style>
