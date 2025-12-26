// composables/useAuth.ts
// Authentication composable for handling user/admin auth state

interface User {
  id: number
  name: string
  email: string
}

interface LoginCredentials {
  email: string
  password: string
}

interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export const useAuth = () => {
  const config = useRuntimeConfig()
  const token = useCookie<string | null>('auth_token', { default: () => null })
  const adminToken = useCookie<string | null>('admin_token', { default: () => null })
  
  // Persist user data in localStorage
  const getUserFromStorage = () => {
    if (process.client) {
      const stored = localStorage.getItem('user_data')
      return stored ? JSON.parse(stored) : null
    }
    return null
  }
  
  const getIsAdminFromStorage = () => {
    if (process.client) {
      const stored = localStorage.getItem('is_admin')
      return stored === 'true'
    }
    return false
  }
  
  const user = useState<User | null>('user', () => getUserFromStorage())
  const isAdmin = useState<boolean>('isAdmin', () => getIsAdminFromStorage())

  const isAuthenticated = computed(() => !!token.value || !!adminToken.value)
  const isAdminAuthenticated = computed(() => !!adminToken.value)

  // User login
  const login = async (credentials: LoginCredentials) => {
    try {
      const response = await $fetch<{ token: string; user: User }>(
        `${config.public.apiBase}/login`,
        {
          method: 'POST',
          body: credentials,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
        }
      )
      token.value = response.token
      user.value = response.user
      isAdmin.value = false
      
      // Persist to localStorage
      if (process.client) {
        localStorage.setItem('user_data', JSON.stringify(response.user))
        localStorage.setItem('is_admin', 'false')
      }
      
      return { success: true }
    } catch (error: any) {
      return { success: false, error: error.data?.message || 'Login failed' }
    }
  }

  // User register
  const register = async (data: RegisterData) => {
    try {
      const response = await $fetch<{ token: string; user: User }>(
        `${config.public.apiBase}/register`,
        {
          method: 'POST',
          body: data,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
        }
      )
      token.value = response.token
      user.value = response.user
      isAdmin.value = false
      
      // Persist to localStorage
      if (process.client) {
        localStorage.setItem('user_data', JSON.stringify(response.user))
        localStorage.setItem('is_admin', 'false')
      }
      
      return { success: true }
    } catch (error: any) {
      return { success: false, error: error.data?.message || error.data?.errors || 'Registration failed' }
    }
  }

  // Admin login
  const adminLogin = async (credentials: LoginCredentials) => {
    try {
      const response = await $fetch<{ token: string; admin: User }>(
        `${config.public.apiBase}/admin-login`,
        {
          method: 'POST',
          body: credentials,
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
        }
      )
      adminToken.value = response.token
      user.value = response.admin
      isAdmin.value = true
      
      // Persist to localStorage
      if (process.client) {
        localStorage.setItem('user_data', JSON.stringify(response.admin))
        localStorage.setItem('is_admin', 'true')
      }
      
      return { success: true }
    } catch (error: any) {
      return { success: false, error: error.data?.message || 'Admin login failed' }
    }
  }

  // Logout
  const logout = async () => {
    try {
      const currentToken = isAdmin.value ? adminToken.value : token.value
      if (currentToken) {
        await $fetch(`${config.public.apiBase}/logout`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${currentToken}`,
          },
        })
      }
    } catch (error) {
      // Ignore errors on logout
    } finally {
      token.value = null
      adminToken.value = null
      user.value = null
      isAdmin.value = false
      
      // Clear localStorage
      if (process.client) {
        localStorage.removeItem('user_data')
        localStorage.removeItem('is_admin')
      }
      
      navigateTo(isAdmin.value ? '/admin/login' : '/auth/login')
    }
  }

  // Get current user
  const fetchUser = async () => {
    const currentToken = adminToken.value || token.value
    if (!currentToken) return null

    try {
      const response = await $fetch<{ data: User }>(
        `${config.public.apiBase}/get-user`,
        {
          headers: {
            Authorization: `Bearer ${currentToken}`,
          },
        }
      )
      user.value = response.data
      return response.data
    } catch (error) {
      // Token invalid, clear it
      token.value = null
      adminToken.value = null
      user.value = null
      return null
    }
  }

  return {
    user,
    token,
    adminToken,
    isAuthenticated,
    isAdminAuthenticated,
    isAdmin,
    login,
    register,
    adminLogin,
    logout,
    fetchUser,
  }
}
