<template>
  <div
    class="bg-card border border-gray-700 p-4 rounded-lg flex flex-col items-start hover:shadow-lg transition-all duration-300"
  >
    <div class="text-4xl mb-2">{{ data.icone }}</div>
    <span class="text-xs bg-blue-500/20 text-blue-500 px-2 py-1 rounded">{{ data.tipo }}</span>
    <h3 class="font-semibold text-base mt-1">{{ data.titulo }}</h3>
    <p class="text-sm text-gray-400 mt-1">{{ data.descricao }}</p>
    <span class="text-green-500 mt-2">{{ data.pontos }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  data: Object,
})
</script>
