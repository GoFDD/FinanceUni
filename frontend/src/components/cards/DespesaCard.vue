<template>
  <div class="bg-[#18181b] p-4 rounded-lg border border-[#27272a] flex flex-col justify-between">
    <div>
      <p class="font-semibold text-white">{{ nome }}</p>
      <p class="text-sm text-gray-400">{{ categoria }} - {{ data }}</p>
    </div>
    <p class="mt-2 text-lg font-bold text-red-500">{{ valor }}</p>
  </div>
</template>

<script setup>
defineProps({
  nome: String,
  categoria: String,
  valor: String,
  data: String,
})
</script>
