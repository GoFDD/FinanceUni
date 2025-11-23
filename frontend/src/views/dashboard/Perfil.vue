<template>
  <div class="min-h-screen p-8 bg-[#0e100912] text-gray-100">

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-extrabold">Perfil do Usuário</h1>
      <p class="text-gray-400 mt-1">Gerencie suas informações e acompanhe seu progresso financeiro.</p>
    </div>

    <!-- SUMMARY -->
    <div class="flex flex-wrap gap-4 mb-6">
      <div class="flex-1 min-w-[200px] bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Saldo Total</div>
        <div class="mt-2 text-lg font-bold text-green-400">
          {{ formatCurrency(resumo.saldo_total) }}
        </div>
      </div>

      <div class="flex-1 min-w-[200px] bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Conquistas</div>
        <div class="mt-2 text-lg font-bold text-amber-400">
          {{ resumo.conquistas_desbloqueadas }} / {{ resumo.conquistas_totais }}
        </div>
      </div>

      <div class="flex-1 min-w-[200px] bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Metas Ativas</div>
        <div class="mt-2 text-lg font-bold text-sky-400">
          {{ resumo.metas_ativas }}
        </div>
      </div>
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

      <!-- LEFT COLUMN -->
      <div class="lg:col-span-4">
        <div class="bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">

          <div class="flex flex-col items-center">
            <img :src="avatar" class="w-28 h-28 rounded-full border-4 border-green-500" />

            <h2 class="mt-4 text-xl font-semibold">{{ user.name }}</h2>
            <p class="text-sm text-gray-400">{{ user.email }}</p>
            <span class="inline-block mt-2 bg-[#0B1220] text-xs text-gray-300 px-3 py-1 rounded-full border border-gray-700">
              {{ user.title }}
            </span>
          </div>

          <!-- XP -->
          <div class="mt-6">
            <div class="flex justify-between items-center text-sm mb-2">
              <span class="flex items-center gap-2 text-yellow-400">
                ⭐ Nível {{ user.level }}
              </span>
              <span class="text-xs text-gray-400">{{ resumo.total_xp }} XP</span>
            </div>

            <div class="w-full bg-[#081016] rounded-full h-3 overflow-hidden border border-gray-800">
              <div
                class="h-3 bg-gradient-to-r from-green-500 to-sky-500"
                :style="{ width: xpPercent + '%' }"
              ></div>
            </div>
          </div>

          <!-- STREAK -->
          <div class="mt-6 grid grid-cols-2 gap-3">
            <div class="bg-[#0B1220] border border-gray-800 rounded-lg p-3 text-center">
              <div class="text-sm text-gray-400">Sequência</div>
              <div class="mt-2 font-bold text-lg text-green-400">
                {{ streak.streak }} dias
              </div>
            </div>

            <div class="bg-[#0B1220] border border-gray-800 rounded-lg p-3 text-center">
              <div class="text-sm text-gray-400">Melhor Sequência</div>
              <div class="mt-2 font-bold text-lg text-green-400">
                {{ streak.best_streak }}
              </div>
            </div>
          </div>

          <button
            @click="editing = true"
            class="w-full mt-6 py-2 rounded-lg border border-green-500 text-green-400 hover:bg-green-800/10"
          >
            Editar Perfil
          </button>

        </div>
      </div>

      <!-- RIGHT COLUMN -->
      <div class="lg:col-span-8">

        <!-- METAS -->
        <div class="bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Metas</h3>

          <div v-if="metas.length === 0" class="text-gray-500 text-sm">
            Nenhuma meta ativa.
          </div>

          <div
            v-for="m in metas"
            :key="m.id"
            class="p-4 bg-[#081018] border border-gray-800 rounded-lg mb-3"
          >
            <div class="flex justify-between mb-2">
              <div>
                <h4 class="font-semibold">{{ m.nome }}</h4>
                <p class="text-xs text-gray-400 mt-1">
                  R$ {{ m.atual }} de R$ {{ m.meta }}
                </p>
              </div>

              <div class="text-sm text-green-400 font-semibold">
                {{ m.percentual }}%
              </div>
            </div>

            <div class="w-full bg-[#061217] h-3 rounded-full overflow-hidden border border-gray-800">
              <div class="h-3 bg-green-500" :style="{ width: m.percentual + '%' }"></div>
            </div>
          </div>
        </div>

        <!-- CONQUISTAS -->
        <div class="mt-6 bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">
          <div class="flex justify-between mb-4">
            <h3 class="text-xl font-bold">Conquistas</h3>
          </div>

          <div v-if="conquistas.length === 0" class="text-gray-500 text-sm">
            Nenhuma conquista encontrada.
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="ach in conquistas"
              :key="ach.id"
              class="p-4 rounded-lg border border-gray-800 bg-[#081018] flex gap-3"
            >
              <div
                class="w-12 h-12 rounded-lg flex items-center justify-center"
                :class="ach.status === 'completo' ? 'bg-amber-500/20' : 'bg-gray-600/20'"
              >
                ⭐
              </div>

              <div class="flex-1">
                <div class="flex justify-between">
                  <div>
                    <div class="font-semibold">{{ ach.nome }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ ach.descricao }}</div>
                  </div>

                  <div
                    class="text-xs mt-1"
                    :class="ach.status === 'completo' ? 'text-amber-300' : 'text-gray-400'"
                  >
                    {{ ach.statusLabel }}
                  </div>
                </div>

                <div class="text-xs text-gray-500 mt-2">
                  {{ ach.unlocked_at ? 'Desbloqueada em ' + formatDate(ach.unlocked_at) : 'Ainda não desbloqueada' }}
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!-- MODAL -->
    <div v-if="editing" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="w-full max-w-2xl bg-[#071018] border border-gray-800 rounded-2xl p-6">

        <div class="flex justify-between mb-4">
          <h4 class="text-lg font-bold">Editar Perfil</h4>
          <button @click="editing = false" class="text-gray-400 hover:text-white">Fechar</button>
        </div>

        <form @submit.prevent="saveProfile" class="space-y-4">
          <div>
            <label class="text-sm text-gray-300">Nome</label>
            <input v-model="form.name" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100">
          </div>

          <div>
            <label class="text-sm text-gray-300">Email</label>
            <input v-model="form.email" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100">
          </div>

          <div>
            <label class="text-sm text-gray-300">Nova senha</label>
            <input type="password" v-model="form.password" placeholder="Deixe em branco" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100">
          </div>

          <div class="flex gap-3 justify-end">
            <button type="button" @click="editing = false"
              class="px-4 py-2 rounded-md border border-gray-700 text-gray-300">
              Cancelar
            </button>
            <button type="submit"
              class="px-4 py-2 rounded-md bg-green-500 hover:bg-green-600 text-black font-semibold">
              Salvar
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</template>


<script setup>
import { ref, computed, onMounted } from "vue";
import dashboardService from "@/services/dashboardService";

// estados
const user = ref({});
const metas = ref([]);
const conquistas = ref([]);
const resumo = ref({});
const streak = ref({});
const editing = ref(false);

const avatar = "https://i.pravatar.cc/200";
const form = ref({});

// XP %
const xpPercent = computed(() => {
  const xp = resumo.value.total_xp ?? 0;
  const levelXp = (user.value.level ?? 1) * 1000;
  return Math.min(((xp / levelXp) * 100).toFixed(1), 100);
});

function formatCurrency(v) {
  return Number(v).toLocaleString("pt-BR", { style: "currency", currency: "BRL" });
}
function formatDate(d) {
  return new Date(d).toLocaleDateString("pt-BR");
}

async function load() {
  const dashboard = await dashboardService.getDashboardData();

  user.value = dashboard.user;
  metas.value = dashboard.metas;
  conquistas.value = dashboard.conquistas;
  resumo.value = dashboard.resumo;
  streak.value = dashboard.streak;

  form.value = { name: user.value.name, email: user.value.email, password: "" };
}

async function saveProfile() {
  await dashboardService.updateProfile(form.value);
  editing.value = false;
  load();
}

onMounted(load);
</script>
