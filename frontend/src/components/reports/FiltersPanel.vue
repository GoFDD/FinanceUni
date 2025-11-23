<template>
  <div class="bg-slate-900/60 p-4 rounded-xl shadow mb-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
      <div class="col-span-1">
        <label class="text-xs text-slate-300">Período</label>
        <div class="flex gap-2">
          <input v-model="localFrom" type="date" class="w-full p-2 bg-slate-800 rounded-md text-sm" />
          <input v-model="localTo" type="date" class="w-full p-2 bg-slate-800 rounded-md text-sm" />
        </div>
      </div>

      <div>
        <label class="text-xs text-slate-300">Tipo</label>
        <select v-model="localType" class="w-full p-2 bg-slate-800 rounded-md text-sm">
          <option value="todas">Todas as transações</option>
          <option value="despesas">Despesas</option>
          <option value="receitas">Receitas</option>
        </select>
      </div>

      <div>
        <label class="text-xs text-slate-300">Conta</label>
        <select v-model="localAccount" class="w-full p-2 bg-slate-800 rounded-md text-sm">
          <option :value="null">Todos</option>
          <option v-for="a in accounts" :key="a.id" :value="a.id">{{ a.name }}</option>
        </select>
      </div>

      <div class="flex gap-2">
        <button @click="apply" class="px-4 py-2 bg-emerald-600 rounded-md">Aplicar</button>
        <button @click="onReset" class="px-4 py-2 bg-slate-800 rounded-md">Resetar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, toRefs } from 'vue'
const props = defineProps({
  accounts: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  period: { type: Object, default: () => ({ from: null, to: null }) },
  typeFilter: { type: String, default: 'todas' },
  accountId: { type: [String, Number, null], default: null }
})
const emit = defineEmits(['apply','reset','update:period','update:typeFilter','update:accountId'])

// local copies (v-model style)
const localFrom = ref(props.period?.from ?? null)
const localTo = ref(props.period?.to ?? null)
const localType = ref(props.typeFilter ?? 'todas')
const localAccount = ref(props.accountId ?? null)

watch(() => props.period, (v) => {
  localFrom.value = v.from
  localTo.value = v.to
})
watch(() => props.typeFilter, (v) => localType.value = v)
watch(() => props.accountId, (v) => localAccount.value = v)

function apply() {
  emit('update:period', { from: localFrom.value, to: localTo.value })
  emit('update:typeFilter', localType.value)
  emit('update:accountId', localAccount.value)
  emit('apply')
}

function onReset() {
  localFrom.value = null
  localTo.value = null
  localType.value = 'todas'
  localAccount.value = null
  emit('reset')
}
</script>

<style scoped>
</style>
