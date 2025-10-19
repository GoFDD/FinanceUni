<template>
  <div class="flex flex-col gap-6">
    <h1 class="text-2xl font-semibold mb-4">Bancos</h1>

    <!-- Mini-cards de conquistas bancárias -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <CardConquista
        titulo="Colecionador de Contas"
        descricao="4/10 bancos cadastrados"
        icone="🥇"
        status="Desbloqueado"
      />
      <CardConquista
        titulo="Diversificação"
        descricao="3 tipos de conta diferentes"
        icone="⭐"
        status="Em progresso"
      />
      <CardConquista
        titulo="Saldo Saudável"
        descricao="Todas contas positivas"
        icone="✅"
        status="Bloqueado"
      />
    </div>

    <!-- Grid de cards de bancos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
      <CardResumo
        titulo="Nubank"
        valor="R$ 12.450,00"
        subtitulo="Conta Corrente"
        cor="text-green-500"
        icone="🏦"
      />
      <CardResumo
        titulo="Banco Inter"
        valor="R$ 5.200,00"
        subtitulo="Poupança"
        cor="text-green-500"
        icone="🏦"
      />
      <CardResumo
        titulo="XP Investimentos"
        valor="R$ 18.300,00"
        subtitulo="Investimento"
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
