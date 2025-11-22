<template>
  <div class="w-full">
    <!-- Loading State -->
    <div v-if="loading" class="flex items-center justify-center min-h-screen">
      <div class="text-center">
        <div class="animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-400">Carregando dashboard...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex items-center justify-center min-h-screen">
      <div class="bg-red-500/10 border border-red-500 rounded-xl p-6 max-w-md mx-auto">
        <h3 class="text-xl font-semibold text-red-400 mb-2">Erro ao carregar</h3>
        <p class="text-gray-300">{{ error }}</p>
        <button 
          @click="reloadDashboard" 
          class="mt-4 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition"
        >
          Tentar Novamente
        </button>
      </div>
    </div>

    <!-- Dashboard Content -->
    <div v-else class="p-4 md:p-6 lg:p-8 space-y-6 md:space-y-8">
      <!-- Header do Usuário -->
      <div class="bg-slate-900/60 border border-slate-700 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-6">
          <div class="w-full md:w-auto">
            <h2 class="text-xl md:text-2xl font-semibold text-gray-100">Olá, {{ user.name }}</h2>
            <p class="text-xs md:text-sm text-gray-400 mt-1">
              Seu progresso para o nível {{ user.level + 1 }}
            </p>
          </div>
          
          <div class="flex flex-wrap items-center gap-2 md:gap-3 w-full md:w-auto">
            <Trophy class="w-4 h-4 md:w-5 md:h-5 text-yellow-400 flex-shrink-0" />
            <span class="text-sm md:text-base text-blue-400 font-semibold">
              Level {{ user.level }} - {{ user.title }}
            </span>
            <span class="bg-blue-500/20 text-blue-300 px-2 md:px-3 py-1 rounded-full text-xs md:text-sm font-medium">
              {{ user.xp }} XP
            </span>
          </div>
        </div>

        <!-- Info de Progresso -->
        <div class="mt-4 md:mt-5">
          <p class="text-xs md:text-sm text-gray-400 mb-2">
            {{ xpInCurrentLevel }} / {{ xpToNextLevel }} XP para o próximo nível
          </p>
          
          <!-- Barra de XP -->
          <div class="relative w-full h-3 md:h-4 bg-slate-800 rounded-full overflow-hidden shadow-inner">
            <div
              class="h-full bg-gradient-to-r from-emerald-400 via-blue-500 to-cyan-400
                    transition-all duration-1000 ease-out shadow-md"
              :style="{ width: xpProgress + '%' }"
            ></div>

            <!-- Brilho de movimento -->
            <div
              class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent
                    animate-[shine_3s_linear_infinite]"
            ></div>
          </div>
        </div>
      </div>

      <!-- Cards de Resumo -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div
          v-for="card in cardsResumo"
          :key="card.titulo"
          class="bg-slate-900/60 border border-slate-700 rounded-xl p-4 md:p-5 shadow-md 
                 hover:scale-[1.02] hover:bg-slate-800/60 transition-all duration-200"
        >
          <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0 flex-1">
              <div class="flex-shrink-0">
                <component :is="card.icone" class="w-6 h-6 text-emerald-400" />
              </div>
              <div class="min-w-0 flex-1">
                <h3 class="text-base md:text-lg font-semibold text-gray-100 truncate">
                  {{ card.titulo }}
                </h3>
                <p class="text-xs md:text-sm text-gray-400 truncate">
                  {{ card.subtitulo }}
                </p>
              </div>
            </div>
            <div class="flex-shrink-0">
              <p class="text-xl md:text-2xl font-bold text-emerald-400 whitespace-nowrap">
                {{ card.valor }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        <!-- Coluna de Metas -->
        <div class="lg:col-span-2 bg-slate-900/60 border border-slate-700 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
          <div class="flex justify-between items-center mb-4 md:mb-6">
            <h2 class="text-lg md:text-xl font-semibold text-gray-100">Metas Ativas</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs md:text-sm px-3 md:px-4 py-1.5 md:py-2 rounded-full transition">
              + Nova Meta
            </button>
          </div>

          <!-- Estado Vazio -->
          <div v-if="metas.length === 0" class="text-center py-12">
            <Target class="w-16 h-16 text-gray-600 mx-auto mb-4" />
            <p class="text-gray-400 text-base">Nenhuma meta ativa no momento</p>
            <p class="text-sm text-gray-500 mt-1">Crie sua primeira meta para começar!</p>
          </div>

          <!-- Lista de Metas -->
          <div
            v-else
            class="space-y-4 overflow-y-auto max-h-[400px] pr-2 scrollbar-thin scrollbar-thumb-slate-700"
          >
            <div
              v-for="meta in metas"
              :key="meta.id"
              class="bg-slate-800/50 border border-slate-700 rounded-xl p-4 hover:bg-slate-800/70 transition"
            >
              <div class="flex justify-between items-start mb-3">
                <div class="flex items-center gap-2 flex-1 min-w-0">
                  <component :is="meta.icone" class="w-5 h-5 text-emerald-400 flex-shrink-0" />
                  <h3 class="text-base md:text-lg font-semibold text-gray-100 truncate">
                    {{ meta.nome }}
                  </h3>
                  <span 
                    v-if="meta.tipo" 
                    class="text-xs px-2 py-0.5 rounded-full flex-shrink-0"
                    :class="meta.tipo === 'sistema' ? 'bg-purple-500/20 text-purple-300' : 'bg-blue-500/20 text-blue-300'"
                  >
                    {{ meta.tipo }}
                  </span>
                </div>
                <span class="text-sm font-semibold text-gray-300 flex-shrink-0 ml-2">
                  {{ meta.percentual }}%
                </span>
              </div>

              <div class="flex justify-between text-sm text-gray-400 mb-2">
                <span>R$ {{ meta.atual }} de R$ {{ meta.meta }}</span>
                <span>R$ {{ meta.restante }} restantes</span>
              </div>

              <!-- Progress Bar -->
              <div class="w-full h-3 bg-slate-700 rounded-full overflow-hidden mb-3">
                <div
                  class="h-full bg-gradient-to-r from-emerald-500 to-blue-500 transition-all duration-500"
                  :style="{ width: meta.percentual + '%' }"
                ></div>
              </div>

              <div class="flex items-center justify-between text-sm">
                <div class="flex items-center text-gray-300">
                  <Trophy class="w-4 h-4 text-yellow-400 mr-1 flex-shrink-0" />
                  <span class="truncate">{{ meta.recompensaXP }} XP + "{{ meta.recompensaNome }}"</span>
                </div>
                
                <!-- Botão Completar (se estiver em 100%) -->
                <button 
                  v-if="meta.percentual >= 100"
                  @click="completarMeta(meta.id)"
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs transition flex-shrink-0 ml-2"
                >
                  Completar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Coluna de Conquistas -->
        <div class="bg-slate-900/60 border border-slate-700 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
          <div class="flex justify-between items-center mb-4 md:mb-6">
            <h2 class="text-lg md:text-xl font-semibold text-gray-100">Conquistas</h2>
            <router-link 
              to="/conquistas" 
              class="text-blue-400 text-xs md:text-sm hover:underline transition"
            >
              Ver todas
            </router-link>
          </div>

          <!-- Estado Vazio -->
          <div v-if="conquistas.length === 0" class="text-center py-12">
            <Award class="w-16 h-16 text-gray-600 mx-auto mb-4" />
            <p class="text-gray-400 text-base">Nenhuma conquista disponível</p>
          </div>

          <!-- Lista de Conquistas -->
          <div
            v-else
            class="space-y-4 overflow-y-auto max-h-[400px] pr-2 scrollbar-thin scrollbar-thumb-slate-700"
          >
            <div
              v-for="conquista in conquistasLimitadas"
              :key="conquista.id"
              class="bg-slate-800/50 border rounded-xl p-4 transition"
              :class="{
                'border-slate-700 hover:bg-slate-800/70': conquista.status !== 'bloqueado',
                'border-dashed border-slate-600 opacity-60': conquista.status === 'bloqueado',
              }"
            >
              <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3 flex-1 min-w-0">
                  <component
                    :is="conquista.icone"
                    class="w-5 h-5 flex-shrink-0 mt-0.5"
                    :class="conquista.status === 'bloqueado' ? 'text-gray-500' : 'text-yellow-400'"
                  />
                  <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-gray-100 text-sm md:text-base truncate">
                      {{ conquista.nome }}
                    </h3>
                    <p class="text-xs md:text-sm text-gray-400 line-clamp-2">
                      {{ conquista.descricao }}
                    </p>
                    
                    <!-- Barra de Progresso (se em progresso) -->
                    <div v-if="conquista.status === 'progresso'" class="mt-2">
                      <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div
                          class="h-full bg-yellow-500 transition-all duration-500"
                          :style="{ width: conquista.progress + '%' }"
                        ></div>
                      </div>
                      <p class="text-xs text-gray-500 mt-1">{{ conquista.progress }}% completo</p>
                    </div>
                  </div>
                </div>

                <span
                  class="text-xs font-medium px-2 md:px-3 py-1 rounded-full whitespace-nowrap flex-shrink-0"
                  :class="{
                    'bg-green-500/20 text-green-400': conquista.status === 'completo',
                    'bg-yellow-500/20 text-yellow-400': conquista.status === 'progresso',
                    'bg-slate-600/30 text-gray-400': conquista.status === 'bloqueado',
                  }"
                >
                  {{ conquista.statusLabel }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import DashboardService from '@/services/dashboardService'
import { Trophy, PiggyBank, Gamepad2, Wallet, Target, Award } from 'lucide-vue-next'

// ====== ESTADOS ======
const user = ref({
  name: '',
  level: 0,
  xp: 0,
  title: '',
})

const streak = ref({
  streak: 0,
  best_streak: 0,
  last_login: null,
})

const metas = ref([])
const conquistas = ref([])
const resumo = ref({})
const loading = ref(true)
const error = ref(null)

// ====== CÁLCULO DE PROGRESSO ======
const xpToNextLevel = computed(() => 1000) // 1000 XP por nível

const xpInCurrentLevel = computed(() => {
  return user.value.xp % 1000
})

const xpProgress = computed(() => {
  const progress = (xpInCurrentLevel.value / xpToNextLevel.value) * 100
  return Math.min(Math.max(progress, 0), 100)
})

// ====== CONQUISTAS LIMITADAS (mostrar apenas 5) ======
const conquistasLimitadas = computed(() => {
  return conquistas.value.slice(0, 5)
})

// ====== BUSCA DE DADOS ======
async function loadDashboard() {
  try {
    loading.value = true
    error.value = null

    const data = await DashboardService.getDashboardData()

    user.value = data.user
    streak.value = data.streak
    conquistas.value = data.conquistas
    metas.value = data.metas
    resumo.value = data.resumo

  } catch (err) {
    console.error('❌ Erro ao carregar dashboard:', err)
    error.value = err.message || 'Erro ao carregar dashboard'
  } finally {
    loading.value = false
  }
}

// ====== RECARREGAR DASHBOARD ======
function reloadDashboard() {
  loadDashboard()
}

// ====== COMPLETAR META ======
async function completarMeta(goalId) {
  try {
    const response = await DashboardService.completeGoal(goalId)
    console.log('✅ Meta completada:', response)
    
    await loadDashboard()
    
    alert(`Meta completada! Você ganhou ${response.xp_earned} XP!`)
  } catch (err) {
    console.error('❌ Erro ao completar meta:', err)
    alert('Erro ao completar meta: ' + (err.message || 'Erro desconhecido'))
  }
}

// ====== CARREGAR AO MONTAR ======
onMounted(() => {
  loadDashboard()
})

// ====== CARDS RESUMO ======
const cardsResumo = computed(() => [
  {
    titulo: 'Saldo Total',
    valor: resumo.value.saldo_formatado || 'R$ 0,00',
    subtitulo: 'Suas finanças',
    icone: Wallet,
  },
  {
    titulo: 'Metas Ativas',
    valor: resumo.value.metas_ativas || 0,
    subtitulo: `${resumo.value.metas_concluidas || 0} concluídas`,
    icone: PiggyBank,
  },
  {
    titulo: 'Conquistas',
    valor: resumo.value.conquistas_desbloqueadas || 0,
    subtitulo: `De ${resumo.value.conquistas_totais || 0} totais`,
    icone: Trophy,
  },
  {
    titulo: 'Streak',
    valor: `${streak.value.streak} dia${streak.value.streak !== 1 ? 's' : ''}`,
    subtitulo: `Recorde: ${streak.value.best_streak} dias`,
    icone: Gamepad2,
  },
])
</script>

<style scoped>
@keyframes shine {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Scrollbar customizado */
.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: #1e293b;
  border-radius: 10px;
}

.scrollbar-thumb-slate-700::-webkit-scrollbar-thumb {
  background: #475569;
  border-radius: 10px;
}

.scrollbar-thumb-slate-700::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}

/* Garantir altura mínima nos cards */
.grid > div {
  min-height: 100px;
}

/* Limitar linhas de texto */
.line-clamp-2 {
  display: -webkit-box;
  line-clamp: 2;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Melhorias de responsividade */
@media (max-width: 640px) {
  .grid {
    gap: 1rem;
  }
}
</style>