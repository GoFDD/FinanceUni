import api from "./api";

export default {
  // ======================================================
  // 🟢 RECEITAS (INCOME)
  // ======================================================
  async getIncomes() {
    const r = await api.get("/transactions", {
      params: { type: "income" },
    });
    return r.data;
  },

  async createIncome(payload) {
    const data = { ...payload, type: "income" };
    const r = await api.post("/transactions", data);
    return r.data;
  },

  async updateIncome(id, payload) {
    const data = { ...payload, type: "income" };
    const r = await api.put(`/transactions/${id}`, data);
    return r.data;
  },

  async deleteIncome(id) {
    const r = await api.delete(`/transactions/${id}`);
    return r.data;
  },

  // ======================================================
  // 🔴 DESPESAS (EXPENSE)
  // ======================================================
  async getExpenses() {
    const r = await api.get("/transactions", {
      params: { type: "expense" },
    });
    return r.data;
  },

  async createExpense(payload) {
    const data = { ...payload, type: "expense" };
    const r = await api.post("/transactions", data);
    return r.data;
  },

  async updateExpense(id, payload) {
    const data = { ...payload, type: "expense" };
    const r = await api.put(`/transactions/${id}`, data);
    return r.data;
  },

  async deleteExpense(id) {
    const r = await api.delete(`/transactions/${id}`);
    return r.data;
  },

  async getExpenseSummary() {
    const r = await api.get("/transactions/expense-summary");
    return r.data;
  },

  // ======================================================
  // 🟣 CATEGORIAS
  // ======================================================
  async getCategories(type) {
    const r = await api.get("/categories", {
      params: { type },
    });
    return r.data;
  },

  async createCategory(payload) {
    const r = await api.post("/categories", payload);
    return r.data;
  },

  async updateCategory(id, payload) {
    const r = await api.put(`/categories/${id}`, payload);
    return r.data;
  },

  async deleteCategory(id) {
    const r = await api.delete(`/categories/${id}`);
    return r.data;
  },
};
