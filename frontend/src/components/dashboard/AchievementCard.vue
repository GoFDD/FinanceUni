<template>
  <div
    class="flex justify-between items-center p-3 rounded-lg shadow-sm bg-[#1E293B]"
    :class="{
      'bg-green-600 text-white': status === 'complete',
      'bg-yellow-600 text-white': status === 'inprogress',
      'opacity-50': status === 'locked',
    }"
  >
    <span>{{ titulo }}</span>
    <span class="text-sm">{{ xp }} XP</span>
  </div>
</template>

<script setup>
const props = defineProps({
  titulo: String,
  xp: String,
  status: String,
})
</script>
