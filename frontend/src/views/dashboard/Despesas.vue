<template>
  <div class="min-h-screen bg-[#0e100912] text-white p-6">
    <div class="max-w-7xl mx-auto">

      <!-- HEADER -->
      <div class="flex items-start justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold mb-2">Despesas</h1>
          <p class="text-gray-400">Gerencie seus gastos com clareza</p>
        </div>

        <button
          @click="abrirModal()"
          class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all"
        >
          <span class="text-lg">+</span>
          Adicionar Despesa
        </button>
      </div>

      <!-- CARDS -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        <!-- TOTAL DO MÊS -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-red-600/10 rounded-lg">
              <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 1.343-3 3v6m6-9c1.657 0 3 1.343 3 3v6M6 18h12" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Total do Mês</span>
          </div>

          <p class="text-3xl font-bold mb-2">R$ {{ totalMesFormatado }}</p>
        </div>

        <!-- MAIOR DESPESA -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-gray-700/30 rounded-lg">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M18 12H6m6 6V6" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Maior Despesa</span>
          </div>

          <p class="text-3xl font-bold mb-2">R$ {{ maiorDespesa.valor }}</p>
          <p class="text-xs text-gray-500">{{ maiorDespesa.categoria }}</p>
        </div>

        <!-- ECONOMIA VS MES ANTERIOR -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-green-600/10 rounded-lg">
              <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 12l5 5L20 7" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Economia vs mês anterior</span>
          </div>

          <p class="text-3xl font-bold mb-2 text-green-400">R$ {{ economia.valor }}</p>
          <p class="text-xs text-gray-500">{{ economia.percentual }}% de economia</p>
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
        <div v-for="r in despesas" :key="r.id" class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-[#141824] transition">

          <div class="col-span-4 flex items-center gap-3">
            <div class="p-2 bg-red-600/10 rounded-lg">
              <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8c-1.657 0-3 1.343-3 3v6m6-9c1.657 0 3 1.343 3 3v6M6 18h12" />
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
            <span class="text-red-400 font-semibold text-lg">
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

        <div v-if="despesas.length === 0" class="py-12 text-center">
          <p class="text-gray-400">Nenhuma despesa encontrada</p>
        </div>

      </div>

      <!-- XP -->
      <div class="mt-8 bg-gradient-to-br from-purple-600/20 to-blue-600/20 p-6 rounded-xl border border-purple-600/30">
        <h3 class="text-xl font-bold mb-4">⭐ Nível {{ nivel }}</h3>

        <div class="w-full bg-gray-800 rounded-full h-3 overflow-hidden">
          <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-full" :style="{ width: xpPercent + '%' }"></div>
        </div>

        <p class="text-xs text-gray-400 mt-2">
          {{ xpAtual }} / {{ xpMax }} XP — faltam {{ xpMax - xpAtual }} XP
        </p>
      </div>

    </div>

    <!-- MODAL -->
    <TransactionModal
      v-if="modalAberto"
      :transacao="despesaSelecionada"
      :categorias="categorias"
      @fechar="modalAberto=false"
      @salvar="salvarDespesa"
    />

    <ConfirmDelete
      v-if="confirmAberto"
      :item="despesaSelecionada"
      @cancelar="confirmAberto=false"
      @confirmar="deletarDespesa"
    />

  </div>
</template>



<script setup>
import { ref, computed, onMounted } from "vue";
import TransactionService from "@/services/transactionService";
import ConfirmDelete from "@/components/modals/ConfirmDelete.vue";
import TransactionModal from "@/components/modals/TransactionModal.vue";
import dashboardService from "@/services/dashboardService";

/* STATE */
const despesas = ref([]);
const categorias = ref([]);

const modalAberto = ref(false);
const despesaSelecionada = ref(null);
const confirmAberto = ref(false);
const idParaExcluir = ref(null);

/* XP */
const nivel = ref(0);
const xpAtual = ref(0);
const xpMax = ref(1000);

const xpPercent = computed(() =>
  xpMax.value ? ((xpAtual.value / xpMax.value) * 100).toFixed(2) : 0
);

/* CÁLCULOS */
const totalMesFormatado = computed(() =>
  despesas.value
    .reduce((acc, r) => acc + Number(r.amount), 0)
    .toLocaleString("pt-BR", { minimumFractionDigits: 2 })
);

const maiorDespesa = computed(() => {
  if (!despesas.value.length) return { valor: "0,00", categoria: "-" };
  const r = [...despesas.value].sort((a, b) => Number(b.amount) - Number(a.amount))[0];
  return {
    valor: Number(r.amount).toLocaleString("pt-BR", { minimumFractionDigits: 2 }),
    categoria: r.description,
  };
});

/* ECONOMIA VS MÊS ANTERIOR */
const economia = ref({ valor: "0,00", percentual: 0 });

async function carregarEconomia() {
  try {
    const r = await TransactionService.getExpenseSummary();

    economia.value = {
      valor: Number(r.economia).toLocaleString("pt-BR", { minimumFractionDigits: 2 }),
      percentual: r.percentual ?? 0,
    };
  } catch (err) {
    console.error("Erro economia", err);
  }
}

/* HELPERS */
function getCategoryName(id) {
  if (!categorias.value.length) return "Sem categoria";
  const c = categorias.value.find((x) => x.id === id);
  return c ? c.name : "Sem categoria";
}

function formatDate(date) {
  if (!date) return "-";
  return new Date(date).toLocaleDateString("pt-BR");
}

/* MODAIS */
function abrirModal(r = null) {
  despesaSelecionada.value = r ? { ...r } : null;
  modalAberto.value = true;
}

function abrirModalExcluir(r) {
  despesaSelecionada.value = r;
  idParaExcluir.value = r.id;
  confirmAberto.value = true;
}

/* CRUD */
async function salvarDespesa(payload) {
  try {
    if (despesaSelecionada.value) {
      await TransactionService.updateExpense(despesaSelecionada.value.id, payload);
    } else {
      await TransactionService.createExpense(payload);
    }

    modalAberto.value = false;
    await carregarDespesas();
    await carregarEconomia();
  } catch (err) {
    console.error("Erro salvar despesa", err);
  }
}

async function deletarDespesa() {
  try {
    await TransactionService.deleteExpense(idParaExcluir.value);
    confirmAberto.value = false;

    await carregarDespesas();
    await carregarEconomia();
  } catch (err) {
    console.error("Erro excluir despesa", err);
  }
}

/* LOAD DATA */
async function carregarDespesas() {
  try {
    despesas.value = await TransactionService.getExpenses();
  } catch (err) {
    console.error("Erro despesas", err);
  }
}

async function carregarCategorias() {
  try {
    categorias.value = await TransactionService.getCategories("expense");
  } catch (err) {
    console.error("Erro carregar categorias (expense)", err);
  }
}


async function carregarXp() {
  try {
    const d = await dashboardService.getDashboardData();

    const totalXp = d.user.xp ?? 0;
    nivel.value = d.user.level;
    xpAtual.value = totalXp % 1000;
  } catch (err) {
    console.error("Erro XP", err);
  }
}

onMounted(async () => {
  await carregarCategorias();
  await carregarDespesas();
  await carregarEconomia();
  await carregarXp();
});
</script>
