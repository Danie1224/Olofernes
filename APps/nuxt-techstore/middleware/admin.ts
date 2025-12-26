// middleware/admin.ts
// Protects routes that require admin authentication

export default defineNuxtRouteMiddleware((to, from) => {
  const { isAdminAuthenticated } = useAuth()

  if (!isAdminAuthenticated.value) {
    return navigateTo('/admin/login')
  }
})
