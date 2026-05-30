import { useState, useCallback } from 'react'
import api from '../services/api'

export const useAuth = () => {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const login = useCallback(async (email, password) => {
    try {
      setLoading(true)
      const response = await api.post('/login', { email, password })
      localStorage.setItem('authToken', response.data.token)
      setUser(response.data.user)
      return response.data
    } catch (err) {
      setError(err.response?.data?.message || 'Login failed')
      throw err
    } finally {
      setLoading(false)
    }
  }, [])

  const logout = useCallback(() => {
    localStorage.removeItem('authToken')
    setUser(null)
  }, [])

  return { user, loading, error, login, logout }
}
