<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Orders</h1>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
      <div class="flex border-b overflow-x-auto">
        <button
          @click="activeFilter = 'all'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'all' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          All Orders
        </button>
        <button
          @click="activeFilter = 'pending'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'pending' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Pending
        </button>
        <button
          @click="activeFilter = 'processing'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'processing' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Processing
        </button>
        <button
          @click="activeFilter = 'shipped'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'shipped' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Shipped
        </button>
        <button
          @click="activeFilter = 'completed'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'completed' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Completed
        </button>
        <button
          @click="activeFilter = 'cancelled'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors whitespace-nowrap',
            activeFilter === 'cancelled' 
              ? 'text-white bg-blue-600' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Cancelled
        </button>
      </div>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order ID</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Payment</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="order in filteredOrders" :key="order.order_id">
            <td class="px-6 py-4">#{{ order.order_id }}</td>
            <td class="px-6 py-4">{{ order.customer_id }}</td>
            <td class="px-6 py-4">₱{{ formatPrice(order.total_price || 0) }}</td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-600 font-medium">Paid</span>
              </div>
              <div class="text-gray-500 text-sm">{{ order.payment_method }}</div>
            </td>
            <td class="px-6 py-4">
              <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded-full text-xs">
                {{ order.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-500">{{ formatDate(order.created_at) }}</td>
            <td class="px-6 py-4">
              <NuxtLink 
                :to="`/admin/orders/${order.order_id}`"
                class="text-primary-600 hover:underline"
              >
                View
              </NuxtLink>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { orders, loading, fetchOrders } = useOrders()
const activeFilter = ref('all')

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-PH', {
    year: 'numeric',
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

// Filter orders based on active filter
const filteredOrders = computed(() => {
  if (activeFilter.value === 'all') {
    return orders.value
  }
  return orders.value.filter((order: any) => {
    return order.status?.toLowerCase() === activeFilter.value
  })
})

onMounted(async () => {
  await fetchOrders()
})
</script>
