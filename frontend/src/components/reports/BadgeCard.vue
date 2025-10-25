<template>
  <div
    class="min-w-[180px] snap-center bg-card border-2 p-6 rounded-xl flex flex-col items-center justify-center hover:scale-105 hover:shadow-lg transition-transform duration-300"
    :class="data.status === 'Bloqueado' ? 'opacity-40 filter grayscale' : ''"
  >
    <div class="text-5xl mb-2">{{ data.icone }}</div>
    <h4 class="font-bold text-lg text-center">{{ data.titulo }}</h4>
    <span class="text-xs text-gray-400">{{ data.status }}</span>
    <p v-if="data.data" class="text-xs text-gray-500">{{ data.data }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  data: Object,
})
</script>
