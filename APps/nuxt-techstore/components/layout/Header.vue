<template>
  <header class="bg-white shadow-sm">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <NuxtLink to="/" class="text-xl font-bold text-primary-600">
          TechStore
        </NuxtLink>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center space-x-6">
          <NuxtLink to="/products" class="text-gray-600 hover:text-primary-600">
            Products
          </NuxtLink>
          <template v-if="isAuthenticated">
            <NuxtLink to="/cart" class="text-gray-600 hover:text-primary-600 relative">
              Cart
              <span v-if="cartCount > 0" class="ml-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                {{ cartCount }}
              </span>
            </NuxtLink>
            <NuxtLink to="/orders" class="text-gray-600 hover:text-primary-600">
              My Orders
            </NuxtLink>
            <NuxtLink to="/returns" class="text-gray-600 hover:text-primary-600">
              Returns
            </NuxtLink>
            <NuxtLink to="/vouchers" class="text-gray-600 hover:text-primary-600">
              Vouchers
            </NuxtLink>
          </template>
        </nav>

        <!-- Right side -->
        <div class="flex items-center space-x-4">
          <template v-if="isAuthenticated">
            <!-- Welcome message -->
            <span class="text-gray-700 font-medium">
              Welcome, <span class="text-primary-600">{{ user?.name }}</span>!
            </span>
            
            <!-- User menu -->
            <div class="relative">
              <button @click="showUserMenu = !showUserMenu" class="flex items-center text-gray-600 hover:text-primary-600">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                  Logout
                </button>
              </div>
            </div>
          </template>
          <template v-else>
            <NuxtLink to="/auth/login" class="text-gray-600 hover:text-primary-600">
              Login
            </NuxtLink>
            <NuxtLink to="/auth/register" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700">
              Register
            </NuxtLink>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const { user, isAuthenticated, logout } = useAuth()
const { cartCount } = useCart()

const showUserMenu = ref(false)

const handleLogout = async () => {
  await logout()
  showUserMenu.value = false
}

// Close menu when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!(e.target as HTMLElement).closest('.relative')) {
      showUserMenu.value = false
    }
  })
})
</script>
