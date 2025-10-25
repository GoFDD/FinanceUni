<template>
  <div
    class="bg-[#1E293B] rounded-xl shadow-md p-4 flex flex-col items-center justify-center text-center hover:shadow-lg transition"
  >
    <div class="text-3xl mb-2">{{ icone }}</div>
    <h3 class="text-lg font-semibold">{{ titulo }}</h3>
    <p class="text-xl font-bold" :class="cor">{{ valor }}</p>
    <span class="text-sm text-gray-400">{{ subtitulo }}</span>
  </div>
</template>

<script setup>
const props = defineProps({
  titulo: String,
  valor: String,
  subtitulo: String,
  icone: String,
  cor: String,
})
</script>
