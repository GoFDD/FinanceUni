<template>
  <div class="space-y-8 px-4 md:px-8 py-6 text-gray-100">
    <!-- Header do Usuário -->
    <div class="bg-slate-900/60 border border-slate-700 rounded-2xl p-6 shadow-lg">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h2 class="text-2xl font-semibold">Olá, {{ user.name }}</h2>
          <p class="text-sm text-gray-400">Seu progresso para o nível {{ user.level + 1 }}</p>
        </div>
        <div class="flex items-center gap-3">
          <Trophy class="w-5 h-5 text-yellow-400" />
          <span class="text-blue-400 font-semibold">Level {{ user.level }} - {{ user.title }}</span>
          <span class="bg-blue-500/20 text-blue-300 px-3 py-1 rounded-full text-sm font-medium">
            {{ user.xp }} XP
          </span>
          <p class="text-sm text-gray-400">
          {{ user.xp - totalXpForCurrentLevel }} / {{ xpToNextLevel }} XP para o próximo nível
        </p>

        </div>
      </div>

      <!-- Barra de XP -->
      <div class="mt-4 w-full">
        <div class="relative w-full h-4 bg-slate-800 rounded-full overflow-hidden shadow-inner">
          <div
            class="h-full bg-gradient-to-r from-emerald-400 via-blue-500 to-cyan-400
                  transition-all duration-1000 ease-out shadow-md"
            :style="{ width: xpProgress + '%' }"
          ></div>

          <!-- Brilho leve de movimento -->
          <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent
                  animate-[shine_3s_linear_infinite]"
          ></div>
        </div>
      </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div
        v-for="card in cardsResumo"
        :key="card.titulo"
        class="flex items-center justify-between bg-slate-900/60 border border-slate-700 rounded-xl p-5 shadow-md hover:scale-[1.02] hover:bg-slate-800/60 transition"
      >
        <div class="flex items-center gap-3">
          <component :is="card.icone" class="w-6 h-6 text-emerald-400" />
          <div>
            <h3 class="text-lg font-semibold">{{ card.titulo }}</h3>
            <p class="text-sm text-gray-400">{{ card.subtitulo }}</p>
          </div>
        </div>
        <p class="text-xl font-bold text-emerald-400">{{ card.valor }}</p>
      </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Coluna de Metas -->
      <div class="lg:col-span-2 bg-slate-900/60 border border-slate-700 rounded-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Metas Ativas</h2>
          <button class="btn btn-sm btn-primary rounded-full">+ Nova Meta</button>
        </div>

        <div
          class="space-y-4 overflow-y-auto max-h-[400px] pr-2 scrollbar-thin scrollbar-thumb-slate-700"
        >
          <div
            v-for="meta in metas"
            :key="meta.nome"
            class="bg-slate-800/50 border border-slate-700 rounded-xl p-4 hover:bg-slate-800/70 transition"
          >
            <div class="flex justify-between items-start mb-2">
              <div class="flex items-center gap-2">
                <component :is="meta.icone" class="w-5 h-5 text-emerald-400" />
                <h3 class="text-lg font-semibold">{{ meta.nome }}</h3>
              </div>
              <span class="text-sm text-gray-400">{{ meta.percentual }}%</span>
            </div>

            <div class="flex justify-between text-sm text-gray-400 mb-2">
              <span>{{ meta.atual }} de {{ meta.meta }}</span>
              <span>{{ meta.restante }} restantes</span>
            </div>

            <!-- Progress Bar -->
            <div class="w-full h-3 bg-slate-700 rounded-full overflow-hidden mb-2">
              <div
                class="h-full bg-gradient-to-r from-emerald-500 to-blue-500"
                :style="{ width: meta.percentual + '%' }"
              ></div>
            </div>

            <div class="flex items-center justify-end text-sm text-gray-300">
              <Trophy class="w-4 h-4 text-yellow-400 mr-1" />
              <span>Recompensa: {{ meta.recompensaXP }} XP + "{{ meta.recompensaNome }}"</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Coluna de Conquistas -->
      <div class="bg-slate-900/60 border border-slate-700 rounded-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold">Conquistas</h2>
          <router-link to="/conquistas" class="text-blue-400 text-sm hover:underline"
            >Ver todas</router-link
          >
        </div>

        <div
          class="space-y-4 overflow-y-auto max-h-[400px] pr-2 scrollbar-thin scrollbar-thumb-slate-700"
        >
          <div
            v-for="conquista in conquistas"
            :key="conquista.nome"
            class="flex items-center justify-between bg-slate-800/50 border rounded-xl p-4"
            :class="{
              'border-slate-700': conquista.status !== 'bloqueado',
              'border-dashed border-slate-600 opacity-50 backdrop-blur-sm':
                conquista.status === 'bloqueado',
            }"
          >
            <div class="flex items-center gap-3">
              <component
                :is="conquista.icone"
                class="w-5 h-5"
                :class="conquista.status === 'bloqueado' ? 'text-gray-500' : 'text-yellow-400'"
              />
              <div>
                <h3 class="font-semibold">{{ conquista.nome }}</h3>
                <p class="text-sm text-gray-400">{{ conquista.descricao }}</p>
              </div>
            </div>

            <span
              class="text-xs font-medium px-3 py-1 rounded-full"
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
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import AuthService from '@/services/authService'
import { Trophy, PiggyBank, Gamepad2, Wallet } from 'lucide-vue-next'

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
const loading = ref(true)

// ====== CÁLCULO DE PROGRESSO ======

// XP necessário para atingir o próximo nível (exponencial)
const xpToNextLevel = computed(() => 1000 * Math.pow(2, user.value.level - 1))

// XP total necessário até o nível atual (somando os anteriores)
const totalXpForCurrentLevel = computed(() => {
  if (user.value.level <= 1) return 0
  return 1000 * (Math.pow(2, user.value.level - 1) - 1)
})

// Progresso percentual dentro do nível atual
const xpProgress = computed(() => {
  const xpInLevel = user.value.xp - totalXpForCurrentLevel.value
  const progress = (xpInLevel / xpToNextLevel.value) * 100
  return Math.min(Math.max(progress, 0), 100)
})


// ====== BUSCA DE DADOS ======
onMounted(async () => {
  try {
    const currentUser = await AuthService.getUser()
    if (currentUser) {
      user.value = currentUser
    }

    const streakData = await AuthService.getUserStreak()
    streak.value = streakData

  } catch (error) {
    console.error('Erro ao carregar dados do usuário:', error)
  } finally {
    loading.value = false
  }
})

// ====== CARDS RESUMO ======
const cardsResumo = computed(() => [
  {
    titulo: 'Saldo Total',
    valor: 'R$ 45.280',
    subtitulo: '+12.5% vs mês anterior',
    icone: Wallet,
  },
  {
    titulo: 'Metas Ativas',
    valor: '4',
    subtitulo: '2 concluídas',
    icone: PiggyBank,
  },
  {
    titulo: 'Conquistas',
    valor: '7',
    subtitulo: 'De 15 totais',
    icone: Trophy,
  },
  {
    titulo: 'Streak',
    valor: `Dia ${streak.value.streak} `,
    subtitulo: `Melhor sequência: ${streak.value.best_streak} dias`,
    icone: Gamepad2,
  },
])
</script>

<style scoped>
@keyframes shine {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}
</style>