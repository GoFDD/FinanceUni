<template>
  <div class="bg-[#18181b] border border-[#27272a] rounded-lg p-6 shadow text-center">
    <div class="text-4xl mb-3">{{ icone }}</div>
    <h4 class="text-lg font-semibold">{{ titulo }}</h4>
    <p class="text-sm text-gray-400 mb-2">{{ descricao }}</p>

    <div v-if="status === 'desbloqueado'" class="text-emerald-500 font-bold">✅ Desbloqueado</div>
    <div v-else-if="status === 'progresso'" class="text-yellow-500 font-bold">⏳ Em progresso</div>
    <div v-else class="text-gray-500 font-bold">🔒 Bloqueado</div>
  </div>
</template>

<script setup>
defineProps({
  titulo: String,
  descricao: String,
  icone: String,
  status: String,
})
</script>
