<template>
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
      <NuxtLink to="/products" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
        Continue Shopping
      </NuxtLink>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-5 rounded-xl shadow-lg text-center">
        <div class="text-3xl font-bold">{{ stats.total }}</div>
        <div class="text-sm opacity-90 mt-1">Total Orders</div>
      </div>
      <div class="bg-gradient-to-br from-amber-400 to-orange-500 text-white p-5 rounded-xl shadow-lg text-center">
        <div class="text-3xl font-bold">{{ stats.pending }}</div>
        <div class="text-sm opacity-90 mt-1">Pending</div>
      </div>
      <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-5 rounded-xl shadow-lg text-center">
        <div class="text-3xl font-bold">{{ stats.processing }}</div>
        <div class="text-sm opacity-90 mt-1">Processing</div>
      </div>
      <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-5 rounded-xl shadow-lg text-center">
        <div class="text-3xl font-bold">{{ stats.completed }}</div>
        <div class="text-sm opacity-90 mt-1">Completed</div>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
      <div class="flex flex-wrap gap-3">
        <select v-model="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg bg-white">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="processing">Processing</option>
          <option value="shipped">Shipped</option>
          <option value="delivered">Delivered</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="filteredOrders.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
      </svg>
      <p class="text-gray-600 mb-4">You haven't placed any orders yet</p>
      <NuxtLink to="/products" class="text-indigo-600 hover:underline font-medium">
        Start Shopping →
      </NuxtLink>
    </div>

    <!-- Orders List -->
    <div v-else class="space-y-4">
      <div 
        v-for="order in filteredOrders" 
        :key="order.order_id"
        class="bg-white rounded-xl shadow-md p-6 border border-gray-100 hover:border-indigo-500 hover:shadow-lg transition-all"
      >
        <!-- Order Header -->
        <div class="flex flex-wrap justify-between items-start gap-4 pb-4 mb-4 border-b border-gray-100">
          <div>
            <h3 class="font-semibold text-lg text-gray-900">Order #{{ order.order_id }}</h3>
            <p class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
          </div>
          <span :class="getStatusClass(order.status)" class="px-4 py-1.5 rounded-full text-sm font-semibold">
            {{ order.status }}
          </span>
        </div>

        <!-- Completion Notification -->
        <div v-if="order.status === 'completed' && order.completed_at" class="flex gap-3 p-3 bg-green-50 border-l-4 border-green-500 rounded mb-4">
          <div class="w-6 h-6 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
          <div class="text-sm text-green-700">
            <strong class="block">Order Completed</strong>
            <p class="text-green-600">Completed on {{ formatDate(order.completed_at) }}</p>
          </div>
        </div>

        <!-- Order Items -->
        <div v-if="order.order_items && order.order_items.length > 0" class="mb-4">
          <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
              <tr>
                <th class="text-left py-2 px-3 text-gray-600 font-semibold uppercase text-xs">Product</th>
                <th class="text-center py-2 px-3 text-gray-600 font-semibold uppercase text-xs w-20">Qty</th>
                <th class="text-right py-2 px-3 text-gray-600 font-semibold uppercase text-xs w-24">Price</th>
                <th class="text-right py-2 px-3 text-gray-600 font-semibold uppercase text-xs w-24">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in order.order_items.slice(0, 3)" :key="item.order_item_id" class="border-b border-gray-50">
                <td class="py-2 px-3 font-medium text-gray-900">{{ item.product?.name || 'Product' }}</td>
                <td class="py-2 px-3 text-center text-gray-600">{{ item.quantity }}</td>
                <td class="py-2 px-3 text-right text-gray-600">${{ formatPrice(item.unit_price || 0) }}</td>
                <td class="py-2 px-3 text-right font-semibold text-green-600">${{ formatPrice((item.unit_price || 0) * item.quantity) }}</td>
              </tr>
              <tr v-if="order.order_items.length > 3">
                <td colspan="4" class="py-2 px-3 text-gray-500 text-sm">
                  +{{ order.order_items.length - 3 }} more item(s)
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Order Footer -->
        <div class="flex flex-wrap justify-between items-center pt-4 border-t border-gray-100">
          <div>
            <span class="text-gray-500 text-sm block">Total</span>
            <span class="text-xl font-bold text-gray-900">${{ formatPrice(order.total_price || 0) }}</span>
          </div>
          <div class="flex gap-3">
            <NuxtLink 
              :to="`/orders/${order.order_id}`"
              class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg"
            >
              View Details
            </NuxtLink>
            <NuxtLink 
              v-if="order.status === 'delivered' || order.status === 'completed'"
              :to="`/returns/create?order_id=${order.order_id}`"
              class="px-5 py-2.5 bg-amber-100 text-amber-800 rounded-lg font-medium hover:bg-amber-200 transition-colors"
            >
              Request Return
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { orders, loading, fetchOrders } = useOrders()
const statusFilter = ref('')

const stats = computed(() => {
  return {
    total: orders.value.length,
    pending: orders.value.filter(o => o.status === 'pending').length,
    processing: orders.value.filter(o => o.status === 'processing' || o.status === 'shipped').length,
    completed: orders.value.filter(o => o.status === 'delivered' || o.status === 'completed').length,
  }
})

const filteredOrders = computed(() => {
  if (!statusFilter.value) return orders.value
  return orders.value.filter(o => o.status === statusFilter.value)
})

const formatPrice = (price: number) => {
  return price.toLocaleString('en-US', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'pending': 'bg-amber-100 text-amber-800',
    'processing': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-sky-100 text-sky-800',
    'delivered': 'bg-green-100 text-green-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

onMounted(async () => {
  // Force refresh to get latest orders
  await fetchOrders({}, true)
})
</script>
