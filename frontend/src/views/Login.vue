<template>
  <div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Metade Visual -->
    <div
      class="lg:w-3/5 bg-gradient-to-br from-green-200 via-blue-200 to-green-100 flex flex-col justify-center items-center p-8 relative overflow-hidden"
    >
      <h1 class="text-5xl font-bold text-green-900 mb-4 text-center lg:text-left">
        Bem-vindo ao FinanceUni
      </h1>
      <p class="text-lg text-green-800 text-center lg:text-left mb-8">
        Gerencie suas finanças universitárias de forma simples, segura e moderna.
      </p>
      <div class="w-3/4 h-64 bg-green-300 rounded-xl shadow-lg flex items-center justify-center">
        <span class="text-green-800">Imagem / Ilustração</span>
      </div>
      <svg
        class="absolute bottom-0 left-0 w-full h-32"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path d="M0,32 C150,0 350,64 500,32 L500,100 L0,100 Z" fill="rgba(21,128,61,0.1)" />
      </svg>
    </div>

    <!-- Metade Form -->
    <div class="lg:w-2/5 flex items-center justify-center p-8">
      <div class="card shadow-2xl bg-base-100 rounded-xl p-8 w-full max-w-md">
        <h2 class="text-3xl font-bold text-center mb-4">Login</h2>

        <div v-if="error" class="alert alert-error mb-4">{{ error }}</div>

        <div class="flex flex-col gap-4">
          <!-- Email -->
          <div class="form-control">
            <label class="label"><span class="label-text">Email</span></label>
            <div class="relative w-full">
              <input
                v-model="email"
                type="email"
                placeholder="Digite seu email"
                class="input input-bordered w-full pl-10 focus:placeholder-transparent"
              />
            </div>
          </div>

          <!-- Senha -->
          <div class="form-control">
            <label class="label"><span class="label-text">Senha</span></label>
            <div class="relative w-full">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Digite sua senha"
                class="input input-bordered w-full pl-10 pr-10 focus:placeholder-transparent"
              />
              <button
                type="button"
                @click="togglePassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1"
              >
                <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <div class="flex items-center justify-between mt-2">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="rememberMe" class="checkbox" /> Lembrar-me
            </label>
            <router-link to="/forgot-password" class="link link-hover text-sm"
              >Esqueci minha senha</router-link
            >
          </div>

          <button
            @click="handleLogin"
            class="btn btn-success w-full mt-4 hover:scale-105 transition-transform duration-200"
          >
            Entrar
          </button>
        </div>

        <p class="text-center text-gray-500 mt-6">
          Não tem uma conta?
          <router-link to="/register" class="link link-primary">Cadastre-se</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import authService from '@/services/authService'
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const showPassword = ref(false)
const error = ref('')

const router = useRouter()

const togglePassword = () => (showPassword.value = !showPassword.value)

const handleLogin = async () => {
  error.value = ''
  if (!email.value || !password.value) {
    error.value = 'Por favor, preencha todos os campos.'
    return
  }

  try {
    const { token, user } = await authService.login({
      email: email.value,
      password: password.value,
    })

    // Armazena token e dados do usuário
    localStorage.setItem('auth_token', token)
    localStorage.setItem('user', JSON.stringify(user))

    // Redireciona para dashboard
    router.push('/dashboard')
  } catch (err) {
    error.value = err.response?.data?.message || 'Credenciais inválidas.'
  }
}
</script>
