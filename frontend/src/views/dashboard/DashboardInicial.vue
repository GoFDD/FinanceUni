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
        </div>
      </div>

      <!-- Barra de XP -->
      <div class="mt-4 w-full">
        <div class="w-full h-3 bg-slate-800 rounded-full overflow-hidden">
          <div
            class="h-full bg-gradient-to-r from-emerald-500 to-blue-500 transition-all duration-700"
            :style="{ width: user.progress + '%' }"
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
import { ref } from 'vue'
import { Trophy, PiggyBank, Plane, Gamepad2, Shield, Wallet } from 'lucide-vue-next'

// Mock de usuário
const user = ref({
  name: 'Julio Cesar',
  level: 6,
  xp: 2450,
  progress: 70,
  title: 'Mestre do Dinheiro',
})

// Cards Resumo
const cardsResumo = [
  { titulo: 'Saldo Total', valor: 'R$ 45.280', subtitulo: '+12.5% vs mês anterior', icone: Wallet },
  { titulo: 'Metas Ativas', valor: '4', subtitulo: '2 concluídas', icone: PiggyBank },
  { titulo: 'Conquistas', valor: '7', subtitulo: 'De 15 totais', icone: Trophy },
  { titulo: 'Streak', valor: '5 dias', subtitulo: 'Maior sequência: 8 dias', icone: Gamepad2 },
]

// Metas mock
const metas = [
  {
    nome: 'Fundo de Emergência',
    icone: Shield,
    atual: 'R$ 7.500',
    meta: 'R$ 10.000',
    restante: 'R$ 2.500',
    percentual: 75,
    recompensaXP: 500,
    recompensaNome: 'Auxilio Emergencial',
  },
  {
    nome: 'Viagem de Férias',
    icone: Plane,
    atual: 'R$ 3.200',
    meta: 'R$ 5.000',
    restante: 'R$ 1.800',
    percentual: 64,
    recompensaXP: 350,
    recompensaNome: 'Andarilho',
  },
  {
    nome: 'Nova TV',
    icone: Gamepad2,
    atual: 'R$ 1.000',
    meta: 'R$ 3.000',
    restante: 'R$ 2.000',
    percentual: 33,
    recompensaXP: 200,
    recompensaNome: 'Buff do entretenimento',
  },
]

// Conquistas mock
const conquistas = [
  {
    nome: 'Primeira Meta',
    descricao: 'Complete sua primeira meta',
    status: 'completo',
    statusLabel: 'Completa',
    icone: Trophy,
  },
  {
    nome: 'Poupador Constante',
    descricao: 'Economize por 7 dias seguidos',
    status: 'progresso',
    statusLabel: 'Em progresso',
    icone: PiggyBank,
  },
  {
    nome: 'Explorador Financeiro',
    descricao: 'Crie 5 metas diferentes',
    status: 'bloqueado',
    statusLabel: 'Bloqueada',
    icone: Plane,
  },
]
</script>
