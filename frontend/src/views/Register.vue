<template>
  <div class="min-h-screen flex flex-col lg:flex-row">
    <!-- Metade Visual -->
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

      <div
        class="w-3/4 h-64 bg-green-300/80 rounded-xl shadow-lg flex items-center justify-center backdrop-blur-sm"
      >
        <span class="text-green-800">Ilustração / Placeholder</span>
      </div>

      <svg
        class="absolute bottom-0 left-0 w-full h-32"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
        aria-hidden="true"
      >
        <path d="M0,32 C150,0 350,64 500,32 L500,100 L0,100 Z" fill="rgba(21,128,61,0.08)" />
      </svg>
    </div>

    <!-- Metade Form -->
    <div class="lg:w-2/5 flex items-center justify-center p-8">
      <div class="card shadow-2xl bg-base-100 rounded-xl p-8 w-full max-w-md">
        <div class="flex items-center gap-2 mb-4">
          <span class="text-3xl font-bold text-green-700">💰</span>
          <span class="text-xl font-semibold text-gray-800">FinanceUni</span>
        </div>

        <h2 class="text-3xl font-bold text-center mb-2">Crie sua conta</h2>
        <p class="text-center text-gray-500 mb-4">Cadastre-se e comece a organizar suas finanças</p>

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
                placeholder="Seu nome completo"
                class="input input-bordered w-full pl-10 focus:placeholder-transparent"
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
                placeholder="Crie uma senha"
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

          <!-- Confirm Password -->
          <div class="form-control">
            <label class="label"><span class="label-text">Confirmar senha</span></label>
            <div class="relative">
              <input
                v-model="confirmPassword"
                :type="showConfirm ? 'text' : 'password'"
                placeholder="Confirme sua senha"
                class="input input-bordered w-full pl-10 pr-10 focus:placeholder-transparent"
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
              >
                <font-awesome-icon :icon="showConfirm ? 'eye-slash' : 'eye'" />
              </button>
            </div>
          </div>

          <!-- Seletor de Role -->
          <div class="form-control">
            <label class="label"><span class="label-text">Tipo de usuário</span></label>
            <select v-model="role" class="select select-bordered w-full">
              <option disabled value="">Selecione um tipo</option>
              <option value="student">Aluno</option>
              <option value="cliente">Cliente</option>
              <option value="university">Universidade</option>
            </select>
          </div>

          <!-- Campos dinâmicos -->
          <template v-if="role === 'student'">
            <input
              v-model="student_id"
              type="text"
              placeholder="Matrícula"
              class="input input-bordered w-full"
            />
            <input
              v-model="course"
              type="text"
              placeholder="Curso"
              class="input input-bordered w-full"
            />
            <input
              v-model="university"
              type="text"
              placeholder="Universidade"
              class="input input-bordered w-full"
            />
          </template>

          <template v-if="role === 'cliente'">
            <input
              v-model="cpf"
              type="text"
              placeholder="CPF"
              class="input input-bordered w-full"
            />
            <input
              v-model="birth_date"
              type="date"
              placeholder="Data de nascimento"
              class="input input-bordered w-full"
            />
          </template>

          <template v-if="role === 'university'">
            <input
              v-model="university_name"
              type="text"
              placeholder="Nome da Universidade"
              class="input input-bordered w-full"
            />
            <input
              v-model="cnpj"
              type="text"
              placeholder="CNPJ"
              class="input input-bordered w-full"
            />
            <input
              v-model="address"
              type="text"
              placeholder="Endereço"
              class="input input-bordered w-full"
            />
          </template>

          <button
            type="submit"
            class="btn btn-success w-full mt-2 hover:scale-105 transition-transform duration-200"
            :disabled="loading"
          >
            {{ loading ? 'Cadastrando...' : 'Cadastrar' }}
          </button>
        </form>

        <p class="text-center text-gray-500 mt-6">
          Já tem uma conta?
          <router-link to="/login" class="link link-primary">Faça login</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const fullName = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')
const role = ref('')

// Campos extras
const student_id = ref('')
const course = ref('')
const university = ref('')
const cpf = ref('')
const birth_date = ref('')
const university_name = ref('')
const cnpj = ref('')
const address = ref('')

// Estados auxiliares
const error = ref('')
const loading = ref(false)
const showPassword = ref(false)
const showConfirm = ref(false)

const togglePassword = () => (showPassword.value = !showPassword.value)
const toggleConfirm = () => (showConfirm.value = !showConfirm.value)

const handleRegister = async () => {
  if (!fullName.value || !email.value || !password.value || !confirmPassword.value || !role.value) {
    error.value = 'Por favor, preencha todos os campos obrigatórios.'
    return
  }
  if (password.value !== confirmPassword.value) {
    error.value = 'As senhas não coincidem.'
    return
  }

  loading.value = true
  error.value = ''

  const payload = {
    name: fullName.value,
    email: email.value,
    password: password.value,
    role: role.value,
  }

  if (role.value === 'student') {
    payload.student_id = student_id.value
    payload.course = course.value
    payload.university = university.value
  }
  if (role.value === 'cliente') {
    payload.cpf = cpf.value
    payload.birth_date = birth_date.value
  }
  if (role.value === 'university') {
    payload.university_name = university_name.value
    payload.cnpj = cnpj.value
    payload.address = address.value
  }

  try {
    const response = await axios.post('/api/register', payload)
    if (response.status === 201 || response.status === 200) {
      router.push('/login')
    } else {
      error.value = 'Erro ao cadastrar, tente novamente.'
    }
  } catch (err) {
    console.error(err)
    error.value = err.response?.data?.message || 'Erro ao cadastrar, tente novamente.'
  } finally {
    loading.value = false
  }
}
</script>
