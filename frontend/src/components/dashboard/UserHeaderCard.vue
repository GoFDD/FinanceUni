<template>
  <div class="bg-slate-800 rounded-xl p-6 flex flex-col space-y-3">
    <div class="flex justify-between items-center">
      <h2 class="text-lg font-semibold">Olá, {{ user.nome }}</h2>
      <span class="text-sm text-gray-400">Level {{ user.level }}</span>
    </div>
    <div class="w-full">
      <p class="text-xs text-gray-400 mb-1">Progresso para o nível {{ user.level + 1 }}</p>
      <div class="h-3 w-full bg-gray-700 rounded-full overflow-hidden">
        <div
          class="h-full bg-gradient-to-r from-purple-500 to-green-500"
          :style="{ width: progresso + '%' }"
        ></div>
      </div>
      <p class="text-xs text-gray-400 mt-1">{{ user.xp }} / {{ user.nextLevelXp }} XP</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  user: Object,
})

const progresso = (props.user.xp / props.user.nextLevelXp) * 100
</script>
