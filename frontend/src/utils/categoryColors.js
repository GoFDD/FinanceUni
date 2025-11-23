export const categoryColors = {
  // Despesas comuns
  'Alimentação': '#facc15', // amarelo
  'Transporte': '#3b82f6', // azul
  'Saúde': '#22c55e', // verde
  'Lazer': '#a855f7', // roxo
  'Casa': '#ef4444', // vermelho

  // Receitas
  'Salário': '#14b8a6',
  'Investimentos': '#8b5cf6',

  'default': '#64748b',
}

export function getCategoryColor(name) {
  return categoryColors[name] || categoryColors['default']
}
