<template>
  <div class="bg-[#18181b] border border-[#27272a] rounded-lg p-4 flex flex-col gap-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div :class="['w-10 h-10 rounded-full flex items-center justify-center', cor]">
          {{ inicial }}
        </div>
        <div>
          <h3 class="text-white font-semibold">{{ nome }}</h3>
          <span class="text-sm text-gray-400">{{ tipo }}</span>
        </div>
      </div>
    </div>

    <!-- Corpo -->
    <div class="flex flex-col gap-2">
      <p :class="saldoCor" class="text-2xl font-bold">{{ saldoFormatado }}</p>
      <p class="text-sm text-gray-400">{{ variacao }}</p>
      <slot name="grafico"></slot>
      <!-- Sparkline ou Chart -->
    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center text-sm text-gray-400">
      <span>{{ transacoes }} transações</span>
      <div class="flex gap-2">
        <button class="btn-outline">Ver</button>
        <button class="btn-ghost">Editar</button>
        <button class="btn-ghost text-red-500">Excluir</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

defineProps({
  nome: String,
  tipo: String,
  saldo: Number,
  transacoes: Number,
  variacao: String,
  cor: String, // ex: 'bg-blue-500'
})

const saldoCor = computed(() => (saldo >= 0 ? 'text-green-500' : 'text-red-500'))
const saldoFormatado = computed(() =>
  saldo.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
)

const inicial = computed(() => nome[0].toUpperCase())
</script>
