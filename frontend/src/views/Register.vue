<template>
  <div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Metade Visual  -->
    <div
      class="lg:w-3/5 bg-gradient-to-br from-green-200 via-blue-200 to-green-100 flex flex-col justify-center items-center p-8 relative overflow-hidden"
    >
      <h1 class="text-5xl font-bold text-green-900 mb-4 text-center lg:text-left">
        Comece sua jornada financeira
      </h1>
      <p class="text-lg text-green-800 text-center lg:text-left mb-8 max-w-lg">
        Organize seus gastos, acompanhe metas e desenvolva hábitos financeiros saudáveis enquanto
        estuda.
      </p>

      <!-- (vou trocar por img) -->
      <div
        class="w-3/4 h-64 bg-green-300/80 rounded-xl shadow-lg flex items-center justify-center backdrop-blur-sm"
      >
        <span class="text-green-800">Ilustração / Placeholder</span>
      </div>

      <!--ondulado discreto no bottom -->
      <svg
        class="absolute bottom-0 left-0 w-full h-32"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
      >
        <path d="M0,32 C150,0 350,64 500,32 L500,100 L0,100 Z" fill="rgba(21,128,61,0.08)" />
      </svg>
    </div>

    <!-- Metade Form (direita) -->
    <div class="lg:w-2/5 flex items-center justify-center p-8">
      <div class="card shadow-2xl bg-base-100 rounded-xl p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex items-center gap-2 mb-4">
          <span class="text-3xl font-bold text-green-700">💰</span>
          <span class="text-xl font-semibold text-gray-800">FinanceUni</span>
        </div>

        <h2 class="text-3xl font-bold text-center mb-2">Crie sua conta</h2>
        <p class="text-center text-gray-500 mb-4">Cadastre-se e comece a organizar suas finanças</p>

        <!-- Mensagem de erro -->
        <div v-if="error" role="alert" class="alert alert-error mb-4">
          {{ error }}
        </div>

        <form class="flex flex-col gap-4" @submit.prevent="handleRegister" novalidate>
          <!-- Nome -->
          <div class="form-control">
            <label class="label"><span class="label-text">Nome completo</span></label>
            <div class="relative">
              <input
                v-model="fullName"
                type="text"
                inputmode="text"
                placeholder="Seu nome completo"
                class="input input-bordered w-full pl-10 focus:placeholder-transparent"
                aria-label="Nome completo"
                :aria-invalid="error && !fullName ? 'true' : 'false'"
              />
              <span
                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
              >
                <font-awesome-icon icon="user" />
              </span>
            </div>
          </div>

          <!-- Email -->
          <div class="form-control">
            <label class="label"><span class="label-text">Email</span></label>
            <div class="relative">
              <input
                v-model="email"
                type="email"
                placeholder="seu@exemplo.com"
                class="input input-bordered w-full pl-10 focus:placeholder-transparent"
                aria-label="Email"
                :aria-invalid="error && !email ? 'true' : 'false'"
              />
              <span
                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
              >
                <font-awesome-icon icon="envelope" />
              </span>
            </div>
          </div>

          <!-- Senha -->
          <div class="form-control">
            <label class="label"><span class="label-text">Senha</span></label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Crie uma senha"
                class="input input-bordered w-full pl-10 pr-10 focus:placeholder-transparent"
                aria-label="Senha"
                :aria-invalid="error && !password ? 'true' : 'false'"
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
                :aria-pressed="showPassword"
                :title="showPassword ? 'Ocultar senha' : 'Mostrar senha'"
              >
                <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <!-- Confirmar Senha -->
          <div class="form-control">
            <label class="label"><span class="label-text">Confirmar senha</span></label>
            <div class="relative">
              <input
                v-model="confirmPassword"
                :type="showConfirm ? 'text' : 'password'"
                placeholder="Confirme sua senha"
                class="input input-bordered w-full pl-10 pr-10 focus:placeholder-transparent"
                aria-label="Confirmar senha"
                :aria-invalid="error && password !== confirmPassword ? 'true' : 'false'"
              />
              <span
                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
              >
                <font-awesome-icon icon="lock" />
              </span>
              <button
                type="button"
                @click="toggleConfirm"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-600"
                :aria-pressed="showConfirm"
                :title="showConfirm ? 'Ocultar senha' : 'Mostrar senha'"
              >
                <font-awesome-icon :icon="showConfirm ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <!-- Botão principal -->
          <button
            type="submit"
            class="btn"
            :class="
              loading
                ? 'btn-disabled btn-success w-full mt-2'
                : 'btn-success w-full mt-2 hover:scale-105 transition-transform duration-200'
            "
            :disabled="loading"
            aria-busy="loading"
          >
            {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
          </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
          Já tem uma conta?
          <router-link to="/login" class="link link-primary">Faça login</router-link>
        </p>

        <p class="text-center text-gray-400 text-xs mt-4">
          <a href="#" class="link link-hover">Termos</a> •
          <a href="#" class="link link-hover">Privacidade</a>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Campos do formulário
const fullName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

// Estados auxiliares
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showConfirm = ref(false)

// Ações
const togglePassword = () => (showPassword.value = !showPassword.value)
const toggleConfirm = () => (showConfirm.value = !showConfirm.value)

const handleRegister = async () => {
  // validação básica (apenas demo/front)
  if (!fullName.value || !email.value || !password.value || !confirmPassword.value) {
    error.value = 'Por favor, preencha todos os campos.'
    return
  }
  if (password.value !== confirmPassword.value) {
    error.value = 'As senhas não coincidem.'
    return
  }

  error.value = ''
  loading.value = true

  // Simulando requisição
  setTimeout(() => {
    loading.value = false
    // substituir por integração com o back-end (axios/fetch)
    console.log('Registro simulado', { fullName: fullName.value, email: email.value })
  }, 1200)
}
</script>

<style scoped>
.input:focus {
  border-color: #15803d;
  box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.12);
}
</style>
