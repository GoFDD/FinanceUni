import api from './api'

export async function register(data) {
  const { data: result } = await api.post('/register', data)
  return result
}

export async function login(data) {
  const { data: result } = await api.post('/login', data)
  return result
}

export async function logout() {
  const { data: result } = await api.post('/logout')
  return result
}
