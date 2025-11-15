import api from './api'

/**
 * Service para gerenciar dados do dashboard
 */
export default {
  /**
   * Busca todos os dados do dashboard em uma única chamada
   * Retorna: user, streak, conquistas, metas, resumo
   */
  async getDashboardData() {
    try {
      const response = await api.get('/dashboard')
      return response.data
    } catch (error) {
      console.error('Erro ao carregar dashboard:', error)
      throw error.response?.data || { message: 'Erro ao carregar dashboard' }
    }
  },

  /**
   * Completa uma meta específica
   */
  async completeGoal(goalId) {
    try {
      const response = await api.post(`/gamification/goals/${goalId}/complete`)
      return response.data
    } catch (error) {
      console.error('Erro ao completar meta:', error)
      throw error.response?.data || { message: 'Erro ao completar meta' }
    }
  },

  /**
   * Busca todas as conquistas (detalhado)
   */
  async getAllAchievements() {
    try {
      const response = await api.get('/gamification/achievements')
      return response.data
    } catch (error) {
      console.error('Erro ao buscar conquistas:', error)
      throw error.response?.data || { message: 'Erro ao buscar conquistas' }
    }
  },

  /**
   * Busca dados detalhados de gamificação
   */
  async getGamificationDashboard() {
    try {
      const response = await api.get('/gamification/dashboard')
      return response.data
    } catch (error) {
      console.error('Erro ao buscar dados de gamificação:', error)
      throw error.response?.data || { message: 'Erro ao buscar gamificação' }
    }
  },
}