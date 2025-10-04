<template>
  <div class="flex flex-col gap-6">
    <h1 class="text-2xl font-semibold mb-4">Receitas</h1>

    <!-- Meta mensal -->
    <CardConquista
      titulo="Meta de Receita Mensal"
      descricao="R$ 6.450 / R$ 8.000"
      icone="🚀"
      status="Em progresso"
    />

    <!-- Mini-cards de conquistas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
      <CardConquista
        titulo="Streak de Receitas"
        descricao="15 dias consecutivos"
        icone="🔥"
        status="Desbloqueado"
      />
      <CardConquista
        titulo="Diversificação de Renda"
        descricao="3 fontes diferentes"
        icone="⭐"
        status="Em progresso"
      />
      <CardConquista
        titulo="Recorde Pessoal"
        descricao="Maior receita: R$ 9.200"
        icone="🏆"
        status="Bloqueado"
      />
    </div>

    <!-- Resumo de receitas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
      <CardResumo
        titulo="Total de Receitas"
        valor="R$ 6.450,00"
        subtitulo=""
        cor="text-green-500"
        icone="💰"
      />
      <CardResumo
        titulo="Maior Receita"
        valor="R$ 5.000,00"
        subtitulo="Salário"
        cor="text-green-500"
        icone="💼"
      />
      <CardResumo titulo="Fontes Ativas" valor="3" subtitulo="" cor="text-white" icone="⭐" />
      <CardResumo
        titulo="Crescimento vs Mês Anterior"
        valor="+18%"
        subtitulo=""
        cor="text-green-500"
        icone="📈"
      />
    </div>
  </div>
</template>

<script setup>
import CardResumo from '@/components/dashboard/CardResumo.vue'
import CardConquista from '@/components/dashboard/CardConquista.vue'
</script>
