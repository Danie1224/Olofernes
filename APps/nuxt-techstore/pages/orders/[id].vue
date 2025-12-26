<template>
  <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Order Details</h1>
      <NuxtLink to="/orders" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        ← Back to Orders
      </NuxtLink>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Order Not Found -->
    <div v-else-if="!currentOrder" class="text-center py-12 bg-white rounded-xl shadow">
      <div class="text-5xl mb-4">🔍</div>
      <h2 class="text-xl font-bold text-gray-800 mb-2">Order Not Found</h2>
      <p class="text-gray-600 mb-4">The order you're looking for doesn't exist.</p>
      <NuxtLink to="/orders" class="text-indigo-600 hover:underline font-medium">
        Go Back to Orders →
      </NuxtLink>
    </div>

    <!-- Order Details -->
    <div v-else>
      <!-- Order Status Card -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <div class="flex flex-wrap justify-between items-start gap-4">
          <div>
            <h2 class="text-xl font-bold text-gray-900">Order #{{ currentOrder.order_id }}</h2>
            <p class="text-gray-500 mt-1">Placed on {{ formatDate(currentOrder.created_at) }}</p>
          </div>
          <span :class="getStatusClass(currentOrder.status)" class="px-4 py-2 rounded-full text-sm font-bold uppercase">
            {{ currentOrder.status }}
          </span>
        </div>

        <!-- Status Timeline -->
        <div class="mt-6 pt-6 border-t border-gray-100">
          <div class="flex items-center gap-2 md:gap-4 flex-wrap">
            <div class="flex items-center gap-2">
              <div class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm">✓</div>
              <span class="text-sm font-medium">Placed</span>
            </div>
            <div class="flex-1 hidden md:block h-1 bg-gray-200 rounded min-w-[20px]">
              <div 
                class="h-full bg-green-500 rounded transition-all"
                :style="{ width: ['processing', 'completed'].includes(currentOrder.status) ? '100%' : '0%' }"
              ></div>
            </div>
            <div class="flex items-center gap-2">
              <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-sm',
                ['processing', 'completed'].includes(currentOrder.status)
                  ? 'bg-green-500 text-white' 
                  : 'bg-gray-200 text-gray-400'
              ]">
                {{ ['processing', 'completed'].includes(currentOrder.status) ? '✓' : '2' }}
              </div>
              <span class="text-sm font-medium">Processing</span>
            </div>
            <div class="flex-1 hidden md:block h-1 bg-gray-200 rounded min-w-[20px]">
              <div 
                class="h-full bg-green-500 rounded transition-all"
                :style="{ width: currentOrder.status === 'completed' ? '100%' : '0%' }"
              ></div>
            </div>
            <div class="flex items-center gap-2">
              <div :class="[
                'w-8 h-8 rounded-full flex items-center justify-center text-sm',
                currentOrder.status === 'completed'
                  ? 'bg-green-500 text-white' 
                  : 'bg-gray-200 text-gray-400'
              ]">
                {{ currentOrder.status === 'completed' ? '✓' : '3' }}
              </div>
              <span class="text-sm font-medium">Completed</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment & Order Info -->
      <div class="grid md:grid-cols-2 gap-6 mb-6">
        <!-- Order Info -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
            📦 Order Information
          </h3>
          <div class="space-y-3">
            <div>
              <span class="text-gray-500 block text-sm">Order ID</span>
              <span class="font-semibold text-gray-900">#{{ currentOrder.order_id }}</span>
            </div>
            <div>
              <span class="text-gray-500 block text-sm">Order Date</span>
              <span class="font-semibold text-gray-900">{{ formatDate(currentOrder.order_date || currentOrder.created_at) }}</span>
            </div>
            <div>
              <span class="text-gray-500 block text-sm">Status</span>
              <span :class="getStatusClass(currentOrder.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ currentOrder.status }}
              </span>
            </div>
          </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
          <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
            💳 Payment Information
          </h3>
          <div class="space-y-3">
            <div>
              <span class="text-gray-500 block text-sm">Payment Method</span>
              <span class="font-semibold text-gray-900 uppercase">{{ currentOrder.payment_method || 'N/A' }}</span>
            </div>
            <div>
              <span class="text-gray-500 block text-sm">Payment Status</span>
              <span :class="[
                'px-3 py-1 rounded-full text-xs font-semibold',
                currentOrder.payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'
              ]">
                {{ currentOrder.payment_status || 'Pending' }}
              </span>
            </div>
            <div>
              <span class="text-gray-500 block text-sm">Total Amount</span>
              <span class="font-bold text-xl text-indigo-600">${{ formatPrice(currentOrder.total_price || 0) }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
          🛒 Order Items
        </h3>
        
        <div class="space-y-4">
          <div 
            v-for="item in currentOrder.order_items" 
            :key="item.order_item_id"
            class="bg-gray-50 rounded-lg p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4"
          >
            <div class="flex items-center gap-4 flex-1">
              <img 
                :src="item.product?.image || 'https://via.placeholder.com/80'"
                :alt="item.product?.name"
                class="w-16 h-16 object-cover rounded-lg border border-gray-200"
              />
              <div>
                <h4 class="font-semibold text-gray-900">{{ item.product?.name || `Product #${item.product_id}` }}</h4>
                <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                <p class="text-sm text-gray-600">Unit Price: ${{ formatPrice(item.unit_price) }}</p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-bold text-lg text-indigo-600">${{ formatPrice(item.unit_price * item.quantity) }}</p>
              <p class="text-xs text-gray-500">Subtotal</p>
            </div>
          </div>
        </div>

        <!-- Order Totals -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">${{ formatPrice(calculateSubtotal()) }}</span>
          </div>
          <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Shipping</span>
            <span class="font-medium">$10.00</span>
          </div>
          <div class="flex justify-between items-center pt-4 border-t border-gray-200">
            <span class="text-xl font-bold text-gray-900">Total</span>
            <span class="text-xl font-bold text-indigo-600">${{ formatPrice(currentOrder.total_price || 0) }}</span>
          </div>
        </div>
      </div>

      <!-- Completion Info -->
      <div v-if="currentOrder.completed_at" class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-6">
        <p class="text-green-700 font-medium">
          ✓ Order completed on {{ formatDate(currentOrder.completed_at) }}
        </p>
      </div>

      <!-- Actions -->
      <div class="flex flex-wrap gap-4">
        <NuxtLink 
          to="/products"
          class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors"
        >
          Continue Shopping
        </NuxtLink>
        <button
          v-if="currentOrder.status === 'pending' && !(currentOrder as any).cancelled_at"
          @click="cancelOrder"
          :disabled="cancelling"
          class="px-6 py-3 bg-red-500 text-white rounded-lg font-medium hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ cancelling ? 'Cancelling...' : 'Cancel Order' }}
        </button>
        <span 
          v-if="currentOrder.status === 'cancelled' || (currentOrder as any).cancelled_at"
          class="px-6 py-3 bg-gray-200 text-gray-600 rounded-lg font-medium cursor-not-allowed"
        >
          Order Cancelled
        </span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const { currentOrder, loading, fetchOrder } = useOrders()
const { post } = useApi()

const cancelling = ref(false)

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
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

const calculateSubtotal = () => {
  if (!currentOrder.value?.order_items) return 0
  return currentOrder.value.order_items.reduce((sum: number, item: any) => sum + (item.unit_price * item.quantity), 0)
}

const cancelOrder = async () => {
  if (!currentOrder.value) return
  
  if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
    return
  }
  
  cancelling.value = true
  try {
    await post(`/orders/${currentOrder.value.order_id}/cancel`, {})
    alert('Order cancelled successfully!')
    await fetchOrder(route.params.id as string)
  } catch (error: any) {
    alert(error.data?.message || 'Failed to cancel order. Please try again.')
  } finally {
    cancelling.value = false
  }
}

onMounted(async () => {
  await fetchOrder(route.params.id as string)
})
</script>
