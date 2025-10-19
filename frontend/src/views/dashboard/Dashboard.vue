<template>
  <div class="flex h-screen bg-[#0f172a] text-gray-200">
    <Sidebar :user="user" @logout="logout" />

    <main class="flex-1 p-6 overflow-y-auto">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/Sidebar.vue'
import authService from '@/services/authService'
import { useRouter } from 'vue-router'

const router = useRouter()
const user = ref(null)

onMounted(async () => {
  user.value = await authService.getUser()
})

const logout = async () => {
  await authService.logout()
  router.push('/login')
}
</script>
