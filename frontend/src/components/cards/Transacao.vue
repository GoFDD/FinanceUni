<template>
  <div
    class="flex items-center justify-between bg-[#18181b] p-3 rounded hover:bg-gray-800 transition"
  >
    <div class="flex items-center gap-3">
      <div
        class="w-8 h-8 rounded-full flex items-center justify-center bg-gray-600 text-white text-sm"
      >
        {{ categoria[0] }}
      </div>
      <div class="flex flex-col">
        <span class="text-gray-200 font-medium">{{ descricao }}</span>
        <span class="text-gray-400 text-xs">{{ data }}</span>
      </div>
    </div>
    <div class="flex items-center gap-4">
      <span class="px-2 py-1 rounded text-xs" :class="categoriaColor">{{ categoria }}</span>
      <span class="px-2 py-1 rounded text-xs" :class="bancoColor">{{ banco }}</span>
      <span :class="valorColor" class="font-bold">{{ valor }}</span>
      <div class="flex gap-1">
        <button class="text-gray-400 hover:text-blue-400 text-xs">Editar</button>
        <button class="text-gray-400 hover:text-red-500 text-xs">Excluir</button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  descricao: String,
  categoria: String,
  banco: String,
  valor: String,
  data: String,
  cor: String,
})

const valorColor = computed(() => (cor === 'red' ? 'text-red-500' : 'text-green-400'))
const categoriaColor = computed(() => 'bg-gray-700 text-white')
const bancoColor = computed(() => 'bg-gray-600 text-white')
</script>
