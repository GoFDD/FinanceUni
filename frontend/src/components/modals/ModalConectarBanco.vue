<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 w-96 shadow-lg">
      <h2 class="text-xl font-semibold mb-4">Conectar Banco</h2>

      <button 
        @click="$emit('connect-demo')"
        class="w-full mb-3 py-2 bg-blue-600 text-white rounded-lg"
      >
        Conectar via Pluggy (Demo)
      </button>

      <button 
        @click="$emit('connect-manual')"
        class="w-full py-2 bg-gray-500 text-white rounded-lg"
      >
        Adicionar Banco Fictício
      </button>

      <button 
        @click="$emit('close')"
        class="mt-4 text-gray-400 hover:text-gray-600 text-sm w-full text-center"
      >
        Cancelar
      </button>
    </div>
  </div>
</template>
