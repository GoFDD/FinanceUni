import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const fullName = ref('')
  const email = ref('')
  const password = ref('')
  const confirmPassword = ref('')
  const loading = ref(false)
  const error = ref('')
  const success = ref(false)

  const validateForm = () => {
    if (!fullName.value || !email.value || !password.value || !confirmPassword.value) {
      error.value = 'Por favor, preencha todos os campos.'
      return false
    }
    if (password.value !== confirmPassword.value) {
      error.value = 'As senhas não coincidem.'
      return false
    }
    error.value = ''
    return true
  }

  const register = async () => {
    if (!validateForm()) return

    loading.value = true
    success.value = false

    setTimeout(() => {
      loading.value = false
      success.value = true
      console.log('Registro simulado:', { fullName: fullName.value, email: email.value })
    }, 1200)
  }

  return {
    fullName,
    email,
    password,
    confirmPassword,
    loading,
    error,
    success,
    register,
  }
})
