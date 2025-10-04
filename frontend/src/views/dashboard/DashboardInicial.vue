<template>
  <div class="space-y-6">
    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <CardResumo v-for="(card, i) in cardsResumo" :key="i" v-bind="card" />
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" v-if="showCharts">
      <div class="md:col-span-2">
        <GraficoFluxo :chart-data="fluxoData" />
      </div>
      <div>
        <CategoriaDashboard :chart-data="categoriaData" />
      </div>
    </div>

    <!-- Conquistas -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Conquistas</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <CardConquista v-for="(c, i) in conquistas" :key="i" v-bind="c" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import CardResumo from '@/components/dashboard/CardResumo.vue'
import CardConquista from '@/components/dashboard/CardConquista.vue'
import GraficoFluxo from '@/components/charts/GraficoFluxo.vue'
import CategoriaDashboard from '@/components/charts/CategoriaDashboard.vue'

const cardsResumo = [
  {
    titulo: 'Saldo Total',
    valor: 'R$ 45.280,00',
    subtitulo: '+12.5% vs mês anterior',
    cor: 'text-emerald-500',
    icone: '💰',
  },
  {
    titulo: 'Total de Bancos',
    valor: '4',
    subtitulo: '3 contas ativas',
    cor: 'text-white',
    icone: '🏦',
  },
  {
    titulo: 'Transações Totais',
    valor: '127',
    subtitulo: 'Este mês',
    cor: 'text-white',
    icone: '📊',
  },
]

const conquistas = [
  { titulo: 'Primeiro Milhão', descricao: 'Meta: R$ 1.000.000', icone: '🏆', status: 'bloqueado' },
  { titulo: 'Economista', descricao: '30 dias economizando', icone: '🐷', status: 'desbloqueado' },
  { titulo: 'Investidor', descricao: '10 investimentos', icone: '📈', status: 'progresso' },
]

const fluxoData = {
  labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
  datasets: [
    {
      label: 'Receitas',
      data: [5000, 7000, 6000, 8000, 7500, 9000],
      borderColor: '#22c55e',
      fill: true,
    },
    {
      label: 'Despesas',
      data: [3000, 4000, 3500, 5000, 4200, 4800],
      borderColor: '#ef4444',
      fill: true,
    },
  ],
}

const categoriaData = ref({
  labels: ['Alimentação', 'Transporte', 'Moradia', 'Lazer'],
  datasets: [
    {
      data: [35, 25, 20, 20],
      backgroundColor: ['#3b82f6', '#22c55e', '#eab308', '#a855f7'],
    },
  ],
})

// Controla renderização para evitar lag
const showCharts = ref(false)
onMounted(() => nextTick(() => (showCharts.value = true)))
</script>
