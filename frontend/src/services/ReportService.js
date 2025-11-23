import api from "./api";

export default {

  async getCategories() {
    const res = await api.get("/reports/categories");
    return res.data;
  },

  async getReport(params) {
    const res = await api.get("/reports", { params });
    return res.data;
  }
};
