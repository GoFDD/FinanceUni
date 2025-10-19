<template>
  <div class="bg-[#18181b] border border-[#27272a] rounded-lg p-4 flex flex-col items-center gap-2">
    <div :class="['w-12 h-12 rounded-full flex items-center justify-center', cor]">
      {{ icone }}
    </div>
    <h4 class="text-white font-semibold text-sm">{{ titulo }}</h4>
    <p class="text-gray-400 text-xs text-center">{{ descricao }}</p>
    <div v-if="progresso" class="w-full bg-gray-700 rounded-full h-2 mt-2">
      <div class="bg-blue-500 h-2 rounded-full" :style="{ width: progresso + '%' }"></div>
    </div>
    <span v-if="status" class="text-xs text-green-400">{{ status }}</span>
  </div>
</template>

<script setup>
defineProps({
  titulo: String,
  descricao: String,
  icone: String, // emoji ou ícone SVG
  cor: String, // bg color
  progresso: Number,
  status: String,
})
</script>
