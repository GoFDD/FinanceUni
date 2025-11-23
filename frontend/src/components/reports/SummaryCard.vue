<template>
  <div class="bg-slate-800/50 p-4 rounded-lg flex items-center justify-between">
    <div>
      <div class="text-xs text-slate-300">{{ title }}</div>
      <div class="text-lg font-semibold mt-1">{{ value }}</div>
    </div>
    <div class="text-2xl">{{ icon }}</div>
  </div>
</template>

<script setup>
const props = defineProps({
  title: String,
  value: [String, Number],
  icon: { type: String, default: '' }
})
</script>

<style scoped></style>
