<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  initialData: Object,
  mode: { type: String, default: 'income' }, // income | expense
});

const emits = defineEmits(['close', 'save']);

const form = ref({
  description: '',
  amount: '',
  date: new Date().toISOString().split('T')[0],
  category_id: null,
  is_recurring: false,
  notes: ''
});

watch(() => props.initialData, (val) => {
  if (val) {
    form.value = {
      ...val,
      date: val.date ? val.date.split('T')[0] : new Date().toISOString().split('T')[0],
    };
  }
}, { immediate: true });

function salvar() {
  emits('save', form.value);
}
</script>

<template>
  <div v-if="show" class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50">
    <div class="bg-[#1a1f2e] border border-gray-800/50 p-6 rounded-xl w-full max-w-lg">

      <h2 class="text-xl font-bold mb-4">
        {{ initialData ? 'Editar' : 'Adicionar' }} {{ mode === 'income' ? 'Receita' : 'Despesa' }}
      </h2>

      <form @submit.prevent="salvar" class="space-y-4">

        <div>
          <label class="text-sm text-gray-300">Descrição</label>
          <input v-model="form.description" class="input" required />
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-sm text-gray-300">Valor</label>
            <input v-model="form.amount" type="number" step="0.01" class="input" required />
          </div>

          <div>
            <label class="text-sm text-gray-300">Data</label>
            <input v-model="form.date" type="date" class="input" required />
          </div>
        </div>

        <div>
          <label class="text-sm text-gray-300">Observações</label>
          <textarea v-model="form.notes" class="input h-20"></textarea>
        </div>

        <div class="flex gap-3 pt-4">
          <button type="button" class="btn-secondary w-full" @click="$emit('close')">Cancelar</button>
          <button class="btn-primary w-full">Salvar</button>
        </div>

      </form>
    </div>
  </div>
</template>

<style scoped>
.input {
  @apply w-full bg-[#0a0e1a] border border-gray-800/50 rounded-lg px-3 py-2 text-white;
}
.btn-primary {
  @apply bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg py-2 font-semibold;
}
.btn-secondary {
  @apply bg-gray-800 hover:bg-gray-700 text-white rounded-lg py-2;
}
</style>
