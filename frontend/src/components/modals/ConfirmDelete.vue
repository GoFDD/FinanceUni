<template>
  <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50" @click.self="cancel">
    <div class="bg-white dark:bg-[#1a1f2e] p-6 rounded-lg w-full max-w-md">
      <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Confirmar exclusão</h3>
      <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">Deseja realmente excluir <strong>{{ itemText }}</strong>?</p>

      <div class="flex justify-end gap-2">
        <button @click="cancel" class="px-4 py-2 rounded bg-gray-200 dark:bg-gray-700">Cancelar</button>
        <button @click="confirm" :disabled="loading" class="px-4 py-2 rounded bg-red-600 text-white">
          <span v-if="!loading">Excluir</span>
          <span v-else>Excluindo...</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  item: { type: Object, default: null }
})

const emit = defineEmits(['cancelar', 'confirmar'])

const loading = ref(false)

const itemText = computed(() => {
  if (!props.item) return ''
  return props.item.description || props.item.title || `#${props.item.id}`
})

function cancel() {
  emit('cancelar')
}

async function confirm() {
  if (loading.value) return
  loading.value = true
  try {
    emit('confirmar')
  } finally {
    loading.value = false
  }
}
</script>

