<template>
  <div class="min-h-screen bg-[#0a0e1a] text-white p-6">
    <div class="max-w-7xl mx-auto">

      <!-- Header -->
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

      <!-- Cards de Resumo -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        
        <!-- Total -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800/50 hover:border-emerald-600/30 transition-all">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-emerald-600/10 rounded-lg">
              <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Total de Receitas</span>
          </div>

          <p class="text-3xl font-bold text-white mb-2">
            R$ {{ totalMes.toLocaleString('pt-BR', { minimumFractionDigits: 2 }) }}
          </p>

          <p class="text-xs text-emerald-500 flex items-center gap-1">
            <span>↑</span>
            +18% vs mês anterior
          </p>
        </div>

        <!-- Contagem -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800/50">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-gray-700/30 rounded-lg">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Receitas Registradas</span>
          </div>

          <p class="text-3xl font-bold text-white mb-2">{{ receitas.length }}</p>
          <p class="text-xs text-gray-500">{{ receitasRecorrentes }} recorrentes</p>
        </div>

        <!-- Maior Receita -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800/50">
          <div class="flex items-center gap-3 mb-3">
            <div class="p-2 bg-gray-700/30 rounded-lg">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <span class="text-sm text-gray-400">Maior Receita</span>
          </div>

          <p class="text-3xl font-bold text-white mb-2">R$ {{ maiorReceita.valor }}</p>
          <p class="text-xs text-gray-500">{{ maiorReceita.categoria }}</p>
        </div>

      </div>

      <!-- Filtros -->
      <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="flex-1 relative">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>

          <input
            v-model="busca"
            type="text"
            placeholder="Buscar receita..."
            class="w-full bg-[#1a1f2e] pl-10 pr-4 py-3 rounded-lg border border-gray-800/50 focus:border-emerald-600 text-white placeholder-gray-500"
          />
        </div>

        <select 
          v-model="filtroCategoria"
          class="bg-[#1a1f2e] px-4 py-3 rounded-lg border border-gray-800/50 focus:border-emerald-600 text-white"
        >
          <option value="">Todas categorias</option>
          <option v-for="cat in categorias" :key="cat.id" :value="cat.nome">
            {{ cat.nome }}
          </option>
        </select>

        <select 
          v-model="filtroMes"
          class="bg-[#1a1f2e] px-4 py-3 rounded-lg border border-gray-800/50 focus:border-emerald-600 text-white"
        >
          <option>Novembro 2025</option>
          <option>Outubro 2025</option>
          <option>Setembro 2025</option>
        </select>
      </div>

      <!-- Tabela -->
      <div class="bg-[#1a1f2e] rounded-xl border border-gray-800/50 overflow-hidden">
        
        <div class="grid grid-cols-12 gap-4 px-6 py-4 bg-[#141824] text-sm text-gray-400 border-b border-gray-800/50">
          <div class="col-span-4">Descrição</div>
          <div class="col-span-2">Categoria</div>
          <div class="col-span-2">Data</div>
          <div class="col-span-2">Valor</div>
          <div class="col-span-2 text-center">Ações</div>
        </div>

        <div class="divide-y divide-gray-800/30">
          <div 
            v-for="receita in receitasFiltradas" 
            :key="receita.id" 
            class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-[#141824] transition-colors"
          >
            
            <div class="col-span-4 flex items-center gap-3">
              <div class="p-2 bg-emerald-600/10 rounded-lg">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
              </div>
              <div>
                <p class="font-medium text-white">{{ receita.descricao }}</p>
                <span
                  v-if="receita.recorrente"
                  class="text-xs text-blue-400"
                >
                  🔄 Recorrente
                </span>
              </div>
            </div>

            <div class="col-span-2 flex items-center">
              <span class="px-3 py-1 bg-gray-800/50 rounded-full text-sm text-gray-300">
                {{ receita.categoria }}
              </span>
            </div>

            <div class="col-span-2 flex items-center text-gray-400">
              {{ receita.data }}
            </div>

            <div class="col-span-2 flex items-center">
              <span class="text-emerald-400 font-semibold text-lg">
                R$ {{ receita.valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 }) }}
              </span>
            </div>

            <div class="col-span-2 flex items-center justify-center gap-2">
              <button 
                @click="abrirModal(receita)"
                class="px-3 py-1.5 text-sm bg-gray-800/50 hover:bg-gray-700 text-gray-300 rounded-lg"
              >
                Editar
              </button>

              <button 
                @click="confirmarExclusao(receita.id)"
                class="px-3 py-1.5 text-sm text-red-400 hover:bg-red-600/10 rounded-lg"
              >
                Excluir
              </button>
            </div>

          </div>
        </div>

        <div v-if="receitasFiltradas.length === 0" class="py-12 text-center">
          <p class="text-gray-400">Nenhuma receita encontrada</p>
        </div>
      </div>

      <!-- Gamificação → Missões + Level XP -->
      <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Missões -->
        <div class="bg-[#1a1f2e] p-6 rounded-xl border border-gray-800/50">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">🏆 Missões do Mês</h3>
            <span class="text-xs px-2 py-1 bg-emerald-600/20 text-emerald-400 rounded-full">
              {{ missoesConcluidas }}/{{ missoes.length }}
            </span>
          </div>

          <div class="space-y-3">
            <div 
              v-for="missao in missoes" 
              :key="missao.id"
              class="p-4 bg-[#0a0e1a] rounded-lg border border-gray-800/50"
            >
              <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-2">
                  <span class="text-xl">{{ missao.icone }}</span>
                  <div>
                    <h4 class="font-medium">{{ missao.titulo }}</h4>
                    <p class="text-sm text-gray-400">{{ missao.descricao }}</p>
                  </div>
                </div>

                <div v-if="missao.concluida" class="text-emerald-400">✔</div>
              </div>

              <div class="w-full bg-gray-800 rounded-full h-2">
                <div 
                  class="h-full rounded-full bg-blue-500 transition-all duration-500"
                  :style="{ width: `${(missao.progresso / missao.meta) * 100}%` }"
                ></div>
              </div>

              <p class="text-xs text-gray-400 mt-1">
                {{ missao.progresso }} / {{ missao.meta }} — +{{ missao.xp }} XP
              </p>
            </div>
          </div>
        </div>

        <!-- Level XP -->
        <div class="space-y-4">

          <div class="bg-gradient-to-br from-purple-600/20 to-blue-600/20 p-6 rounded-xl border border-purple-600/30">
            <h3 class="text-xl font-bold mb-4">⭐ Nível {{ nivelAtual }}</h3>

            <div class="w-full bg-gray-800 rounded-full h-3 overflow-hidden">
              <div 
                class="bg-gradient-to-r from-purple-500 to-blue-500 h-full rounded-full"
                :style="{ width: `${(xpAtual / xpProximoNivel) * 100}%` }"
              ></div>
            </div>

            <p class="text-xs text-gray-400 mt-2">
              {{ xpAtual }} / {{ xpProximoNivel }} XP — falta {{ xpProximoNivel - xpAtual }} XP
            </p>
          </div>

        </div>

      </div>

    </div>

    <!-- Modais -->
    <!-- (os modais permanecem idênticos ao seu código original) -->

  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

/*======= ESTADOS =======*/
const receitas = ref([])
const categorias = ref([])
const missoes = ref([])

const nivelAtual = ref(0)
const xpAtual = ref(0)
const xpProximoNivel = ref(1000)

/*======= CÁLCULOS =======*/
const totalMes = computed(() => receitas.value.reduce((a, b) => a + b.valor, 0))

const maiorReceita = computed(() => {
  if (receitas.value.length === 0) return { valor: "0,00", categoria: "-" }
  let r = [...receitas.value].sort((a,b) => b.valor - a.valor)[0]
  return { valor: r.valor.toLocaleString('pt-BR', { minimumFractionDigits: 2 }), categoria: r.categoria }
})

const receitasRecorrentes = computed(() =>
  receitas.value.filter(r => r.recorrente).length
)

const busca = ref("")
const filtroCategoria = ref("")
const filtroMes = ref("Novembro 2025")

const receitasFiltradas = computed(() => {
  let out = receitas.value

  if (busca.value)
    out = out.filter(r =>
      r.descricao.toLowerCase().includes(busca.value.toLowerCase())
    )

  if (filtroCategoria.value)
    out = out.filter(r => r.categoria === filtroCategoria.value)

  return out
})

const missoesConcluidas = computed(() => missoes.value.filter(m => m.concluida).length)
</script>
