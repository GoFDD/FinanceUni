import api from "./api";

/**
 * Service para gerenciar dados do dashboard e gamificação
 */
export default {
  /**
   * Dashboard principal (saldo, metas, conquistas, XP total, streak, etc.)
   */
  async getDashboardData() {
    try {
      const response = await api.get("/dashboard");
      return response.data;
    } catch (error) {
      console.error("Erro ao carregar dashboard:", error);
      throw error.response?.data || { message: "Erro ao carregar dashboard" };
    }
  },

  /**
   * Criar meta pessoal do usuário
   */
  async createUserGoal(payload) {
    try {
      const response = await api.post("/goals", payload);
      return response.data;
    } catch (error) {
      console.error("Erro ao criar meta:", error);
      throw error.response?.data || { message: "Erro ao criar meta" };
    }
  },

  /**
   * Completa uma meta específica
   */
  async completeGoal(goalId) {
    try {
      const response = await api.post(`/gamification/goals/${goalId}/complete`);
      return response.data;
    } catch (error) {
      console.error("Erro ao completar meta:", error);
      throw error.response?.data || { message: "Erro ao completar meta" };
    }
  },

  /**
   * Lista todas conquistas do usuário
   */
  async getAllAchievements() {
    try {
      const response = await api.get("/gamification/achievements");
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar conquistas:", error);
      throw error.response?.data || { message: "Erro ao buscar conquistas" };
    }
  },

  /**
   * Dashboard de gamificação completo
   */
  async getGamificationDashboard() {
    try {
      const response = await api.get("/gamification/dashboard");
      return response.data;
    } catch (error) {
      console.error("Erro ao buscar dados de gamificação:", error);
      throw error.response?.data || { message: "Erro ao buscar gamificação" };
    }
  },

  /**
   *  Desbloquear/Coletar Conquista
   */
  async unlockAchievement(id) {
    try {
      const response = await api.post(`/gamification/achievements/${id}/unlock`);
      return response.data;
    } catch (error) {
      console.error("Erro ao coletar conquista:", error);
      throw error.response?.data || { message: "Erro ao coletar conquista" };
    }
  },

  /**
   * (Opcional) Listar metas pessoais do usuário
   */
  async getUserGoals() {
    try {
      const response = await api.get("/gamification/user-goals");
      return response.data;
    } catch (error) {
      console.error("Erro ao listar metas pessoais:", error);
      throw error.response?.data || { message: "Erro ao listar metas pessoais" };
    }
  }
};
