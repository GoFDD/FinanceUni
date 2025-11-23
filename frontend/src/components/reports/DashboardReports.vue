<template>
  <div class="min-h-screen bg-[#0B1220] text-white p-6 md:p-10 space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold">Relatórios</h1>
        <p class="text-slate-400 mt-1">Visualize receitas e despesas — filtros avançados e múltiplos gráficos.</p>
      </div>

      <div class="flex items-center gap-3">
        <button @click="toggleFilters" class="px-4 py-2 rounded-md bg-sky-600 hover:bg-sky-500 transition">
          Filtros
        </button>
        <select v-model="chartPreset" class="bg-slate-800 text-sm rounded-md p-2">
          <option value="categorias">Por categoria</option>
          <option value="por-conta">Por conta</option>
          <option value="por-mes">Série temporal (mês)</option>
        </select>
      </div>
    </div>

    <!-- Filters panel -->
    <filters-panel
      v-show="showFilters"
      :accounts="accounts"
      :categories="categories"
      v-model:period="period"
      v-model:typeFilter="typeFilter"
      v-model:accountId="accountId"
      @apply="fetchReport"
      @reset="resetFilters"
    />

    <!-- summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <summary-card title="Total Receitas" :value="formatCurrency(summary.total_income)" icon="⬆️" />
      <summary-card title="Total Despesas" :value="formatCurrency(summary.total_expense)" icon="⬇️" />
      <summary-card title="Saldo" :value="formatCurrency(summary.balance)" icon="💰" />
      <summary-card title="Transações" :value="summary.count" icon="🧾" />
    </div>

    <!-- Main content: chart + legend / list -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-slate-900/60 p-4 rounded-xl shadow">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-lg font-semibold">{{ chartTitle }}</h3>
          <div class="flex items-center gap-2">
            <select v-model="chartType" class="bg-slate-800 p-1 rounded-md text-sm">
              <option value="pie">Pizza</option>
              <option value="doughnut">Donut</option>
              <option value="bar">Barras</option>
              <option value="line">Tendência</option>
              <option value="radar">Radar</option>
              <option value="polarArea">Polar</option>
            </select>
            <button @click="downloadPNG" class="px-2 py-1 text-sm bg-slate-800 rounded-md">Exportar PNG</button>
          </div>
        </div>

        <chart-wrapper
          ref="chartWrapper"
          :type="chartType"
          :labels="chartData.labels"
          :datasets="chartData.datasets"
          :options="chartOptions"
          class="w-full h-[420px]"
        />
      </div>

      <div class="bg-slate-900/60 p-4 rounded-xl shadow">
        <h4 class="text-lg font-semibold mb-3">Resumo por categoria</h4>
        <div class="space-y-2 max-h-[420px] overflow-auto pr-2">
          <div
            v-for="(c, idx) in chartData.items"
            :key="c.key"
            class="flex items-center justify-between gap-3 p-2 rounded-md hover:bg-slate-800/50 transition"
          >
            <div class="flex items-center gap-3">
              <div class="w-3 h-3 rounded-full" :style="{ backgroundColor: chartData.datasets[0].backgroundColor[idx] }"></div>
              <div>
                <div class="text-sm font-medium">{{ c.label }}</div>
                <div class="text-xs text-slate-400">{{ c.subtitle }}</div>
              </div>
            </div>
            <div class="text-sm font-semibold">{{ formatCurrency(c.value) }}</div>
          </div>

          <div v-if="chartData.items.length === 0" class="text-slate-400 text-sm">Sem dados para o período selecionado.</div>
        </div>
      </div>
    </div>

    <!-- Bottom: small charts (optional) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-slate-900/60 p-4 rounded-xl">
        <h5 class="text-sm font-semibold mb-2">Receita x Despesa (últimos 6 meses)</h5>
        <chart-wrapper :type="'line'" :labels="miniCharts.months" :datasets="miniCharts.incomeExpense" :options="miniOptions" class="h-44" />
      </div>

      <div class="bg-slate-900/60 p-4 rounded-xl">
        <h5 class="text-sm font-semibold mb-2">Top categorias</h5>
        <chart-wrapper :type="'bar'" :labels="miniCharts.topLabels" :datasets="miniCharts.topDataset" :options="miniOptions" class="h-44" />
      </div>

      <div class="bg-slate-900/60 p-4 rounded-xl">
        <h5 class="text-sm font-semibold mb-2">Distribuição por conta</h5>
        <chart-wrapper :type="'polarArea'" :labels="miniCharts.accountLabels" :datasets="miniCharts.accountDataset" :options="miniOptions" class="h-44" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import FiltersPanel from '@/components/reports/FiltersPanel.vue'
import ChartWrapper from '@/components/reports/ChartWrapper.vue'
import SummaryCard from '@/components/reports/SummaryCard.vue'
import reportService from '@/services/ReportService'

// state
const showFilters = ref(true)
const chartType = ref('doughnut')
const chartPreset = ref('categorias')
const chartWrapper = ref(null)

// filters (two-way with FiltersPanel)
const period = ref({ from: null, to: null }) // ISO dates
const typeFilter = ref('todas') // receitas/despesas/todas
const accountId = ref(null)

// data
const accounts = ref([])       // populate from API
const categories = ref([])     // populate from API
const summary = reactive({
  total_income: 0,
  total_expense: 0,
  balance: 0,
  count: 0,
})
const chartData = reactive({
  labels: [],
  datasets: [{ data: [], backgroundColor: [] }],
  items: []
})

// mini charts
const miniCharts = reactive({
  months: [],
  incomeExpense: [],   // dataset array for line
  topLabels: [],
  topDataset: [{ data: [], backgroundColor: [] }],
  accountLabels: [],
  accountDataset: [{ data: [], backgroundColor: [] }],
})
const miniOptions = { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }

// options for main chart (shared)
const chartOptions = {
  responsive: true,
  plugins: {
    legend: { position: 'bottom' },
    tooltip: { mode: 'index', intersect: false }
  }
}

// computed titles
const chartTitle = computed(() => {
  if (chartPreset.value === 'categorias') return 'Gastos por Categoria'
  if (chartPreset.value === 'por-conta') return 'Distribuição por Conta'
  return 'Série temporal'
})

// helpers
const formatCurrency = (v) => {
  if (v == null) return 'R$ 0,00'
  return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(v)
}
const toggleFilters = () => (showFilters.value = !showFilters.value)
const resetFilters = () => {
  period.value = { from: null, to: null }
  typeFilter.value = 'todas'
  accountId.value = null
}

// fetch initial aux data
async function loadMeta() {
  try {
    const [accRes, catRes] = await Promise.all([
      reportService.getAccounts(),
      reportService.getCategories()
    ])
    accounts.value = accRes
    categories.value = catRes
  } catch (e) {
    console.error('Erro ao carregar contas/categorias', e)
  }
}

// fetch report
async function fetchReport() {
  try {
    // parameters
    const params = {
      from: period.value.from,
      to: period.value.to,
      type: typeFilter.value,
      account_id: accountId.value,
      preset: chartPreset.value,
    }

    const res = await reportService.getReport(params)

    // expected response shape (example):
    // { summary: { total_income, total_expense, balance, count }, data: [{ key, label, value, subtitle }], mini: { months, incomeExpense, top, accounts } }
    summary.total_income = res.summary.total_income ?? 0
    summary.total_expense = res.summary.total_expense ?? 0
    summary.balance = res.summary.balance ?? (summary.total_income - summary.total_expense)
    summary.count = res.summary.count ?? 0

    // build chartData
    const items = res.data ?? []
    chartData.labels = items.map(i => i.label)
    chartData.datasets = [{
      data: items.map(i => Number(i.value)),
      backgroundColor: generateColors(items.length),
      borderColor: [],
    }]
    chartData.items = items

    // mini charts
    if (res.mini) {
      miniCharts.months = res.mini.months ?? []
      // incomeExpense expects array of dataset objects for ChartWrapper
      miniCharts.incomeExpense = res.mini.incomeExpense ?? []
      miniCharts.topLabels = (res.mini.top ?? []).map(i => i.label)
      miniCharts.topDataset = [{
        data: (res.mini.top ?? []).map(i => Number(i.value)),
        backgroundColor: generateColors(res.mini.top?.length ?? 0)
      }]
      miniCharts.accountLabels = (res.mini.accounts ?? []).map(i => i.label)
      miniCharts.accountDataset = [{
        data: (res.mini.accounts ?? []).map(i => Number(i.value)),
        backgroundColor: generateColors(res.mini.accounts?.length ?? 0)
      }]
    }
  } catch (e) {
    console.error('Erro ao buscar relatório', e)
    // opcional: mostrar toast
  }
}

function generateColors(n) {
  const base = ['#3b82f6', '#22c55e', '#ef4444', '#f59e0b', '#8b5cf6', '#06b6d4', '#f472b6']
  const out = []
  for (let i = 0; i < n; i++) out.push(base[i % base.length])
  return out
}

// download PNG
function downloadPNG() {
  if (!chartWrapper.value) return
  chartWrapper.value.downloadImage()
}

// lifecycle
onMounted(async () => {
  await loadMeta()
  // set default period (this month)
  const today = new Date()
  const from = new Date(today.getFullYear(), today.getMonth(), 1).toISOString().slice(0,10)
  const to = new Date(today.getFullYear(), today.getMonth()+1, 0).toISOString().slice(0,10)
  period.value = { from, to }
  await fetchReport()
})

// expose to template
const chartWrapperRef = chartWrapper
</script>

<style scoped>
/* small helper scss */
</style>
