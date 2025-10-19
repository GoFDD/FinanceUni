<template>
  <div class="bg-[#18181b] p-4 rounded-lg border border-[#27272a] flex flex-col justify-between">
    <div>
      <div class="flex justify-between items-start">
        <p class="font-semibold text-white">{{ nome }}</p>
        <span :class="`w-3 h-3 rounded-full mt-1 bg-${cor}-500`"></span>
      </div>
      <p class="text-sm text-gray-400">{{ tipo }}</p>
    </div>

    <div class="mt-4">
      <p class="text-lg font-bold text-green-500">{{ saldo }}</p>
      <p class="text-sm text-gray-400">{{ transacoes }} transações este mês</p>
    </div>

    <div class="mt-4 flex justify-between">
      <button class="text-sm text-blue-500 hover:underline">Ver</button>
      <button class="text-sm text-yellow-400 hover:underline">Editar</button>
      <button class="text-sm text-red-500 hover:underline">Excluir</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  nome: String,
  tipo: String,
  saldo: String,
  transacoes: Number,
  cor: String,
})
</script>
