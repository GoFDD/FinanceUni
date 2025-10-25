<template>
  <div
    class="bg-card border border-gray-700 rounded-xl p-8 flex items-center justify-between hover:shadow-lg transition-all duration-300"
  >
    <div class="space-y-2">
      <p class="text-sm text-gray-400">Seu Score de Saúde Financeira</p>
      <div class="flex items-baseline space-x-2">
        <h2 class="text-7xl font-bold">{{ score }}</h2>
        <span class="text-3xl text-gray-400">/ 1000</span>
      </div>
      <span
        class="px-3 py-1 bg-green-500/20 text-green-500 rounded-full border border-green-500/50 font-semibold"
        >Excelente</span
      >
      <p class="text-green-500 flex items-center space-x-1">+45 pontos vs mês anterior</p>
      <p class="text-sm">Você está no top 15% dos usuários!</p>
    </div>
    <div>
      <!-- Gráfico Donut placeholder -->
      <div class="w-40 h-40 bg-gray-800 rounded-full flex items-center justify-center text-4xl">
        🏆
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  score: { type: Number, default: 0 },
})
</script>
