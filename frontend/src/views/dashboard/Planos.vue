<template>
  <div class="min-h-screen bg-[#0B0F17] text-white">

    <!-- HERO -->
    <section class="px-6 md:px-12 lg:px-32 py-24 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
        Transforme sua Vida Financeira com o 
        <span class="text-emerald-400">FinanceUni</span>
      </h1>

      <p class="text-gray-400 mt-6 max-w-2xl mx-auto text-lg">
        Controle total das suas finanças, metas inteligentes, gamificação
        e relatórios poderosos — para usuários, empresas e universidades.
      </p>

      <button
        @click="scrollToPlans"
        class="mt-10 px-8 py-4 bg-emerald-500 hover:bg-emerald-600 transition rounded-xl font-bold text-black text-lg shadow-xl"
      >
        Ver Planos
      </button>
    </section>

    <!-- BENEFÍCIOS -->
    <section class="px-6 md:px-12 lg:px-32 py-20 bg-[#0E131F] border-t border-[#1a2235]">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-14">
        Por que escolher o FinanceUni?
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-[#0A0F18] border border-[#1e2638] p-8 rounded-2xl hover:border-emerald-500/40 transition">
          <div class="text-emerald-400 text-4xl mb-4">🎯</div>
          <h3 class="font-semibold text-xl mb-2">Metas Inteligentes</h3>
          <p class="text-gray-400 text-sm">
            Acompanhe sua evolução com metas automáticas e personalizadas.
          </p>
        </div>

        <div class="bg-[#0A0F18] border border-[#1e2638] p-8 rounded-2xl hover:border-emerald-500/40 transition">
          <div class="text-emerald-400 text-4xl mb-4">🏆</div>
          <h3 class="font-semibold text-xl mb-2">Gamificação Completa</h3>
          <p class="text-gray-400 text-sm">
            Ganhe XP, conquistas e transforme suas finanças em um jogo.
          </p>
        </div>

        <div class="bg-[#0A0F18] border border-[#1e2638] p-8 rounded-2xl hover:border-emerald-500/40 transition">
          <div class="text-emerald-400 text-4xl mb-4">📊</div>
          <h3 class="font-semibold text-xl mb-2">Relatórios Avançados</h3>
          <p class="text-gray-400 text-sm">
            Visualize gráficos, tendências e insights completos.
          </p>
        </div>
      </div>
    </section>

    <!-- PLANOS -->
    <section ref="planosSection" class="px-6 md:px-12 lg:px-32 py-24">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-14">
        Escolha o plano ideal para você
      </h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

        <!-- INDIVIDUAL -->
        <div class="bg-[#0E1620] border border-[#1d2433] rounded-2xl p-8 shadow-lg hover:border-emerald-500/40 transition">
          <h3 class="text-xl font-bold mb-2">Plano Individual</h3>
          <p class="text-gray-400">Para quem deseja controle total das finanças pessoais.</p>

          <div class="mt-6 text-4xl font-extrabold">
            <span class="text-emerald-400">R$ 39,90</span>
            <span class="text-lg text-gray-400">/ mês</span>
          </div>

            <ul class="space-y-3 text-gray-300 mb-8">
            <li>✔ Metas ilimitadas</li>
            <li>✔ Dashboard completo</li>
            <li>✔ Relatórios detalhados</li>
            <li>✔ Conquistas + gamificação</li>
            <li>✔ Exportação de dados</li>
            <li>✔ Conexão com até 2 bancos</li>
            </ul>

          <button
            class="mt-8 w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-black font-bold rounded-xl transition"
          >
            Assinar Agora
          </button>
        </div>

        <!-- EMPRESAS -->
        <div class="bg-[#101b2c] border border-emerald-500 rounded-2xl p-8 shadow-xl scale-105">
          <div class="text-emerald-400 text-xs font-bold uppercase tracking-wider mb-2">
            Mais Popular
          </div>

          <h3 class="text-xl font-bold mb-2">Plano Empresas</h3>
          <p class="text-gray-400">Para micro e médias empresas com foco em crescimento.</p>

          <div class="mt-6 text-4xl font-extrabold">
            <span class="text-emerald-400">R$ 150</span>
            <span class="text-lg text-gray-400">/ mês</span>
          </div>

            <ul class="space-y-3 text-gray-300 mb-8">
            <li>✔ Tudo do plano Individual</li>
            <li>✔ Múltiplos usuários (até 5)</li>
            <li>✔ Painel administrativo avançado</li>
            <li>✔ Relatórios corporativos completos</li>
            <li>✔ Dashboard por setor/área</li>
            <li>✔ Conexão com até 8 bancos</li>
            <li>✔ Suporte prioritário</li>
            </ul>

          <button
            class="mt-8 w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-black font-bold rounded-xl transition"
          >
            Assinar Plano Empresas
          </button>
        </div>

        <!-- ENTERPRISE -->
        <div class="bg-[#0E1620] border border-[#1d2433] rounded-2xl p-8 shadow-lg">
          <h3 class="text-xl font-bold mb-2">Plano Enterprise</h3>
          <p class="text-gray-400">Para universidades e grandes empresas.</p>

          <div class="mt-6 text-4xl font-extrabold text-emerald-400">
            Sob consulta
          </div>

            <ul class="space-y-3 text-gray-300 mb-8">
            <li>✔ Tudo do plano Empresas</li>
            <li>✔ Usuários ilimitados</li>
            <li>✔ Ranking gamificado para equipes</li>
            <li>✔ Módulo completo de Educação Financeira</li>
            <li>✔ API corporativa completa</li>
            <li>✔ Integrações personalizadas</li>
            <li>✔ Suporte dedicado com SLA</li>
            <li>✔ Onboarding assistido para equipes</li>
            </ul>

          <button
            class="mt-8 w-full py-3 bg-emerald-700 hover:bg-emerald-600 text-white font-bold rounded-xl transition"
          >
            Falar com Comercial
          </button>
        </div>

      </div>
    </section>

    <!-- DEPOIMENTOS -->
    <section class="px-6 md:px-12 lg:px-32 py-24 bg-[#0E131F] border-t border-[#1a2235]">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-14">Resultados Reais</h2>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="bg-[#0A0F18] border border-[#1e2638] p-6 rounded-2xl">
          <p class="text-gray-300 italic">
            “Organizei minhas finanças pela primeira vez na vida!”
          </p>
          <div class="mt-4 text-emerald-400 font-bold">— Fernanda, Usuária</div>
        </div>

        <div class="bg-[#0A0F18] border border-[#1e2638] p-6 rounded-2xl">
          <p class="text-gray-300 italic">
            “Controle financeiro completo da minha empresa em um único painel.”
          </p>
          <div class="mt-4 text-emerald-400 font-bold">— Ricardo, Empresário</div>
        </div>

        <div class="bg-[#0A0F18] border border-[#1e2638] p-6 rounded-2xl">
          <p class="text-gray-300 italic">
            “Os alunos amaram o sistema gamificado!”
          </p>
          <div class="mt-4 text-emerald-400 font-bold">— Prof. Camila</div>
        </div>

      </div>
    </section>

    <!-- CTA FINAL -->
    <section class="px-6 md:px-12 lg:px-32 py-24 text-center">
      <h2 class="text-3xl md:text-4xl font-bold mb-6">
        Pronto para evoluir sua vida financeira?
      </h2>

      <p class="text-gray-400 max-w-2xl mx-auto text-lg mb-10">
        Escolha seu plano e libere todo o potencial do FinanceUni.
      </p>

      <button
        @click="scrollToPlans"
        class="px-10 py-4 bg-emerald-500 hover:bg-emerald-600 text-black rounded-xl text-lg font-bold shadow-xl"
      >
        Escolher um Plano
      </button>
    </section>

  </div>
</template>

<script setup>
import { ref } from "vue";

const planosSection = ref(null);

function scrollToPlans() {
  planosSection.value?.scrollIntoView({ behavior: "smooth" });
}
</script>
