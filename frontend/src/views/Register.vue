<template>
  <div class="min-h-screen flex flex-col lg:flex-row transition-colors duration-500 bg-white dark:bg-gray-900">
    <!-- Metade Visual -->
    <div
      class="lg:w-3/5 bg-gradient-to-br from-green-200 via-blue-200 to-green-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex flex-col justify-center items-center p-8 relative overflow-hidden transition-colors duration-500"
    >
      <h1 class="text-5xl font-bold text-gray-900 dark:text-gray-100 mb-4 text-center lg:text-left transition-colors duration-300">
        Comece sua jornada financeira
      </h1>
      <p class="text-lg text-gray-800 dark:text-gray-300 text-center lg:text-left mb-8 max-w-lg transition-colors duration-300">
        Organize seus gastos, acompanhe metas e desenvolva hábitos financeiros saudáveis enquanto estuda.
      </p>

      <!-- Card ilustrativo -->
      <div
        class="relative w-3/4 h-72 bg-gradient-to-br from-green-200/70 to-green-300/50 dark:from-gray-700/50 dark:to-gray-600/40 rounded-xl shadow-xl backdrop-blur-sm p-6 flex flex-col items-center justify-center overflow-hidden transition-all duration-500"
      >
        <!-- Gráfico decorativo -->
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 120 80"
          class="absolute bottom-0 left-0 w-full opacity-40 dark:opacity-30 transition-opacity duration-300"
        >
          <polyline
            fill="none"
            stroke="currentColor"
            stroke-width="3"
            points="0,70 20,60 40,50 60,65 80,35 100,45 120,20"
            class="text-green-700 dark:text-green-400"
          />
          <circle cx="20" cy="60" r="2.5" class="fill-green-700 dark:fill-green-400" />
          <circle cx="60" cy="65" r="2.5" class="fill-green-700 dark:fill-green-400" />
          <circle cx="100" cy="45" r="2.5" class="fill-green-700 dark:fill-green-400" />
        </svg>

        <!-- Texto dentro do card -->
        <div class="z-10 text-center">
          <h3 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2 transition-colors duration-300">
            Controle suas finanças
          </h3>
          <p class="text-gray-700 dark:text-gray-300 max-w-sm transition-colors duration-300">
            Acompanhe gastos, defina metas e veja sua evolução financeira de forma simples e intuitiva.
          </p>
        </div>
      </div>
    </div>

    <!-- Metade Form -->
    <div class="lg:w-2/5 flex items-center justify-center p-8">
      <div class="card shadow-2xl bg-white dark:bg-gray-800/90 dark:border dark:border-gray-700 rounded-xl p-8 w-full max-w-md transition-colors duration-500">
        <div class="flex items-center gap-2 mb-4">
          <span class="text-3xl font-bold text-green-700 dark:text-green-500">💰</span>
          <span class="text-xl font-semibold text-gray-800 dark:text-gray-200">FinanceUni</span>
        </div>

        <h2 class="text-3xl font-bold text-center mb-2 text-gray-800 dark:text-gray-100">Crie sua conta</h2>
        <p class="text-center text-gray-500 dark:text-gray-400 mb-4">
          Cadastre-se e comece a organizar suas finanças
        </p>

        <!-- Mensagem de verificação -->
        <div v-if="emailSent" role="alert" class="alert alert-info mb-4">
          Cadastro realizado! Verifique seu e-mail para confirmar sua conta.
          <button @click="resendVerification" class="btn btn-sm btn-link text-blue-600">
            Reenviar e-mail
          </button>
        </div>

        <!-- Erro -->
        <div v-if="error" role="alert" class="alert alert-error mb-4">
          {{ error }}
        </div>

        <!-- Formulário -->
        <form class="flex flex-col gap-4" @submit.prevent="handleRegister" novalidate>
          <!-- Nome -->
          <div class="form-control">
            <label class="label">
              <span class="label-text text-gray-700 dark:text-gray-300">Nome completo</span>
            </label>
            <div class="relative">
              <input
                v-model="fullName"
                type="text"
                placeholder="Seu nome completo"
                class="input input-bordered w-full pl-10 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <font-awesome-icon icon="user" />
              </span>
            </div>
          </div>

          <!-- Email -->
          <div class="form-control">
            <label class="label">
              <span class="label-text text-gray-700 dark:text-gray-300">Email</span>
            </label>
            <div class="relative">
              <input
                v-model="email"
                type="email"
                placeholder="seu@exemplo.com"
                class="input input-bordered w-full pl-10 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <font-awesome-icon icon="envelope" />
              </span>
            </div>
          </div>

          <!-- Senha -->
          <div class="form-control">
            <label class="label">
              <span class="label-text text-gray-700 dark:text-gray-300">Senha</span>
            </label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Crie uma senha"
                class="input input-bordered w-full pl-10 pr-10 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <font-awesome-icon icon="lock" />
              </span>
              <button
                type="button"
                @click="togglePassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
              >
                <font-awesome-icon :icon="showPassword ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <!-- Confirmar senha -->
          <div class="form-control">
            <label class="label">
              <span class="label-text text-gray-700 dark:text-gray-300">Confirmar senha</span>
            </label>
            <div class="relative">
              <input
                v-model="confirmPassword"
                :type="showConfirm ? 'text' : 'password'"
                placeholder="Confirme sua senha"
                class="input input-bordered w-full pl-10 pr-10 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500">
                <font-awesome-icon icon="lock" />
              </span>
              <button
                type="button"
                @click="toggleConfirm"
                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
              >
                <font-awesome-icon :icon="showConfirm ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <!-- Tipo de usuário -->
          <div class="form-control">
            <label class="label">
              <span class="label-text text-gray-700 dark:text-gray-300">Tipo de usuário</span>
            </label>
            <select 
              v-model="role" 
              class="select select-bordered w-full text-gray-900 dark:text-gray-100 dark:bg-gray-700 dark:border-gray-600"
            >
              <option disabled value="">Selecione um tipo</option>
              <option value="student">Estudante</option>
              <option value="client">Cliente</option>
            </select>
          </div>

          <!-- Campos dinâmicos -->
          <template v-if="role === 'student'">
            <div class="form-control">
              <label class="label">
                <span class="label-text text-gray-700 dark:text-gray-300">Matrícula</span>
              </label>
              <input
                v-model="student_id"
                type="text"
                placeholder="Sua matrícula"
                class="input input-bordered w-full text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text text-gray-700 dark:text-gray-300">Curso</span>
              </label>
              <input
                v-model="course"
                type="text"
                placeholder="Ex: Engenharia de Software"
                class="input input-bordered w-full text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text text-gray-700 dark:text-gray-300">Universidade</span>
              </label>
              <input
                v-model="university"
                type="text"
                placeholder="Ex: Universidade Federal"
                class="input input-bordered w-full text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
          </template>

          <template v-if="role === 'client'">
            <div class="form-control">
              <label class="label">
                <span class="label-text text-gray-700 dark:text-gray-300">CPF</span>
              </label>
              <input
                v-model="cpf"
                type="text"
                placeholder="000.000.000-00"
                v-mask="'###.###.###-##'"
                class="input input-bordered w-full text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
              />
            </div>
            <div class="form-control">
              <label class="label">
                <span class="label-text text-gray-700 dark:text-gray-300">Data de nascimento</span>
              </label>
              <input
                v-model="birth_date"
                type="date"
                class="input input-bordered w-full text-gray-900 dark:text-gray-100 dark:bg-gray-700"
              />
            </div>
          </template>

          <button
            type="submit"
            class="btn btn-success w-full mt-2 hover:scale-105 transition-transform duration-200"
            :disabled="loading"
          >
            {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
          </button>
        </form>

        <p class="text-center text-gray-500 dark:text-gray-400 mt-6">
          Já tem uma conta?
          <router-link to="/login" class="link link-primary">Faça login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'
import authService from '@/services/authService'

const router = useRouter()

const fullName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const role = ref('')

const student_id = ref('')
const course = ref('')
const university = ref('')
const cpf = ref('')
const birth_date = ref('')

const error = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showConfirm = ref(false)
const emailSent = ref(false)

const togglePassword = () => (showPassword.value = !showPassword.value)
const toggleConfirm = () => (showConfirm.value = !showConfirm.value)

const handleRegister = async () => {
  error.value = ''
  loading.value = true

  if (!fullName.value || !email.value || !password.value || !confirmPassword.value || !role.value) {
    error.value = 'Por favor, preencha todos os campos obrigatórios.'
    loading.value = false
    return
  }

  if (password.value !== confirmPassword.value) {
    error.value = 'As senhas não coincidem.'
    loading.value = false
    return
  }

  const payload = {
    name: fullName.value,
    email: email.value,
    password: password.value,
    password_confirmation: confirmPassword.value,
    role: role.value,
  }

  if (role.value === 'student') {
    payload.student_id = student_id.value
    payload.course = course.value
    payload.university = university.value
  } else if (role.value === 'client') {
    payload.cpf = cpf.value
    payload.birth_date = birth_date.value
  }

  try {
    await api.post('/register', payload)
    emailSent.value = true

    //Limpa campos
    fullName.value = ''
    password.value = ''
    confirmPassword.value = ''
    role.value = ''
    student_id.value = ''
    course.value = ''
    university.value = ''
    cpf.value = ''
    birth_date.value = ''
  } catch (err) {
    error.value = err.response?.data?.message || 'Erro ao cadastrar usuário.'
  } finally {
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
  } catch (err) {
    error.value = err.message || 'Erro ao reenviar e-mail.'
  } finally {
    loading.value = false
  }
}
</script>
