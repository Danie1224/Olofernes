<template>
  <div>
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="currentOrder">
      <!-- Purple Header -->
      <div class="bg-indigo-500 text-white rounded-t-lg p-6 relative">
        <NuxtLink 
          to="/admin/orders" 
          class="absolute top-4 right-4 bg-gray-700 hover:bg-gray-600 rounded-full w-8 h-8 flex items-center justify-center transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </NuxtLink>
        
        <h1 class="text-3xl font-bold mb-6">Order #{{ currentOrder.order_id }}</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <div class="text-indigo-200 text-sm mb-1">Customer</div>
            <div class="font-semibold">{{ (currentOrder as any).customer?.name || currentOrder.customer_id }}</div>
          </div>
          <div>
            <div class="text-indigo-200 text-sm mb-1">Order Date</div>
            <div class="font-semibold">{{ formatDateShort(currentOrder.created_at) }}</div>
          </div>
          <div>
            <div class="text-indigo-200 text-sm mb-1">Total</div>
            <div class="font-semibold">₱{{ formatPrice(currentOrder.total_price || 0) }}</div>
          </div>
          <div>
            <div class="text-indigo-200 text-sm mb-1">Status</div>
            <div>
              <span 
                :class="getStatusClass(currentOrder.status)"
                class="inline-block px-3 py-1 rounded text-sm font-medium"
              >
                {{ capitalizeStatus(currentOrder.status) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="bg-white p-6 border-b">
        <h2 class="text-lg font-bold mb-4">Order Summary</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
          <div>
            <div class="text-gray-500 text-sm uppercase mb-2">Payment Method</div>
            <div class="font-medium">{{ currentOrder.payment_method }}</div>
          </div>
          <div>
            <div class="text-gray-500 text-sm uppercase mb-2">Payment Status</div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              <span class="text-green-600 font-medium">Paid</span>
            </div>
          </div>
          <div>
            <div class="text-gray-500 text-sm uppercase mb-2">Items Count</div>
            <div class="font-medium">{{ getItemsCount() }} item{{ getItemsCount() !== 1 ? 's' : '' }}</div>
          </div>
          <div v-if="(currentOrder as any).discount_amount">
            <div class="text-gray-500 text-sm uppercase mb-2">Discount Applied</div>
            <div class="font-medium text-red-600">₱{{ formatPrice((currentOrder as any).discount_amount) }}</div>
          </div>
          <div :class="(currentOrder as any).discount_amount ? '' : 'md:col-start-5'">
            <div class="text-gray-500 text-sm uppercase mb-2">Order Status</div>
            <div>
              <span 
                :class="getStatusClass(currentOrder.status)"
                class="inline-block px-3 py-1 rounded text-sm font-medium"
              >
                {{ capitalizeStatus(currentOrder.status) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Mark Order as Completed -->
      <div v-if="currentOrder.status !== 'completed'" class="bg-indigo-500 text-white p-6">
        <h3 class="text-lg font-bold mb-1">Mark Order as Completed</h3>
        <div class="text-indigo-200 text-sm mb-4">Admin Action</div>
        
        <p class="text-white mb-4">
          Click the button below to mark this order as completed. This action will record the completion timestamp and the admin who marked it complete.
        </p>
        
        <button
          @click="markComplete"
          :disabled="completing"
          class="bg-white text-indigo-600 px-6 py-2 rounded-md hover:bg-indigo-50 disabled:opacity-50 font-medium flex items-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
          {{ completing ? 'Processing...' : 'Mark as Completed' }}
        </button>
      </div>

      <!-- Order Items -->
      <div class="bg-white p-6">
        <h2 class="text-lg font-bold mb-4">Order Items</h2>
        
        <div class="border rounded-lg overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
                <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">QTY</th>
                <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subtotal</th>
              </tr>
            </thead>
            <tbody class="divide-y">
              <tr v-for="item in currentOrder.order_items" :key="item.order_item_id">
                <td class="px-6 py-4">
                  <div class="font-medium">{{ item.product?.name || `Product #${item.product_id}` }}</div>
                  <div class="text-gray-500 text-sm">SKU: {{ (item.product as any)?.product_code || 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 text-center">{{ item.quantity }}</td>
                <td class="px-6 py-4 text-right text-gray-600">₱{{ formatPrice(item.unit_price) }}</td>
                <td class="px-6 py-4 text-right font-semibold text-green-600">₱{{ formatPrice(item.unit_price * item.quantity) }}</td>
              </tr>
            </tbody>
          </table>
          
          <div class="bg-gray-50 px-6 py-4 border-t space-y-2">
            <div v-if="(currentOrder as any).discount_amount" class="flex justify-end items-center gap-4 text-sm">
              <span class="text-gray-600">Subtotal</span>
              <span class="text-gray-800">₱{{ formatPrice(getSubtotal()) }}</span>
            </div>
            <div v-if="(currentOrder as any).discount_amount" class="flex justify-end items-center gap-4 text-sm">
              <span class="text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Discount Applied
              </span>
              <span class="text-red-600 font-medium">-₱{{ formatPrice((currentOrder as any).discount_amount) }}</span>
            </div>
            <div class="flex justify-end items-center gap-4 pt-2 border-t">
              <span class="text-gray-600">Total Amount</span>
              <span class="text-2xl font-bold text-indigo-600">₱{{ formatPrice(currentOrder.total_price || 0) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div v-if="completeSuccess" class="mt-4 bg-green-50 text-green-600 p-4 rounded-md flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Order marked as complete!
      </div>
    </div>

    <div v-else class="text-center py-12 text-gray-600">
      Order not found.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})


//Imports and Data State
const route = useRoute()
const { currentOrder, loading, fetchOrder, completeOrder } = useOrders()

const completing = ref(false)
const completeSuccess = ref(false)

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatDateShort = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const capitalizeStatus = (status: string) => {
  return status.charAt(0).toUpperCase() + status.slice(1)
}

const getItemsCount = () => {
  return currentOrder.value?.order_items?.length || 0
}

const getSubtotal = () => {
  if (!currentOrder.value?.order_items) return 0
  return currentOrder.value.order_items.reduce((sum, item) => {
    return sum + (item.unit_price * item.quantity)
  }, 0)
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


// Mark Order as Completed
const markComplete = async () => {
  if (!currentOrder.value) return
  
  completing.value = true
  try {
    await completeOrder(currentOrder.value.order_id)
    completeSuccess.value = true
    await fetchOrder(route.params.id as string)
  } catch (error) {
    alert('Failed to complete order')
  } finally {
    completing.value = false
  }
}

onMounted(async () => {
  await fetchOrder(route.params.id as string)
})
</script>
