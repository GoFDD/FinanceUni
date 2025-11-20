import api from '@/services/api';

export default {
  getAll(params = {}) {
    return api.get('/transactions', { params });
  },

  getById(id) {
    return api.get(`/transactions/${id}`);
  },

  create(data) {
    return api.post('/transactions', data);
  },

  update(id, data) {
    return api.put(`/transactions/${id}`, data);
  },

  delete(id) {
    return api.delete(`/transactions/${id}`);
  }
}
