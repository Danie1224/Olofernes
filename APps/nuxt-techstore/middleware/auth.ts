// middleware/auth.ts
// Protects routes that require user authentication

export default defineNuxtRouteMiddleware((to, from) => {
  const { isAuthenticated } = useAuth()

  if (!isAuthenticated.value) {
    return navigateTo('/auth/login')
  }
})
