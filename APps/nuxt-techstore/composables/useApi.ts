// composables/useApi.ts
// API fetch wrapper with authentication handling

interface FetchOptions {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  body?: any
  query?: Record<string, any>
}

export const useApi = () => {
  const config = useRuntimeConfig()
  const auth = useAuth()

  const getAuthHeader = (): Record<string, string> => {
    // Get route at call time to ensure reactivity
    const route = useRoute()
    // Use admin token for admin routes, otherwise use user token
    const isAdminRoute = route?.path?.startsWith('/admin') || false
    const currentToken = isAdminRoute ? (auth.adminToken.value || auth.token.value) : (auth.token.value || auth.adminToken.value)
    console.log('getAuthHeader - isAdminRoute:', isAdminRoute, 'token:', currentToken ? 'exists' : 'missing')
    return currentToken ? { Authorization: `Bearer ${currentToken}` } : {}
  }

  const apiFetch = async <T>(endpoint: string, options: FetchOptions = {}): Promise<T> => {
    const { method = 'GET', body, query } = options

    // Don't set Content-Type for FormData - let browser set it with boundary
    const isFormData = body instanceof FormData
    const headers: Record<string, string> = {
      'Accept': 'application/json',
      ...getAuthHeader(),
    }
    
    if (!isFormData) {
      headers['Content-Type'] = 'application/json'
    }

    console.log(`API ${method} ${endpoint}`, { headers: { ...headers, Authorization: headers.Authorization ? 'Bearer ***' : undefined }, body })
    
    try {
      const response = await $fetch<T>(`${config.public.apiBase}${endpoint}`, {
        method,
        body,
        query,
        headers,
      })
      console.log(`API ${method} ${endpoint} - Response:`, response)
      return response
    } catch (error: any) {
      console.error(`API ${method} ${endpoint} - Error:`, error?.data || error?.message || error)
      throw error
    }
  }

  // Convenience methods
  const get = <T>(endpoint: string, query?: Record<string, any>) => 
    apiFetch<T>(endpoint, { method: 'GET', query })

  const post = <T>(endpoint: string, body?: any) => 
    apiFetch<T>(endpoint, { method: 'POST', body })

  const put = <T>(endpoint: string, body?: any) => 
    apiFetch<T>(endpoint, { method: 'PUT', body })

  const patch = <T>(endpoint: string, body?: any) => 
    apiFetch<T>(endpoint, { method: 'PATCH', body })

  const del = <T>(endpoint: string) => 
    apiFetch<T>(endpoint, { method: 'DELETE' })

  return {
    apiFetch,
    get,
    post,
    put,
    patch,
    del,
  }
}
