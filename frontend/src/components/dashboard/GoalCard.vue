<template>
  <div class="bg-[#1E293B] rounded-lg p-4 shadow-sm hover:shadow-md transition">
    <h4 class="font-semibold">{{ titulo }}</h4>
    <div class="h-3 bg-gray-700 rounded-full overflow-hidden mt-2">
      <div class="h-full bg-green-500" :style="{ width: progresso + '%' }"></div>
    </div>
    <p class="text-sm text-gray-400 mt-1">{{ progressoTexto }}</p>
  </div>
</template>

<script setup>
const props = defineProps({
  titulo: String,
  progresso: String,
  progressoTexto: String,
})
</script>
