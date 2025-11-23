<template>
  <aside
    ref="sidebarRef"
    :class="[
      'h-screen bg-[#0D111A] border-r border-[#1C2030] flex flex-col shadow-xl transition-all duration-300',
      collapsed ? 'w-20' : 'w-64'
    ]"
  >
    <!-- HEADER + Collapse Btn -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-[#1C2030]">

      <!-- LOGO -->
      <div class="flex items-center gap-3" v-if="!collapsed">
        <div
          class="w-10 h-10 flex items-center justify-center rounded-xl
          bg-gradient-to-br from-emerald-500/20 to-emerald-700/20
          backdrop-blur-sm shadow-lg shadow-emerald-500/10"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 text-emerald-400"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V4m0 16v-4"
            />
          </svg>
        </div>

        <span class="text-lg font-semibold bg-gradient-to-r from-emerald-300 to-emerald-500 bg-clip-text text-transparent">
          FinanceUni
        </span>
      </div>

      <!-- BOTÃO COLLAPSE -->
      <button
        @click="toggleCollapse"
        class="p-2 rounded-md bg-[#131722] hover:bg-[#182030] transition text-gray-300"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path v-if="!collapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 19l-7-7 7-7"
          />
          <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5l7 7-7 7"
          />
        </svg>
      </button>
    </div>

    <!-- MENU -->
    <nav class="mt-4 flex-1 px-2 space-y-1">

      <SidebarItem to="/dashboard" icon="home" label="Dashboard" :collapsed="collapsed" />
      <div class="border-b border-[#1C2030]/60"></div>

      <SidebarItem to="/dashboard/despesas" icon="arrow-down-circle" label="Despesas" :collapsed="collapsed" />
      <div class="border-b border-[#1C2030]/60"></div>

      <SidebarItem to="/dashboard/receitas" icon="arrow-up-circle" label="Receitas" :collapsed="collapsed" />
      <div class="border-b border-[#1C2030]/60"></div>

      <SidebarItem to="/dashboard/relatorios" icon="bar-chart-3" label="Relatórios" :collapsed="collapsed" />
      <div class="border-b border-[#1C2030]/60"></div>

      <SidebarItem to="/dashboard/bancos" icon="building" label="Bancos" :collapsed="collapsed" />
      <div class="border-b border-[#1C2030]/60"></div>

      <!-- NOVA ROTA PLANOS -->
      <SidebarItem
        to="/dashboard/planos"
        icon="sparkles"
        label="Planos"
        highlight
        :collapsed="collapsed"
      />
      <div class="border-b border-[#1C2030]/60"></div>
    </nav>

    <!-- USER DROPDOWN -->
    <div class="relative p-4 border-t border-[#1C2030]">
      <div
        class="flex items-center gap-3 cursor-pointer group"
        @click.stop="toggleDropdown"
      >
        <img
          :src="avatarUrl"
          class="w-11 h-11 rounded-full border-2 border-emerald-500 shadow-md hover:scale-[1.03] transition"
        />

        <div v-if="!collapsed">
          <p class="text-sm text-white">{{ user?.name }}</p>
          <p class="text-xs text-gray-400">{{ user?.email }}</p>
        </div>
      </div>

      <transition name="fade-slide">
        <div
          v-if="dropdownOpen"
          class="absolute bottom-16 left-4 w-56 bg-[#13151f] border border-gray-700 shadow-xl rounded-xl py-2 z-50"
        >
          <RouterLink to="/dashboard/perfil" @click="closeDropdown" class="dropdown-item">
            ⚙️ Perfil
          </RouterLink>

          <button @click="emitLogout" class="dropdown-item text-red-400 hover:text-red-300">
            🚪 Sair
          </button>
        </div>
      </transition>
    </div>
  </aside>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue"
import SidebarItem from "@/components/layout/SidebarItem.vue"

const props = defineProps({
  user: Object,
  avatarUrl: { type: String, default: "https://i.pravatar.cc/100" },
})

const emit = defineEmits(["logout"])

const dropdownOpen = ref(false)
const collapsed = ref(false)

const toggleDropdown = () => (dropdownOpen.value = !dropdownOpen.value)
const closeDropdown = () => (dropdownOpen.value = false)
const emitLogout = () => emit("logout")

const toggleCollapse = () => (collapsed.value = !collapsed.value)

const sidebarRef = ref(null)
const handleClickOutside = (e) => {
  if (sidebarRef.value && !sidebarRef.value.contains(e.target)) dropdownOpen.value = false
}

onMounted(() => document.addEventListener("click", handleClickOutside))
onUnmounted(() => document.removeEventListener("click", handleClickOutside))
</script>

<style scoped>
.dropdown-item {
  padding: 0.5rem 1rem;
  color: #d1d5db;
  border-radius: 0.375rem;
  transition: 0.2s;
}

.dropdown-item:hover {
  background-color: #1a1d29;
  color: #34d399;
}


.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: 0.18s ease;
}
.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(6px);
}
</style>
