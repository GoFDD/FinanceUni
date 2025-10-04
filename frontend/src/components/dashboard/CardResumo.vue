<template>
  <div class="bg-[#18181b] border border-[#27272a] rounded-lg p-6 shadow">
    <div class="flex justify-between items-start">
      <div>
        <h3 class="text-sm text-gray-400">{{ titulo }}</h3>
        <p :class="['text-2xl font-bold mt-2', cor]">{{ valor }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ subtitulo }}</p>
      </div>
      <div class="text-2xl">{{ icone }}</div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  titulo: String,
  valor: String,
  subtitulo: String,
  cor: String,
  icone: String,
})
</script>
