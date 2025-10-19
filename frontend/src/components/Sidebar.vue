<template>
  <aside class="w-64 bg-[#1a1d29] flex flex-col justify-between shadow-lg" ref="sidebarRef">
    <!-- Logo -->
    <div class="flex items-center px-6 py-4 border-b border-gray-700">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-7 w-7 text-emerald-500"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 16v-4"
        />
      </svg>
      <span class="ml-3 text-lg font-semibold text-white">FinanceUni</span>
    </div>

    <!-- Menu -->
    <nav class="mt-6 flex-1 px-4 space-y-2">
      <RouterLink
        to="/dashboard"
        class="block px-4 py-2 rounded-md hover:bg-[#13151f] hover:text-emerald-400 transition"
        >🏠 Dashboard</RouterLink
      >
      <RouterLink
        to="/dashboard/despesas"
        class="block px-4 py-2 rounded-md hover:bg-[#13151f] hover:text-emerald-400 transition"
        >💸 Despesas</RouterLink
      >
      <RouterLink
        to="/dashboard/receitas"
        class="block px-4 py-2 rounded-md hover:bg-[#13151f] hover:text-emerald-400 transition"
        >💰 Receitas</RouterLink
      >
      <RouterLink
        to="/dashboard/relatorios"
        class="block px-4 py-2 rounded-md hover:bg-[#13151f] hover:text-emerald-400 transition"
        >📊 Relatórios</RouterLink
      >
      <RouterLink
        to="/dashboard/bancos"
        class="block px-4 py-2 rounded-md hover:bg-[#13151f] hover:text-emerald-400 transition"
        >⚙️ Bancos</RouterLink
      >
    </nav>

    <!-- Avatar + Dropdown -->
    <div class="relative p-4 border-t border-gray-700">
      <div class="flex items-center cursor-pointer" @click.stop="toggleDropdown">
        <img
          :src="avatarUrl"
          alt="Avatar"
          class="w-10 h-10 rounded-full border-2 border-emerald-500"
        />
        <div class="ml-3">
          <p class="text-sm font-medium text-white">{{ user?.name }}</p>
          <p class="text-xs text-gray-400">{{ user?.email }}</p>
        </div>
      </div>

      <div
        v-if="dropdownOpen"
        class="absolute bottom-16 left-0 w-56 bg-[#13151f] border border-gray-700 shadow-xl rounded-lg py-2 z-50"
      >
        <button
          @click="closeDropdown"
          class="w-full text-left px-4 py-2 text-gray-400 hover:text-white hover:bg-[#1a1d29] rounded-md"
        >
          ❌ Fechar
        </button>
        <RouterLink
          to="/dashboard/perfil"
          class="block px-4 py-2 text-gray-300 hover:bg-[#1a1d29] hover:text-emerald-400 rounded-md"
          >⚙️ Perfil</RouterLink
        >
        <button
          @click="emitLogout"
          class="w-full text-left px-4 py-2 text-red-400 hover:bg-[#1a1d29] hover:text-red-500 rounded-md"
        >
          🚪 Sair
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
  user: Object,
  avatarUrl: { type: String, default: 'https://i.pravatar.cc/100' },
})
const emit = defineEmits(['logout'])

const dropdownOpen = ref(false)
const sidebarRef = ref(null)

const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value)
const closeDropdown = () => (dropdownOpen.value = false)
const emitLogout = () => emit('logout')

// Fecha dropdown ao clicar fora, listener único
const handleClickOutside = (event) => {
  if (sidebarRef.value && !sidebarRef.value.contains(event.target)) {
    dropdownOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>
