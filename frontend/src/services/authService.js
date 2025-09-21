import api from './api'

export default {
  async register(payload) {
    const { data } = await api.post('/register', payload)
    return data
  },

  async login(credentials) {
    const { data } = await api.post('/login', credentials)
    if (data.token) {
      localStorage.setItem('auth_token', data.token)
    }
    return data
  },

  async getUser() {
    const { data } = await api.get('/user')
    return data
  },

  async logout() {
    localStorage.removeItem('auth_token')
  },
}
