<template>
  <div class="min-h-screen p-8 bg-[#071017] text-gray-100">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-extrabold">Perfil do Usuário</h1>
      <p class="text-gray-400 mt-1">Gerencie suas informações e acompanhe seu progresso financeiro.</p>
    </div>

    <!-- Top summary badges -->
    <div class="flex gap-4 mb-6">
      <div class="flex-1 max-w-xs bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Total Economizado</div>
        <div class="mt-2 text-lg font-bold text-green-400">R$ 15.420</div>
      </div>

      <div class="flex-1 max-w-xs bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Conquistas</div>
        <div class="mt-2 text-lg font-bold text-amber-400">3 / 6</div>
      </div>

      <div class="flex-1 max-w-xs bg-[#0F1720] border border-gray-800 rounded-xl p-4 shadow-sm">
        <div class="text-xs text-gray-400">Metas Ativas</div>
        <div class="mt-2 text-lg font-bold text-sky-400">3</div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left column: perfil -->
      <div class="lg:col-span-4">
        <div class="bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">
          <!-- avatar -->
          <div class="flex flex-col items-center">
            <div class="relative">
              <img
                :src="user.avatar"
                alt="avatar"
                class="w-28 h-28 rounded-full border-4 border-green-500"
              />
              <!-- small badge -->
              <div class="absolute -right-1 -bottom-1 bg-amber-500 rounded-full w-8 h-8 flex items-center justify-center shadow">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              </div>
            </div>

            <h2 class="mt-4 text-xl font-semibold">{{ user.name }}</h2>
            <p class="text-sm text-gray-400">{{ user.email }}</p>
            <span class="inline-block mt-2 bg-[#0B1220] text-xs text-gray-300 px-3 py-1 rounded-full border border-gray-700">Estudante</span>
          </div>

          <!-- level bar -->
          <div class="mt-6">
            <div class="flex items-center justify-between text-sm text-gray-300 mb-2">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L14.09 8.26L20.97 9.27L15.97 13.14L17.54 20L12 16.77L6.46 20L8.03 13.14L3.03 9.27L9.91 8.26L12 2Z"/></svg>
                <span class="font-medium">Nível 6</span>
              </div>
              <div class="text-xs text-gray-400">2450 / 3000 XP</div>
            </div>

            <div class="w-full bg-[#081016] rounded-full h-3 overflow-hidden border border-gray-800">
              <div
                class="h-3 bg-gradient-to-r from-green-500 to-sky-500"
                :style="{ width: user.xpPercent + '%' }"
              ></div>
            </div>
            <div class="mt-2 text-xs text-gray-400">Mestre do Dinheiro</div>
          </div>

          <!-- small tiles -->
          <div class="mt-6 grid grid-cols-2 gap-3">
            <div class="bg-[#0B1220] border border-gray-800 rounded-lg p-3 text-center">
              <div class="text-sm text-gray-400">Sequência</div>
              <div class="mt-2 font-bold text-lg text-green-400">12 <span class="text-xs text-gray-400">dias</span></div>
            </div>
            <div class="bg-[#0B1220] border border-gray-800 rounded-lg p-3 text-center">
              <div class="text-sm text-gray-400">Metas</div>
              <div class="mt-2 font-bold text-lg text-green-400">8 <span class="text-xs text-gray-400">completas</span></div>
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

      <!-- Right column: progresso e metas -->
      <div class="lg:col-span-8">
        <div class="bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">
          <h3 class="text-xl font-bold mb-4">Progresso e Metas</h3>

          <div class="space-y-4">
            <div v-for="(g, idx) in goals" :key="idx" class="p-4 bg-[#081018] border border-gray-800 rounded-lg">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <h4 class="font-semibold">{{ g.name }}</h4>
                  <div class="text-xs text-gray-400 mt-1">R$ {{ formatNumber(g.saved) }} de R$ {{ formatNumber(g.target) }}</div>
                </div>
                <div class="text-sm text-green-400 font-semibold rounded-md bg-[#062017] px-3 py-1">{{ g.progress }}%</div>
              </div>

              <div class="w-full bg-[#061217] h-3 rounded-full overflow-hidden border border-gray-800">
                <div class="h-3 bg-green-500" :style="{ width: g.progress + '%' }"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Conquistas section -->
        <div class="mt-6 bg-[#0E1620] border border-gray-800 rounded-2xl p-6 shadow-md">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold">Conquistas</h3>
            <a href="#" class="text-sm text-sky-400">Ver todas</a>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="(ach, i) in achievements" :key="i" class="p-4 rounded-lg border border-gray-800 bg-[#081018] flex items-start gap-3">
              <div class="w-12 h-12 rounded-lg bg-amber-600/20 flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21 16.54 13.97 22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
              </div>
              <div class="flex-1">
                <div class="flex justify-between items-start">
                  <div>
                    <div class="font-semibold">{{ ach.title }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ ach.subtitle }}</div>
                  </div>
                  <div class="text-xs text-amber-300 mt-1">{{ ach.unlocked ? 'Completa' : 'Bloqueada' }}</div>
                </div>
                <div class="text-xs text-gray-500 mt-2">{{ ach.note }}</div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal / drawer de edição (simples inline) -->
    <div v-if="editing" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="w-full max-w-2xl bg-[#071018] border border-gray-800 rounded-2xl p-6">
        <div class="flex justify-between items-center mb-4">
          <h4 class="text-lg font-bold">Editar Perfil</h4>
          <button @click="editing = false" class="text-gray-400 hover:text-white">Fechar</button>
        </div>

        <form @submit.prevent="saveProfile" class="space-y-4">
          <div>
            <label class="text-sm text-gray-300 block mb-1">Nome</label>
            <input v-model="form.name" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100" />
          </div>
          <div>
            <label class="text-sm text-gray-300 block mb-1">Email</label>
            <input v-model="form.email" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100" />
          </div>
          <div>
            <label class="text-sm text-gray-300 block mb-1">Nova senha</label>
            <input type="password" v-model="form.password" placeholder="Deixe em branco para não alterar" class="w-full bg-[#0B1216] border border-gray-800 rounded-md p-2 text-gray-100" />
          </div>

          <div class="flex gap-3 justify-end">
            <button type="button" @click="editing = false" class="px-4 py-2 rounded-md border border-gray-700 text-gray-300">Cancelar</button>
            <button type="submit" class="px-4 py-2 rounded-md bg-green-500 hover:bg-green-600 text-black font-semibold">Salvar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

/**
 * Substitua os mocks abaixo pelo fetch real da sua API:
 * ex: const res = await api.get('/user'); user.value = res.data
 */

const user = ref({
  name: 'Higor Miguel',
  email: 'higormiguel2015@outlook.com',
  avatar: 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png',
  xpPercent: 82
})

const goals = ref([
  { name: 'Viagem de Férias', saved: 3400, target: 5000, progress: 68 },
  { name: 'Nova TV', saved: 825, target: 2500, progress: 33 },
  { name: 'Reserva de Emergência', saved: 8500, target: 10000, progress: 85 }
])

const achievements = ref([
  { title: 'Primeira Meta', subtitle: 'Complete sua primeira meta financeira', unlocked: true, note: 'Desbloqueado em 15 Jan 2025' },
  { title: 'Economizador', subtitle: 'Economize R$ 10.000', unlocked: true, note: 'Desbloqueado em 22 Jan 2025' },
  { title: 'Sequência de Fogo', subtitle: 'Mantenha uma sequência de 7 dias', unlocked: true, note: 'Desbloqueado em 28 Jan 2025' },
  { title: 'Mestre do Orçamento', subtitle: 'Fique dentro do orçamento por 3 meses', unlocked: false, note: 'Bloqueado' }
])

const editing = ref(false)
const form = ref({ name: user.value.name, email: user.value.email, password: '' })

const saveProfile = () => {
  // Aqui você iria chamar a API para salvar (PUT /user)
  user.value.name = form.value.name
  user.value.email = form.value.email
  // não armazene a senha em mock — apenas envie para API
  form.value.password = ''
  editing.value = false
  alert('Perfil atualizado com sucesso!')
}

const formatNumber = (n) => {
  if (!n && n !== 0) return '0'
  return n.toLocaleString('pt-BR')
}
</script>

<style scoped>
/* pequenos ajustes visuais */
.border-gray-800 { border-color: rgba(90, 100, 110, 0.25) }
</style>
