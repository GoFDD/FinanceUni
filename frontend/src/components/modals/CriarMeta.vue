<template>
  <div 
    v-if="visible"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
  >
    <div class="bg-slate-900 border border-slate-700 rounded-2xl p-6 w-full max-w-md shadow-xl">

      <h2 class="text-xl font-semibold text-gray-100 mb-4">
        Criar Nova Meta
      </h2>

      <!-- Campo: Nome -->
      <div class="mb-4">
        <label class="text-gray-300 text-sm">Título da Meta</label>
        <input 
          v-model="form.title"
          type="text"
          class="w-full mt-1 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-gray-200"
          placeholder="Ex: Economizar 1.000"
        >
      </div>

      <!-- Campo: Categoria -->
      <div class="mb-4">
        <label class="text-gray-300 text-sm">Categoria</label>
        <select 
          v-model="form.category"
          class="w-full mt-1 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-gray-200"
        >
          <option value="economia">Economia</option>
          <option value="viagem">Viagem</option>
          <option value="casa">Casa</option>
          <option value="carro">Carro</option>
          <option value="educacao">Educação</option>
        </select>
      </div>

      <!-- Campo: Valor Alvo -->
      <div class="mb-4">
        <label class="text-gray-300 text-sm">Valor da Meta (R$)</label>
        <input 
          v-model="form.target_value"
          type="number"
          min="1"
          step="0.01"
          class="w-full mt-1 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-gray-200"
          placeholder="1000"
        >
      </div>

      <!-- Campo: Período -->
      <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label class="text-gray-300 text-sm">Início</label>
          <input 
            v-model="form.start_date"
            type="date"
            class="w-full mt-1 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-gray-200"
          >
        </div>
        <div>
          <label class="text-gray-300 text-sm">Fim</label>
          <input 
            v-model="form.end_date"
            type="date"
            class="w-full mt-1 px-3 py-2 rounded-lg bg-slate-800 border border-slate-700 text-gray-200"
          >
        </div>
      </div>

      <!-- Botões -->
      <div class="flex justify-end gap-3 mt-6">
        <button 
          @click="close"
          class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-gray-200"
        >
          Cancelar
        </button>

        <button 
          @click="submit"
          class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white"
        >
          Criar Meta
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import dashboardService from '@/services/dashboardService';

const props = defineProps({
  visible: Boolean
});

const emit = defineEmits(['close', 'created']);

const form = reactive({
  title: '',
  target_value: '',
  category: 'economia',
  start_date: '',
  end_date: ''
});

function close() {
  emit('close');
}

async function submit() {
  try {
    await dashboardService.createUserGoal(form);
    emit('created');
    close();
  } catch (err) {
    console.error('❌ Erro ao criar meta:', err);
    alert('Erro ao criar meta.');
  }
}
</script>
