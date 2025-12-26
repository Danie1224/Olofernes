<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-600">Total Products</p>
              <p class="text-2xl font-bold">{{ stats.products }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-600">Total Orders</p>
              <p class="text-2xl font-bold">{{ stats.orders }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-600">Customers</p>
              <p class="text-2xl font-bold">{{ stats.customers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-full">
              <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm text-gray-600">Return Requests</p>
              <p class="text-2xl font-bold">{{ stats.returns }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
          <h2 class="text-lg font-semibold">Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="order in recentOrders" :key="order.order_id">
                <td class="px-6 py-4">
                  <NuxtLink :to="`/admin/orders/${order.order_id}`" class="text-primary-600 hover:underline">
                    #{{ order.order_id }}
                  </NuxtLink>
                </td>
                <td class="px-6 py-4">{{ order.customer_id }}</td>
                <td class="px-6 py-4">₱{{ formatPrice(order.total_price || 0) }}</td>
                <td class="px-6 py-4">
                  <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs">
                    {{ order.status }}
                  </span>
                </td>
                <td class="px-6 py-4 text-gray-500">{{ formatDate(order.created_at) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { get } = useApi()
const { orders, fetchOrders } = useOrders()

const loading = ref(true)
const stats = reactive({
  products: 0,
  orders: 0,
  customers: 0,
  returns: 0
})

const recentOrders = computed(() => orders.value.slice(0, 5))

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-PH', {
    month: 'short',
    day: 'numeric'
  })
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'processing': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

onMounted(async () => {
  try {
    // Fetch stats from various endpoints
    const [products, orders, customers, returns] = await Promise.all([
      get<{ data: any[] }>('/products'),
      get<{ data: any[] }>('/orders'),
      get<{ data: any[] }>('/customers'),
      get<{ data: any[] }>('/return-requests')
    ])
    
    stats.products = products.data.length
    stats.orders = orders.data.length
    stats.customers = customers.data.length
    stats.returns = returns.data.length

    await fetchOrders()
  } finally {
    loading.value = false
  }
})
</script>
