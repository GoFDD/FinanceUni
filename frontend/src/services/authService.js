import api from './api'

const TOKEN_KEY = 'auth_token'
const USER_KEY = 'auth_user'

export default {
  /**
   * Login do usuário
   */
  async login(payload) {
    try {
      const res = await api.post('/login', payload)
      const { token, user } = res.data

      // token e usuário no localStorage
      localStorage.setItem(TOKEN_KEY, token)
      localStorage.setItem(USER_KEY, JSON.stringify(user))

      //  header Authorization padrão
      api.defaults.headers.common['Authorization'] = `Bearer ${token}`

      return user
    } catch (err) {
      throw err.response?.data || { message: 'Erro no login' }
    }
  },

  /**
   * Logout do usuário
   */
  async logout() {
    const token = localStorage.getItem(TOKEN_KEY)
    if (token) {
      try {
        await api.post('/logout')
      } catch (err) {
        console.warn('Erro ao deslogar:', err)
      }
    }
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    delete api.defaults.headers.common['Authorization']
  },

  /**
   * Retorna usuário logado ou null
   */
  async getUser() {
    const token = localStorage.getItem(TOKEN_KEY)
    const user = localStorage.getItem(USER_KEY)

    if (!token || !user) return null

    // header Authorization - garantir que está sempre setado
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`

    return JSON.parse(user)
  },

  /**
   * Retorna token atual
   */
  getToken() {
    return localStorage.getItem(TOKEN_KEY)
  },

  /**
   * Atualiza usuário local (ex: após editar perfil)
   */
  setUser(user) {
    localStorage.setItem(USER_KEY, JSON.stringify(user))
  },
}
