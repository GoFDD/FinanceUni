<template>
  <div class="w-full p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold text-gray-100">Meus Bancos</h1>

      <!-- Botão Conectar -->
      <button 
        @click="openPluggyConnect"
        :disabled="loading"
        class="w-12 h-12 bg-indigo-600 hover:bg-indigo-700 
               text-white rounded-full flex items-center justify-center 
               text-2xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="!loading">+</span>
        <div v-else class="animate-spin rounded-full h-6 w-6 border-t-2 border-white"></div>
      </button>
    </div>

    <!-- Mensagem de Erro -->
    <div v-if="error" class="bg-red-500/10 border border-red-500 rounded-xl p-4 mb-6">
      <p class="text-red-400 text-sm">{{ error }}</p>
    </div>

    <!-- Mensagem de Sucesso -->
    <div v-if="successMessage" class="bg-green-500/10 border border-green-500 rounded-xl p-4 mb-6">
      <p class="text-green-400 text-sm">{{ successMessage }}</p>
    </div>

    <!-- Container dos Bancos -->
    <div class="bg-slate-900/60 border border-slate-700 rounded-xl p-6 shadow-lg">
      <h2 class="text-xl font-semibold text-gray-100 mb-4">Bancos Conectados</h2>

      <!-- Loading -->
      <div v-if="loadingAccounts" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-400">Carregando contas...</p>
      </div>

      <!-- Estado Vazio -->
      <div 
        v-else-if="accounts.length === 0" 
        class="py-12 text-center"
      >
        <div class="text-6xl mb-4">🏦</div>
        <p class="text-gray-400 text-lg">Nenhum banco conectado ainda</p>
        <p class="text-gray-500 text-sm mt-2">Clique no botão + para conectar seu primeiro banco</p>
      </div>

      <!-- Grid de Contas -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div 
          v-for="acc in accounts"
          :key="acc.id"
          class="bg-slate-800/50 border border-slate-700 rounded-xl p-5 
                 hover:bg-slate-800/70 transition group"
        >
          <!-- Header do Card -->
          <div class="flex items-start justify-between mb-3">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-100 mb-1">
                {{ acc.institution_name }}
              </h3>
              <p class="text-sm text-gray-400">{{ acc.name }}</p>
            </div>
            <button
              @click="removeAccount(acc.id)"
              class="opacity-0 group-hover:opacity-100 transition-opacity
                     text-red-400 hover:text-red-300 text-sm"
            >
              🗑️
            </button>
          </div>

          <!-- Informações -->
          <div class="space-y-2">
            <div class="flex justify-between items-center text-sm">
              <span class="text-gray-400">Tipo:</span>
              <span class="text-gray-200 font-medium">{{ formatType(acc.type) }}</span>
            </div>

            <div v-if="acc.number" class="flex justify-between items-center text-sm">
              <span class="text-gray-400">Conta:</span>
              <span class="text-gray-200 font-mono">{{ acc.number }}</span>
            </div>

            <!-- Saldo -->
            <div class="pt-3 border-t border-slate-700">
              <p class="text-xs text-gray-400 mb-1">Saldo Disponível</p>
              <p class="text-2xl font-bold text-emerald-400">
                {{ formatMoney(acc.balance) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'

// Estado
const accounts = ref([])
const loading = ref(false)

// Carrega contas do banco de dados
async function loadAccounts() {
  const res = await api.get('/pluggy/accounts')
  accounts.value = res.data.accounts
}

async function openPluggyConnect() {

  //  Buscar connect-token do backend
  const res = await api.get('/pluggy/connect-token')
  const accessToken = res.data.accessToken


  if (!accessToken) {
    console.error("❌ accessToken veio vazio")
    return
  }

  //  Iniciar widget Pluggy
  const pluggy = new window.PluggyConnect({
    connectToken: accessToken,
    includeSandbox: true,

    onSuccess: async (itemData) => {
      // salva no backend
      await api.post('/pluggy/save-item', {
        itemId: itemData.item.id
      })

      loadAccounts()
    },

    onError: (err) => {
      console.error("Erro:", err)
    }
  })

  pluggy.init()
}

onMounted(() => {
  loadAccounts()
})
</script>



<style scoped>
/* Animações suaves */
.group:hover .opacity-0 {
  opacity: 1;
}
</style>