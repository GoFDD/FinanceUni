<template>
  <div
    class="fixed inset-0 bg-black/60 flex items-center justify-center p-4 z-50"
    @click.self="close"
  >
    <div class="bg-[#1a1f2e] w-full max-w-lg p-6 rounded-xl border border-gray-700">
      <h2 class="text-xl font-semibold mb-4">
        {{ isEditing ? "Editar Receita" : "Adicionar Receita" }}
      </h2>

      <form @submit.prevent="submit">
        <div class="grid grid-cols-1 gap-4">

          <!-- DESCRIÇÃO -->
          <div>
            <label class="block text-sm text-gray-300 mb-1">Descrição</label>
            <input
              v-model="form.description"
              required
              class="w-full bg-[#0a0e1a] px-3 py-2 rounded border border-gray-700 text-white"
            />
          </div>

          <!-- CATEGORIA -->
          <div>
            <label class="block text-sm text-gray-300 mb-1">Categoria</label>
            <select
              v-model="form.category_id"
              class="w-full bg-[#0a0e1a] px-3 py-2 rounded border border-gray-700 text-white"
            >
              <option :value="null">Sem categoria</option>

              <option
                v-for="cat in categorias"
                :key="cat.id"
                :value="cat.id"
              >
                {{ cat.icon ? cat.icon + ' ' : '' }}{{ cat.name }}
              </option>
            </select>
          </div>

          <!-- VALOR + DATA -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm text-gray-300 mb-1">Valor</label>
              <input
                v-model.number="form.amount"
                type="number"
                step="0.01"
                required
                class="w-full bg-[#0a0e1a] px-3 py-2 rounded border border-gray-700 text-white"
              />
            </div>

            <div>
              <label class="block text-sm text-gray-300 mb-1">Data</label>
              <input
                v-model="form.date"
                type="date"
                required
                class="w-full bg-[#0a0e1a] px-3 py-2 rounded border border-gray-700 text-white"
              />
            </div>
          </div>

          <!-- RECORRENTE -->
          <div class="flex items-center gap-3">
            <input
              type="checkbox"
              v-model="form.is_recurring"
              id="rec"
              class="w-4 h-4"
            />
            <label for="rec" class="text-sm text-gray-300">
              Receita recorrente
            </label>
          </div>

          <!-- NOTAS -->
          <div>
            <label class="block text-sm text-gray-300 mb-1">Observações</label>
            <textarea
              v-model="form.notes"
              rows="3"
              class="w-full bg-[#0a0e1a] px-3 py-2 rounded border border-gray-700 text-white"
            ></textarea>
          </div>

        </div>

        <!-- BOTÕES -->
        <div class="mt-4 flex justify-end gap-3">
          <button
            type="button"
            @click="close"
            class="px-4 py-2 bg-gray-700 rounded"
          >
            Cancelar
          </button>

          <button class="px-4 py-2 bg-emerald-600 rounded">
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  transacao: { type: Object, default: null },
  categorias: { type: Array, default: () => [] }
})

const emit = defineEmits(['fechar', 'salvar'])

const isEditing = ref(false)

const form = ref({
  description: '',
  category_id: null,
  amount: '',
  date: new Date().toISOString().split('T')[0],
  is_recurring: false,
  notes: ''
})

watch(
  () => props.transacao,
  (v) => {
    if (v) {
      isEditing.value = true
      form.value = {
        description: v.description || '',
        category_id: v.category_id || null,
        amount: v.amount,
        date: v.date
          ? new Date(v.date).toISOString().split('T')[0]
          : new Date().toISOString().split('T')[0],
        is_recurring: !!v.is_recurring,
        notes: v.notes || ''
      }
    } else {
      isEditing.value = false
      form.value = {
        description: '',
        category_id: null,
        amount: '',
        date: new Date().toISOString().split('T')[0],
        is_recurring: false,
        notes: ''
      }
    }
  },
  { immediate: true }
)

function close() {
  emit('fechar')
}

function submit() {
  emit('salvar', {
    ...form.value,
    amount: Number(form.value.amount)
  })
}
</script>

