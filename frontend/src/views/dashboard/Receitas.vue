<template>
  <div class="min-h-screen bg-[#10172b] text-white p-6">
    <div class="max-w-7xl mx-auto">

      <!-- HEADER -->
      <div class="flex items-start justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold mb-2">Receitas</h1>
          <p class="text-gray-400">Gerencie suas entradas mensais com clareza</p>
        </div>

        <button
          @click="abrirModal()"
          class="flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-all"
        >
          <span class="text-lg">+</span>
          Adicionar Receita
        </button>
      </div>

      <!-- CARDS -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <!-- TOTAL -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800 hover:border-emerald-600/30 transition-all">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-emerald-600/10 rounded-lg">
              <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Total de Receitas</span>
          </div>

          <p class="text-3xl font-bold mb-2">R$ {{ totalMesFormatado }}</p>
        </div>

        <!-- CONTAGEM -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-gray-700/30 rounded-lg">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Receitas Registradas</span>
          </div>

          <p class="text-3xl font-bold mb-2">{{ receitas.length }}</p>
          <p class="text-xs text-gray-500">{{ receitasRecorrentes }} recorrentes</p>
        </div>

        <!-- MAIOR RECEITA -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-gray-700/30 rounded-lg">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Maior Receita</span>
          </div>

          <p class="text-3xl font-bold mb-2">R$ {{ maiorReceita.valor }}</p>
          <p class="text-xs text-gray-500">{{ maiorReceita.categoria }}</p>
        </div>

      </div>

      <!-- TABELA -->
      <div class="bg-[#1a1f2e] rounded-xl border border-gray-800 overflow-hidden">

        <!-- Cabeçalho -->
        <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-[#141824] text-sm text-gray-400 border-b border-gray-800">
          <div class="col-span-4">Descrição</div>
          <div class="col-span-2">Categoria</div>
          <div class="col-span-2">Data</div>
          <div class="col-span-2">Valor</div>
          <div class="col-span-2 text-center">Ações</div>
        </div>

        <!-- Linhas -->
        <div v-for="r in receitas" :key="r.id" class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-[#141824] transition">

          <div class="col-span-4 flex items-center gap-3">
            <div class="p-2 bg-emerald-600/10 rounded-lg">
              <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <div>
              <p class="font-medium">{{ r.description }}</p>
              <span v-if="r.is_recurring" class="text-xs text-blue-400">🔄 Recorrente</span>
            </div>
          </div>

          <div class="col-span-2 flex items-center">
            <span class="px-3 py-1 bg-gray-800 rounded-full text-sm">
              {{ getCategoryName(r.category_id) }}
            </span>
          </div>

          <div class="col-span-2 flex items-center text-gray-400">
            {{ formatDate(r.date) }}
          </div>

          <div class="col-span-2 flex items-center">
            <span class="text-emerald-400 font-semibold text-lg">
              R$ {{ Number(r.amount).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
            </span>
          </div>

          <div class="col-span-2 flex items-center justify-center gap-2">
            <button @click="abrirModal(r)" class="px-3 py-1.5 text-sm bg-gray-800 hover:bg-gray-700 rounded-lg">
              Editar
            </button>

            <button @click="abrirModalExcluir(r)" class="px-3 py-1.5 text-sm text-red-400 hover:bg-red-600/10 rounded-lg">
              Excluir
            </button>
          </div>

        </div>

        <div v-if="receitas.length === 0" class="py-12 text-center">
          <p class="text-gray-400">Nenhuma receita encontrada</p>
        </div>

      </div>

      <!-- XP -->
      <div class="mt-8 bg-gradient-to-br from-purple-600/20 to-blue-600/20 p-6 rounded-xl border border-purple-600/30">
        <h3 class="text-xl font-bold mb-4">⭐ Nível {{ nivel }}</h3>

        <div class="w-full bg-gray-800 rounded-full h-3 overflow-hidden">
          <div
            class="bg-gradient-to-r from-purple-500 to-blue-500 h-full"
            :style="{ width: `${xpPercent}%` }"
          ></div>
        </div>

        <p class="text-xs text-gray-400 mt-2">
          {{ xpAtual }} / {{ xpMax }} XP — faltam {{ xpMax - xpAtual }} XP
        </p>
      </div>

    </div>

    <!-- MODAL -->
    <TransactionModal
      v-if="modalAberto"
      :transacao="receitaSelecionada"
      :categorias="categorias"
      @fechar="modalAberto=false"
      @salvar="salvarReceita"
    />


    <ConfirmDelete
      v-if="confirmAberto"
      :item="receitaSelecionada"
      @cancelar="confirmAberto=false"
      @confirmar="deletarReceita"
    />

  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import TransactionService from '@/services/transactionService'
import ConfirmDelete from '@/components/modals/ConfirmDelete.vue'
import dashboardService from '@/services/dashboardService'
import TransactionModal from '@/components/modals/TransactionModal.vue'

/* STATE */
const receitas = ref([])
const categorias = ref([])

const modalAberto = ref(false)
const receitaSelecionada = ref(null)
const confirmAberto = ref(false)
const idParaExcluir = ref(null)

/* XP */
const nivel = ref(0)
const xpAtual = ref(0)
const xpMax = ref(1000)

const xpPercent = computed(() =>
  xpMax.value ? ((xpAtual.value / xpMax.value) * 100).toFixed(2) : 0
)

/* CÁLCULOS */
const totalMesFormatado = computed(() =>
  receitas.value
    .reduce((acc, r) => acc + Number(r.amount), 0)
    .toLocaleString('pt-BR', { minimumFractionDigits: 2 })
)

const receitasRecorrentes = computed(() =>
  receitas.value.filter(r => r.is_recurring).length
)

const maiorReceita = computed(() => {
  if (!receitas.value.length) return { valor: '0,00', categoria: '-' }
  const r = [...receitas.value].sort((a,b) => Number(b.amount) - Number(a.amount))[0]
  return {
    valor: Number(r.amount).toLocaleString('pt-BR', { minimumFractionDigits: 2 }),
    categoria: r.description
  }
})

/* HELPERS */
function getCategoryName(id) {
  if (!categorias.value.length) return 'Sem categoria'
  const c = categorias.value.find(x => x.id === id)
  return c ? c.name : 'Sem categoria'
}

function formatDate(date) {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('pt-BR')
}

/* MODAIS */
function abrirModal(r = null) {
  receitaSelecionada.value = r ? { ...r } : null
  modalAberto.value = true
}

function abrirModalExcluir(r) {
  receitaSelecionada.value = r
  idParaExcluir.value = r.id
  confirmAberto.value = true
}

/* CRUD */
async function salvarReceita(payload) {
  try {
    if (receitaSelecionada.value) {
      await TransactionService.updateIncome(receitaSelecionada.value.id, payload)
    } else {
      await TransactionService.createIncome(payload)
    }
    modalAberto.value = false
    await carregarReceitas()
  } catch (err) {
    console.error('Erro salvar receita', err)
  }
}

async function deletarReceita() {
  try {
    await TransactionService.deleteIncome(idParaExcluir.value)
    confirmAberto.value = false
    await carregarReceitas()
  } catch (err) {
    console.error('Erro excluir', err)
  }
}

/* LOAD DATA */
async function carregarReceitas() {
  try {
    receitas.value = await TransactionService.getIncomes()
  } catch (err) {
    console.error('Erro carregar receitas', err)
  }
}

async function carregarCategorias() {
  try {
    categorias.value = await TransactionService.getCategories("income");
  } catch (err) {
    console.error("Erro carregar categorias (income)", err);
  }
}

async function carregarXp() {
  try {
    const d = await dashboardService.getDashboardData();
    const totalXp = d.user.xp ?? 0

    nivel.value = d.user.level
    xpAtual.value = totalXp % 1000
    xpMax.value = 1000

  } catch (err) {
    console.error('Erro carregar xp', err)
  }
}

onMounted(async () => {
  await carregarCategorias()
  await carregarReceitas()
  await carregarXp()
})
</script>


