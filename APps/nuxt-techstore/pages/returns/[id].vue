<template>
  <div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Return Request #{{ returnRequest?.request_id || route.params.id }}</h1>
      <NuxtLink to="/returns" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        Back to Returns
      </NuxtLink>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Return Not Found -->
    <div v-else-if="!returnRequest" class="text-center py-12 bg-white rounded-xl shadow">
      <div class="text-5xl mb-4">🔍</div>
      <h2 class="text-xl font-bold text-gray-800 mb-2">Return Request Not Found</h2>
      <p class="text-gray-600 mb-4">The return request you're looking for doesn't exist.</p>
      <NuxtLink to="/returns" class="text-indigo-600 hover:underline font-medium">
        Go Back to Returns →
      </NuxtLink>
    </div>

    <!-- Return Details -->
    <div v-else>
      <!-- Return Request Details Card -->
      <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 mb-6 text-white">
        <div class="flex justify-between items-start mb-4">
          <h2 class="text-xl font-bold">Return Request Details</h2>
          <span :class="getStatusBadgeClass(returnRequest.status)" class="px-3 py-1 rounded text-xs font-bold uppercase">
            {{ returnRequest.status }}
          </span>
        </div>
        <div class="space-y-2">
          <div class="flex justify-between">
            <span class="opacity-90">Request ID:</span>
            <span class="font-semibold">#{{ returnRequest.request_id }}</span>
          </div>
          <div class="flex justify-between">
            <span class="opacity-90">Order ID:</span>
            <span class="font-semibold">#{{ returnRequest.order_id }}</span>
          </div>
          <div class="flex justify-between">
            <span class="opacity-90">Requested Date:</span>
            <span class="font-semibold">{{ formatDateTime(returnRequest.created_at) }}</span>
          </div>
        </div>
      </div>

      <!-- Return Information -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span>📋</span> Return Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <p class="text-sm text-gray-500 mb-1">Status</p>
            <span :class="getStatusClass(returnRequest.status)" class="inline-block px-3 py-1 rounded text-xs font-bold uppercase">
              {{ returnRequest.status }}
            </span>
          </div>
          <div>
            <p class="text-sm text-gray-500 mb-1">Items Returned</p>
            <p class="font-semibold text-gray-900">{{ returnRequest.items?.length || 0 }} item(s)</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 mb-1">Total Refund Amount</p>
            <p class="font-semibold text-green-600">₱{{ formatPrice(totalRefund) }}</p>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <p class="text-sm text-gray-500 mb-1">Customer</p>
          <p class="font-semibold text-gray-900">{{ returnRequest.customer?.name || 'N/A' }}</p>
        </div>
      </div>

      <!-- Returned Items -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span>📦</span> Returned Items
        </h3>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-gray-200">
              <tr>
                <th class="text-left py-3 text-xs font-semibold text-gray-600 uppercase">Product</th>
                <th class="text-center py-3 text-xs font-semibold text-gray-600 uppercase w-24">Quantity</th>
                <th class="text-center py-3 text-xs font-semibold text-gray-600 uppercase w-28">Unit Price</th>
                <th class="text-center py-3 text-xs font-semibold text-gray-600 uppercase w-32">Refund Amount</th>
                <th class="text-center py-3 text-xs font-semibold text-gray-600 uppercase w-24">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="item in returnRequest.items" :key="item.item_id || item.return_item_id">
                <td class="py-4">
                  <p class="font-semibold text-gray-900">{{ item.product?.name || item.orderItem?.product?.name || 'Product' }}</p>
                  <p class="text-xs text-gray-500">Product ID: {{ item.order_item_id }}</p>
                </td>
                <td class="py-4 text-center text-gray-700">{{ item.quantity }}</td>
                <td class="py-4 text-center text-gray-700">₱{{ formatPrice(item.unit_price || item.orderItem?.unit_price || 0) }}</td>
                <td class="py-4 text-center font-semibold text-green-600">₱{{ formatPrice(item.refund_amount || 0) }}</td>
                <td class="py-4 text-center">
                  <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-semibold uppercase">
                    PENDING
                  </span>
                </td>
              </tr>
            </tbody>
            <tfoot class="border-t-2 border-gray-300 bg-indigo-50">
              <tr>
                <td colspan="3" class="py-3 text-right font-semibold text-indigo-700">Total Refund Amount</td>
                <td class="py-3 text-center font-bold text-lg text-indigo-600">₱{{ formatPrice(totalRefund) }}</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Item Notes -->
      <div v-if="hasItemNotes" class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 mb-6">
        <h4 class="font-semibold text-green-800 mb-2 flex items-center gap-2">
          <span>📝</span> Item Notes
        </h4>
        <div class="space-y-2">
          <div v-for="item in returnRequest.items?.filter(i => i.notes || i.reason)" :key="item.item_id">
            <p class="text-green-700 text-sm">
              <span class="font-semibold">{{ item.product?.name || 'Product' }}:</span> 
              {{ item.notes || item.reason }}
            </p>
          </div>
        </div>
      </div>

      <!-- Order Information -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span>📦</span> Order Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <div>
            <p class="text-sm text-gray-500 mb-1">Order Number</p>
            <p class="font-semibold text-gray-900">#{{ returnRequest.order_id }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 mb-1">Order Date</p>
            <p class="font-semibold text-gray-900">{{ returnRequest.order?.created_at ? formatDateOnly(returnRequest.order.created_at) : 'N/A' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500 mb-1">Order Status</p>
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold uppercase">
              {{ returnRequest.order?.status || 'COMPLETED' }}
            </span>
          </div>
        </div>
        <div class="pt-4 border-t border-gray-100">
          <p class="text-sm text-gray-500 mb-1">Order Total</p>
          <p class="font-bold text-xl text-green-600">₱{{ formatPrice(returnRequest.order?.total_amount || 0) }}</p>
        </div>
      </div>

      <!-- Back to Returns Button -->
      <div class="mb-6">
        <NuxtLink to="/returns" class="inline-block px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
          Back to Returns
        </NuxtLink>
      </div>

      <!-- Timeline -->
      <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span>📅</span> Timeline
        </h3>
        <div class="space-y-4">
          <!-- Request Submitted -->
          <div class="flex gap-3">
            <div class="w-3 h-3 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
            <div>
              <p class="font-semibold text-gray-900">Request Submitted</p>
              <p class="text-sm text-gray-500">{{ formatDateTime(returnRequest.created_at) }}</p>
            </div>
          </div>

          <!-- Approved -->
          <div v-if="returnRequest.status === 'approved' || returnRequest.status === 'refunded'" class="flex gap-3">
            <div class="w-3 h-3 rounded-full bg-green-500 mt-1.5 flex-shrink-0"></div>
            <div>
              <p class="font-semibold text-gray-900">Approved</p>
              <p class="text-sm text-gray-500">{{ formatDateTime(returnRequest.updated_at) }}</p>
            </div>
          </div>

          <!-- Rejected -->
          <div v-if="returnRequest.status === 'rejected'" class="flex gap-3">
            <div class="w-3 h-3 rounded-full bg-red-500 mt-1.5 flex-shrink-0"></div>
            <div>
              <p class="font-semibold text-gray-900">Rejected</p>
              <p class="text-sm text-gray-500">{{ formatDateTime(returnRequest.updated_at) }}</p>
            </div>
          </div>

          <!-- Refunded -->
          <div v-if="returnRequest.status === 'refunded'" class="flex gap-3">
            <div class="w-3 h-3 rounded-full bg-green-600 mt-1.5 flex-shrink-0"></div>
            <div>
              <p class="font-semibold text-gray-900">Refunded</p>
              <p class="text-sm text-gray-500">{{ formatDateTime(returnRequest.updated_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Admin Notes -->
      <div v-if="returnRequest.admin_notes" class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mt-6">
        <h4 class="font-semibold text-blue-800 mb-2">💬 Admin Response</h4>
        <p class="text-blue-700">{{ returnRequest.admin_notes }}</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const { currentReturn, loading, fetchReturn } = useReturns()

const returnRequest = computed(() => currentReturn.value)

const totalRefund = computed(() => {
  if (!returnRequest.value?.items?.length) {
    return returnRequest.value?.refund_amount || 0
  }
  return returnRequest.value.items.reduce((sum, item) => sum + (item.refund_amount || 0), 0)
})

const hasItemNotes = computed(() => {
  return returnRequest.value?.items?.some(item => item.notes || item.reason) || false
})

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-PH', { minimumFractionDigits: 2 }) || '0.00'
}

const formatDateTime = (date: string) => {
  const d = new Date(date)
  return d.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  }) + ' at ' + d.toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

const formatDateOnly = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const getStatusClass = (status: string) => {
  if (!status) return 'bg-gray-100 text-gray-800'
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'approved': 'bg-blue-100 text-blue-800',
    'rejected': 'bg-red-100 text-red-800',
    'refunded': 'bg-blue-100 text-blue-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

const getStatusBadgeClass = (status: string) => {
  if (!status) return 'bg-white/20 text-white'
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-400 text-yellow-900',
    'approved': 'bg-blue-400 text-white',
    'rejected': 'bg-red-400 text-white',
    'refunded': 'bg-white/30 text-white'
  }
  return classes[status.toLowerCase()] || 'bg-white/20 text-white'
}

onMounted(async () => {
  await fetchReturn(route.params.id as string)
})
</script>
