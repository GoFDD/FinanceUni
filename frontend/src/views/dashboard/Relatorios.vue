<template>
  <div class="min-h-screen bg-[#0F172A] text-white flex flex-col p-6 md:p-8 overflow-y-auto">
    <!-- Cabeçalho -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold">Relatórios</h1>
        <p class="text-gray-400 text-sm">
          Visualize seus gastos e receitas com gráficos detalhados
        </p>
      </div>

      <button @click="abrirFiltros" class="btn btn-primary btn-sm md:btn-md">
        <i class="fa-solid fa-filter mr-2"></i>Filtros
      </button>
    </div>

    <!--  Controle de Período e Tipo de Gráfico -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-6">
      <!-- Controle de Período -->
      <div class="flex items-center gap-3">
        <button @click="anteriorMes" class="btn btn-sm btn-outline btn-secondary">‹</button>
        <span class="text-lg font-semibold">{{ mesAtual }}</span>
        <button @click="proximoMes" class="btn btn-sm btn-outline btn-secondary">›</button>
      </div>

      <!-- Tipo de Gráfico -->
      <div class="join">
        <button
          v-for="tipo in tiposGrafico"
          :key="tipo.id"
          @click="tipoSelecionado = tipo.id"
          class="join-item btn btn-sm md:btn-md"
          :class="{
            'btn-primary': tipoSelecionado === tipo.id,
            'btn-outline btn-secondary': tipoSelecionado !== tipo.id,
          }"
        >
          {{ tipo.icone }} {{ tipo.label }}
        </button>
      </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div
        v-for="card in resumoCards"
        :key="card.titulo"
        class="bg-[#1E293B] p-4 rounded-xl shadow hover:bg-[#334155] transition"
      >
        <p class="text-gray-400 text-sm">{{ card.titulo }}</p>
        <p class="text-2xl font-bold mt-2">{{ card.valor }}</p>
      </div>
    </div>

    <!-- Filtros Detalhados -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
      <select v-model="filtroPeriodo" class="select select-bordered select-secondary w-full">
        <option value="mes">Este mês</option>
        <option value="trimestre">Último Trimestre</option>
        <option value="ano">Este ano</option>
        <option value="personalizado">Período Personalizado</option>
      </select>

      <select v-model="filtroTipo" class="select select-bordered select-secondary w-full">
        <option value="todas">Todas as transações</option>
        <option value="despesas">Apenas Despesas</option>
        <option value="receitas">Apenas Receitas</option>
      </select>

      <select v-model="filtroBanco" class="select select-bordered select-secondary w-full">
        <option value="todos">Todos os bancos</option>
        <option value="banco1">Banco 1</option>
        <option value="banco2">Banco 2</option>
      </select>
    </div>

    <!--  Gráfico Principal -->
    <div class="bg-[#1E293B] rounded-xl p-6 flex flex-col lg:flex-row gap-6 shadow-lg flex-grow">
      <div class="lg:w-3/4 w-full">
        <canvas ref="graficoPrincipal" class="w-full h-[400px]"></canvas>
      </div>

      <!-- Mini Cards -->
      <div class="lg:w-1/4 w-full flex flex-col gap-2">
        <div
          v-for="cat in categorias"
          :key="cat.nome"
          class="bg-[#334155] p-3 rounded-lg flex justify-between items-center hover:bg-[#475569] transition cursor-pointer"
          @mouseenter="hoverCategoria(cat.nome)"
          @mouseleave="hoverCategoria(null)"
        >
          <div class="flex items-center gap-2">
            <span>{{ cat.icone }}</span>
            <span class="text-sm font-medium">{{ cat.nome }}</span>
          </div>
          <span class="font-semibold">{{ cat.valor }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

// Estado base
const mesAtual = ref('Outubro de 2025')
const tiposGrafico = [
  { id: 'pizza', label: 'Pizza', icone: '🥧' },
  { id: 'barras', label: 'Barras', icone: '📊' },
  { id: 'tendencias', label: 'Tendências', icone: '📈' },
]
const tipoSelecionado = ref('pizza')

// Dados estáticos
const resumoCards = [
  { titulo: 'Total de Despesas', valor: 'R$ 2.450,00' },
  { titulo: 'Maior Categoria', valor: 'Alimentação - R$ 890,00' },
  { titulo: 'Categorias Ativas', valor: '5' },
  { titulo: 'Média por Categoria', valor: 'R$ 490,00' },
]
const categorias = [
  { nome: 'Transporte', valor: 'R$ 636,90', icone: '🚗' },
  { nome: 'Consertos', valor: 'R$ 450,00', icone: '🔧' },
  { nome: 'Saúde', valor: 'R$ 320,00', icone: '🏥' },
  { nome: 'Alimentação', valor: 'R$ 890,00', icone: '🍔' },
]

// Filtros
const filtroPeriodo = ref('mes')
const filtroTipo = ref('todas')
const filtroBanco = ref('todos')

// Controle de Mês
const anteriorMes = () => (mesAtual.value = 'Setembro de 2025')
const proximoMes = () => (mesAtual.value = 'Novembro de 2025')

// Filtros
const abrirFiltros = () => alert('Abrir painel de filtros avançados')

// Gráfico dinâmico
const graficoPrincipal = ref(null)
let chartInstance = null

const renderChart = () => {
  if (chartInstance) chartInstance.destroy()

  const dataValues = categorias.map((c) => Number(c.valor.replace(/[^\d]/g, '')))
  const labels = categorias.map((c) => c.nome)
  const colors = ['#3b82f6', '#22c55e', '#ef4444', '#facc15']

  let type = 'pie'
  if (tipoSelecionado.value === 'barras') type = 'bar'
  if (tipoSelecionado.value === 'tendencias') type = 'line'

  chartInstance = new Chart(graficoPrincipal.value, {
    type,
    data: {
      labels,
      datasets: [
        {
          label: 'Gastos por Categoria',
          data: dataValues,
          backgroundColor: colors,
          borderColor: colors,
          fill: tipoSelecionado.value === 'tendencias',
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      scales: type !== 'pie' ? { y: { beginAtZero: true } } : {},
    },
  })
}

watch(tipoSelecionado, renderChart)
onMounted(renderChart)

const hoverCategoria = (nome) => {
  if (!chartInstance) return
  const index = chartInstance.data.labels.indexOf(nome)
  if (index !== -1) {
    chartInstance.setActiveElements([{ datasetIndex: 0, index }])
    chartInstance.tooltip.setActiveElements([{ datasetIndex: 0, index }], { x: 0, y: 0 })
  } else {
    chartInstance.setActiveElements([])
    chartInstance.tooltip.setActiveElements([], { x: 0, y: 0 })
  }
  chartInstance.update()
}
</script>

<style scoped>
::-webkit-scrollbar {
  width: 8px;
}
::-webkit-scrollbar-thumb {
  background: #475569;
  border-radius: 4px;
}
</style>
