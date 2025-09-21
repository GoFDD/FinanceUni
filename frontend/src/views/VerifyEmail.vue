<template>
  <div class="verify-email">
    <p v-if="loading">Confirmando seu e-mail...</p>
    <p v-if="error" class="text-red-500">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const error = ref('')

onMounted(async () => {
  const token = route.params.token
  try {
    const res = await api.get(`/verify-email/${token}`)
    localStorage.setItem('auth_token', res.data.token)
    localStorage.setItem('auth_user', JSON.stringify(res.data.user))
    router.push('/login')
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao confirmar e-mail.'
  } finally {
    loading.value = false
  }
})
</script>
