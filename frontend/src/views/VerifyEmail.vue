<template>
  <div
    class="verify-email min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4"
  >
    <div class="card w-full max-w-lg bg-base-100 shadow-2xl">
      <div class="card-body text-center">
        <!-- Header -->
        <div class="mb-6">
          <div class="avatar placeholder mb-4">
            <div class="bg-primary text-primary-content rounded-full w-16 h-16">
              <span class="text-2xl">📧</span>
            </div>
          </div>
          <h1 class="text-2xl font-bold text-base-content">Verificação de E-mail</h1>
        </div>

        <!-- Loading State -->
        <div v-if="loading && !success && !error" class="space-y-6">
          <div class="flex flex-col items-center gap-4">
            <span class="loading loading-spinner loading-lg text-primary"></span>
            <div class="space-y-2">
              <p class="text-lg font-semibold text-base-content">Confirmando seu e-mail...</p>
              <p class="text-sm text-base-content/70">Por favor, aguarde alguns instantes</p>
            </div>
          </div>

          <!-- Progress bar animation -->
          <div class="w-full bg-base-200 rounded-full h-2">
            <div
              class="bg-primary h-2 rounded-full animate-pulse"
              :style="{ width: progressWidth + '%' }"
            ></div>
          </div>
        </div>

        <!-- Success State -->
        <div v-else-if="success && !loading" class="space-y-6">
          <!-- Success Animation -->
          <div class="flex justify-center">
            <div class="relative">
              <div
                class="w-20 h-20 bg-success rounded-full flex items-center justify-center animate-bounce"
              >
                <svg
                  class="w-10 h-10 text-success-content"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="3"
                    d="M5 13l4 4L19 7"
                  ></path>
                </svg>
              </div>
              <!-- Ripple effect -->
              <div
                class="absolute inset-0 w-20 h-20 bg-success rounded-full animate-ping opacity-20"
              ></div>
            </div>
          </div>

          <div class="alert alert-success">
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
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span>{{ success }}</span>
          </div>

          <!-- Redirect countdown -->
          <div class="bg-base-200 rounded-lg p-4">
            <p class="text-sm text-base-content/70 mb-2">
              Redirecionando para o login em {{ countdown }}s...
            </p>
            <progress
              class="progress progress-primary w-full"
              :value="(5 - countdown) * 20"
              max="100"
            ></progress>
          </div>

          <!-- Manual redirect button -->
          <button @click="goToLogin" class="btn btn-primary btn-wide">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 713-3h7a3 3 0 013 3v1"
              ></path>
            </svg>
            Ir para Login
          </button>
        </div>

        <!-- Error State -->
        <div v-else-if="error && !loading" class="space-y-6">
          <!-- Error Animation -->
          <div class="flex justify-center">
            <div class="relative">
              <div
                class="w-20 h-20 bg-error rounded-full flex items-center justify-center animate-pulse"
              >
                <svg
                  class="w-10 h-10 text-error-content"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="3"
                    d="M6 18L18 6M6 6l12 12"
                  ></path>
                </svg>
              </div>
            </div>
          </div>

          <div class="alert alert-error">
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
          </div>

          <!-- Action buttons -->
          <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <button @click="retry" class="btn btn-outline btn-primary">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                ></path>
              </svg>
              Tentar Novamente
            </button>
            <button @click="goToLogin" class="btn btn-ghost">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 713-3h7a3 3 0 713 3v1"
                ></path>
              </svg>
              Ir para Login
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/services/api'

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const error = ref('')
const success = ref('')
const countdown = ref(5)
const progressWidth = ref(0)

let countdownInterval = null
let progressInterval = null

const startCountdown = () => {
  countdownInterval = setInterval(() => {
    countdown.value--
    if (countdown.value <= 0) {
      clearInterval(countdownInterval)
      goToLogin()
    }
  }, 1000)
}

const startProgressAnimation = () => {
  let progress = 0
  progressInterval = setInterval(() => {
    progress += Math.random() * 30
    if (progress >= 90) {
      progress = 90 // Mantém em 90% até completar requisição
      clearInterval(progressInterval)
    }
    progressWidth.value = progress
  }, 200)
}

const completeProgress = () => {
  if (progressInterval) clearInterval(progressInterval)
  progressWidth.value = 100
}

const verifyEmail = async () => {
  const token = route.params.token

  if (!token) {
    error.value = 'Token de verificação não encontrado.'
    return
  }

  loading.value = true
  error.value = ''
  success.value = ''

  startProgressAnimation()

  try {
    const res = await api.get(`/verify-email/${token}`)

    completeProgress()

    if (res.data && (res.data.message || res.data.token || res.data.user)) {
      success.value = res.data.message || 'E-mail confirmado com sucesso!'
      setTimeout(() => {
        loading.value = false
        startCountdown()
      }, 500)
    } else {
      setTimeout(() => {
        loading.value = false
        error.value = 'Resposta inesperada do servidor.'
      }, 500)
    }
  } catch (err) {
    completeProgress()

    setTimeout(() => {
      loading.value = false

      if (err.response) {
        const status = err.response.status
        const message = err.response.data?.message

        if (status === 422 && message) {
          error.value = message
        } else if (status >= 500) {
          error.value = 'Erro interno do servidor. Tente novamente mais tarde.'
        } else {
          error.value = message || `Erro ${status}: Não foi possível verificar o e-mail.`
        }
      } else if (err.request) {
        error.value = 'Erro de conexão. Verifique sua internet e tente novamente.'
      } else {
        error.value = 'Erro inesperado. Tente novamente.'
      }
    }, 500)
  }
}

const retry = () => {
  // Reset de estados para nova tentativa
  error.value = ''
  success.value = ''
  countdown.value = 5
  progressWidth.value = 0

  if (countdownInterval) clearInterval(countdownInterval)
  if (progressInterval) clearInterval(progressInterval)

  verifyEmail()
}

const goToLogin = () => {
  // Limpa intervalos antes de navegar
  if (countdownInterval) clearInterval(countdownInterval)
  if (progressInterval) clearInterval(progressInterval)
  router.push('/login')
}

// Limpeza de intervalos ao destruir componente
onUnmounted(() => {
  if (countdownInterval) clearInterval(countdownInterval)
  if (progressInterval) clearInterval(progressInterval)
})

// Inicia verificação ao montar componente
onMounted(() => {
  verifyEmail()
})
</script>

<style scoped>
.verify-email {
  background-image:
    radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.1) 0%, transparent 50%),
    radial-gradient(circle at 75% 75%, rgba(99, 102, 241, 0.1) 0%, transparent 50%);
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

.animate-shimmer {
  animation: shimmer 2s infinite;
}
</style>
