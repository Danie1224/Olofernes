<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Admin Sidebar -->
    <div class="flex">
      <aside class="w-64 min-h-screen bg-gray-800 text-white">
        <div class="p-4">
          <h1 class="text-xl font-bold">TechStore Admin</h1>
        </div>
        <nav class="mt-4">
          <NuxtLink 
            to="/admin" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path === '/admin' }"
          >
            Dashboard
          </NuxtLink>
          <NuxtLink 
            to="/admin/products" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path.startsWith('/admin/products') }"
          >
            Products
          </NuxtLink>
          <NuxtLink 
            to="/admin/customers" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path.startsWith('/admin/customers') }"
          >
            Customers
          </NuxtLink>
          <NuxtLink 
            to="/admin/orders" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path.startsWith('/admin/orders') }"
          >
            Orders
          </NuxtLink>
          <NuxtLink 
            to="/admin/returns" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path.startsWith('/admin/returns') }"
          >
            Returns
          </NuxtLink>
          <NuxtLink 
            to="/admin/vouchers" 
            class="block px-4 py-2 hover:bg-gray-700"
            :class="{ 'bg-gray-700': $route.path.startsWith('/admin/vouchers') }"
          >
            Vouchers
          </NuxtLink>
          <button 
            @click="logout" 
            class="block w-full text-left px-4 py-2 mt-8 hover:bg-red-600 bg-red-500"
          >
            Logout
          </button>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
const { logout } = useAuth()
</script>
