<template>
  <RouterLink
    :to="to"
    class="flex items-center gap-3 px-4 py-2 rounded-lg transition group select-none"
    :class="[
      isActive
        ? 'bg-emerald-500/15 text-emerald-400 shadow-inner shadow-emerald-500/10'
        : 'text-gray-400 hover:bg-[#141825] hover:text-emerald-300',
      highlight ? 'border border-emerald-500/30 shadow-lg shadow-emerald-500/10' : ''
    ]"
  >
    <component
      :is="iconComp"
      class="w-5 h-5 flex-shrink-0"
      :class="isActive ? 'text-emerald-400' : 'text-gray-500 group-hover:text-emerald-300'"
    />

    <!-- Esconde texto ao recolher -->
    <span v-if="!collapsed" class="text-sm font-medium truncate">
      {{ label }}
    </span>
  </RouterLink>
</template>

<script setup>
import { computed } from "vue"
import { useRoute } from "vue-router"
import {
  Home,
  ArrowDownCircle,
  ArrowUpCircle,
  BarChart3,
  Building,
  Sparkles
} from "lucide-vue-next"

const props = defineProps({
  to: String,
  icon: String,
  label: String,
  highlight: Boolean,
  collapsed: Boolean,
})

const route = useRoute()

const icons = {
  home: Home,
  "arrow-down-circle": ArrowDownCircle,
  "arrow-up-circle": ArrowUpCircle,
  "bar-chart-3": BarChart3,
  building: Building,
  sparkles: Sparkles,
}

const iconComp = computed(() => icons[props.icon] || Home)

const isActive = computed(() => route.path.startsWith(props.to))
</script>
