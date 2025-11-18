// src/stores/auth.js
import { defineStore } from 'pinia'
import { http } from '../api/http'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null,
    user: localStorage.getItem('user')
      ? JSON.parse(localStorage.getItem('user')!)
      : null,
  }),

  actions: {
    async login(email: string, password: string) {
      const { data } = await http.post('/api/auth/login', {
        email,
        password,
      })

      this.token = data.access_token
      this.user = data.user

      localStorage.setItem('token', this.token!)
      localStorage.setItem('user', JSON.stringify(this.user))
    },

    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      // Se você tiver rota de logout na API, pode chamar aqui também
      // await api.post('/api/auth/logout')
    },
  },
})
