<template>
  <div
    class="bg-[#18181b] border border-[#27272a] rounded-lg p-4 flex items-center justify-between"
  >
    <div>
      <h3 class="text-sm text-gray-400">{{ titulo }}</h3>
      <p :class="cor" class="text-2xl font-bold">{{ valor }}</p>
      <span class="text-xs text-gray-500">{{ subtitulo }}</span>
    </div>
    <div class="text-3xl">
      <slot name="icone"></slot>
    </div>
  </div>
</template>

<script setup>
defineProps({
  titulo: String,
  valor: String,
  subtitulo: String,
  cor: String, // ex: 'text-green-500'
})
</script>
