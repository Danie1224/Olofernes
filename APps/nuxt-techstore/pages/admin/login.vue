<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Admin Login</h1>
        <p class="text-gray-600 mt-2">TechStore Admin Panel</p>
      </div>

      <div class="bg-white rounded-lg shadow-md p-8">
        <!-- Success Notification -->
        <Transition name="fade">
          <div 
            v-if="showSuccess" 
            class="mb-4 bg-green-500 text-white text-sm font-medium px-4 py-3 rounded-md shadow-lg flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Login successful! Redirecting...
          </div>
        </Transition>

        <form @submit.prevent="handleLogin">
          <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-md mb-4">
            {{ error }}
          </div>

          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email Address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
              placeholder="admin@example.com"
            />
          </div>

          <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
              placeholder="••••••••"
            />
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full bg-gray-800 text-white py-2 rounded-md hover:bg-gray-900 disabled:opacity-50"
          >
            {{ loading ? 'Signing in...' : 'Sign In' }}
          </button>
        </form>

        <div class="mt-6 text-center">
          <NuxtLink to="/auth/login" class="text-sm text-gray-500 hover:text-gray-700">
            ← Back to User Login
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: false
})

const { adminLogin } = useAuth()

const form = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const error = ref('')
const showSuccess = ref(false)

const handleLogin = async () => {
  loading.value = true
  error.value = ''

  const result = await adminLogin(form)
  
  if (result.success) {
    showSuccess.value = true
    setTimeout(() => {
      navigateTo('/admin')
    }, 1000)
  } else {
    error.value = result.error || 'Login failed'
  }
  
  loading.value = false
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
