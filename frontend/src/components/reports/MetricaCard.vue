<template>
  <div
    class="bg-card border border-gray-700 p-4 rounded-lg hover:shadow-lg transition-all duration-300"
  >
    <div class="text-3xl mb-2">{{ data.icone }}</div>
    <p class="text-sm font-medium text-gray-400">{{ data.titulo }}</p>
    <h3 class="text-2xl font-bold">{{ data.valor }}</h3>
    <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
      <div
        :style="{ width: data.percent + '%' }"
        :class="
          data.percent > 70 ? 'bg-green-500' : data.percent > 50 ? 'bg-yellow-500' : 'bg-red-500'
        "
        class="h-2 rounded-full transition-all duration-1000"
      ></div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  data: Object,
})
</script>
