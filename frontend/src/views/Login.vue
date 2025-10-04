<template>
  <div class="min-h-screen flex items-center justify-center p-8">
    <div class="card shadow-2xl bg-base-100 rounded-xl p-8 w-full max-w-md">
      <div class="flex items-center gap-2 mb-4">
        <span class="text-3xl font-bold text-green-700">💰</span>
        <span class="text-xl font-semibold text-gray-800">FinanceUni</span>
      </div>

      <h2 class="text-3xl font-bold text-center mb-2">Faça login</h2>
      <p class="text-center text-gray-500 mb-4">Acesse sua conta para gerenciar suas finanças</p>

      <!-- Erro -->
      <div v-if="error" role="alert" class="alert alert-error mb-4">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="stroke-current shrink-0 h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
          />
        </svg>
        <span>{{ error }}</span>
        <button
          v-if="error.includes('não confirmado')"
          @click="resendVerification"
          class="btn btn-sm btn-link text-red-600"
        >
          Reenviar e-mail de verificação
        </button>
      </div>

      <form class="flex flex-col gap-4" @submit.prevent="handleLogin" novalidate>
        <!-- Email -->
        <div class="form-control">
          <label class="label"><span class="label-text">Email</span></label>
          <div class="relative">
            <input
              v-model="email"
              type="email"
              placeholder="seu@exemplo.com"
              class="input input-bordered w-full pl-10 focus:placeholder-transparent"
            />
            <span
              class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
            >
              <font-awesome-icon icon="envelope" />
            </span>
          </div>
        </div>

        <!-- Password -->
        <div class="form-control">
          <label class="label"><span class="label-text">Senha</span></label>
          <div class="relative">
            <input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              placeholder="Sua senha"
              class="input input-bordered w-full pl-10 pr-10 focus:placeholder-transparent"
            />
            <span
              class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
            >
              <font-awesome-icon icon="lock" />
            </span>
            <button
              type="button"
              @click="togglePassword"
              class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-600"
            >
              <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
            </button>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="form-control">
          <label class="label cursor-pointer flex items-center gap-2">
            <input type="checkbox" v-model="rememberMe" class="checkbox checkbox-primary" />
            <span class="label-text">Lembrar-me</span>
          </label>
        </div>

        <button
          type="submit"
          class="btn btn-success w-full mt-2 hover:scale-105 transition-transform duration-200"
          :disabled="loading"
        >
          <span v-if="loading" class="loading loading-spinner"></span>
          {{ loading ? 'Entrando...' : 'Entrar' }}
        </button>
      </form>

      <p class="text-center text-gray-500 mt-6">
        Não tem uma conta?
        <router-link to="/register" class="link link-primary">Cadastre-se</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import authService from '@/services/authService'

const router = useRouter()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const showPassword = ref(false)
const error = ref('')
const loading = ref(false)

const togglePassword = () => (showPassword.value = !showPassword.value)

const handleLogin = async () => {
  error.value = ''
  loading.value = true

  if (!email.value || !password.value) {
    error.value = 'Por favor, preencha todos os campos.'
    loading.value = false
    return
  }

  try {
    await authService.login({
      email: email.value,
      password: password.value,
    })
    router.push('/dashboard')
  } catch (err) {
    error.value = err.message || 'Credenciais inválidas.'
    loading.value = false
  }
}

const resendVerification = async () => {
  error.value = ''
  loading.value = true

  if (!email.value) {
    error.value = 'Por favor, insira seu e-mail para reenviar a verificação.'
    loading.value = false
    return
  }

  try {
    await authService.resendVerification(email.value)
    error.value = 'E-mail de verificação reenviado! Verifique sua caixa de entrada.'
    loading.value = false
  } catch (err) {
    error.value = err.message || 'Erro ao reenviar e-mail de verificação.'
    loading.value = false
  }
}
</script>
