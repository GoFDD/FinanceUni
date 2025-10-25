<template>
  <div class="flex flex-col gap-6">
    <h1 class="text-2xl font-semibold mb-4">Despesas</h1>

    <!-- Desafios ativos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <CardConquista
        titulo="Economize R$ 500"
        descricao="R$ 320/R$ 500"
        icone="🏆"
        status="Em progresso"
      />
      <CardConquista
        titulo="Reduza Gastos com Alimentação"
        descricao="R$ 650/800"
        icone="⭐"
        status="Em progresso"
      />
    </div>

    <!-- Resumo mensal -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
      <CardResumo
        titulo="Total de Despesas"
        valor="R$ 3.240,00"
        subtitulo="Este mês"
        cor="text-red-500"
        icone="💸"
      />
      <CardResumo
        titulo="Maior Despesa"
        valor="R$ 850,00"
        subtitulo="Aluguel"
        cor="text-red-500"
        icone="🏠"
      />
      <CardResumo
        titulo="Categoria Dominante"
        valor="Alimentação (35%)"
        subtitulo=""
        cor="text-white"
        icone="🍔"
      />
      <CardResumo
        titulo="Economia vs Mês Anterior"
        valor="-12%"
        subtitulo=""
        cor="text-green-500"
        icone="📉"
      />
    </div>
  </div>
</template>

<script setup>
import CardResumo from '@/components/dashboard/CardResumo.vue'
import CardConquista from '@/components/dashboard/CardConquista.vue'
</script>
