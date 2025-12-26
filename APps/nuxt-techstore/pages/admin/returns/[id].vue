<template>
  <div>
    <!-- Back Button -->
    <NuxtLink to="/admin/returns" class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors mb-6">
      ← Back to Returns
    </NuxtLink>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="currentReturn">
      <!-- Orange Header Banner -->
      <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-t-xl p-6 text-white mb-0">
        <h1 class="text-2xl font-bold mb-4">Return Request RR-{{ String(currentReturn.request_id).padStart(5, '0') }}</h1>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <div class="bg-white/20 rounded-lg p-3">
            <p class="text-xs opacity-80">Order</p>
            <p class="font-semibold">ORD-{{ String(currentReturn.order_id).padStart(5, '0') }}</p>
          </div>
          <div class="bg-white/20 rounded-lg p-3">
            <p class="text-xs opacity-80">Requested</p>
            <p class="font-semibold">{{ formatDateShort(currentReturn.created_at) }}</p>
          </div>
          <div class="bg-white/20 rounded-lg p-3">
            <p class="text-xs opacity-80">Status</p>
            <span :class="getStatusBadgeClass(currentReturn.status)" class="inline-block px-2 py-0.5 rounded text-xs font-semibold mt-1">
              {{ currentReturn.status }}
            </span>
          </div>
          <div class="bg-white/20 rounded-lg p-3">
            <p class="text-xs opacity-80">Refund Total</p>
            <p class="font-semibold">₱{{ formatPrice(totalRefund) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Customer Information -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-4">Customer Information</h3>
            <div class="grid grid-cols-3 gap-4">
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Name</p>
                <p class="font-medium text-gray-900">{{ currentReturn.customer?.name || 'N/A' }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Email</p>
                <p class="font-medium text-gray-900">{{ currentReturn.customer?.email || 'N/A' }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Customer ID</p>
                <p class="font-medium text-gray-700 text-sm">{{ currentReturn.customer_id || 'N/A' }}</p>
              </div>
            </div>
          </div>

          <!-- Request Information -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-4">Request Information</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Requested Date</p>
                <p class="font-medium text-gray-900">{{ formatDateLong(currentReturn.created_at) }}</p>
                <p class="text-sm text-gray-500">{{ formatTime(currentReturn.created_at) }}</p>
              </div>
              <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Last Updated</p>
                <p class="font-medium text-gray-900">{{ formatDateLong(currentReturn.updated_at) }}</p>
                <p class="text-sm text-gray-500">{{ formatTime(currentReturn.updated_at) }}</p>
              </div>
            </div>
          </div>

          <!-- Return Items -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-4">Return Items ({{ returnItems.length }})</h3>
            <table class="w-full">
              <thead class="border-b border-gray-200">
                <tr>
                  <th class="text-left py-3 text-xs font-semibold text-gray-500 uppercase">Product</th>
                  <th class="text-center py-3 text-xs font-semibold text-gray-500 uppercase w-16">Qty</th>
                  <th class="text-center py-3 text-xs font-semibold text-gray-500 uppercase w-24">Unit Price</th>
                  <th class="text-center py-3 text-xs font-semibold text-gray-500 uppercase w-28">Reason</th>
                  <th class="text-right py-3 text-xs font-semibold text-gray-500 uppercase w-28">Refund Amount</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr v-for="item in returnItems" :key="item.item_id || item.order_item_id">
                  <td class="py-4">
                    <p class="font-medium text-gray-900">{{ item.product?.name || item.orderItem?.product?.name || 'Product' }}</p>
                    <p class="text-xs text-gray-500">SKU: {{ item.product?.sku || item.orderItem?.product?.sku || 'N/A' }}</p>
                  </td>
                  <td class="py-4 text-center text-gray-600">{{ item.quantity }}</td>
                  <td class="py-4 text-center text-gray-600">₱{{ formatPrice(item.unit_price || item.orderItem?.unit_price || 0) }}</td>
                  <td class="py-4 text-center">
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                      {{ item.reason || 'N/A' }}
                    </span>
                    <p v-if="item.notes" class="text-xs text-gray-500 mt-1 italic">{{ item.notes }}</p>
                  </td>
                  <td class="py-4 text-right font-semibold text-green-600">₱{{ formatPrice(item.refund_amount || 0) }}</td>
                </tr>
              </tbody>
              <tfoot class="border-t-2 border-gray-200">
                <tr>
                  <td colspan="4" class="py-4 text-right font-semibold text-gray-700">Total Refund Amount</td>
                  <td class="py-4 text-right font-bold text-xl text-green-600">₱{{ formatPrice(totalRefund) }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

        <!-- Right Column - Actions & Timeline -->
        <div class="space-y-6">
          <!-- Action Required Card -->
          <div v-if="currentReturn.status === 'pending'" class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-red-600 mb-4">Action Required</h3>
            
            <!-- Approve Section -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Add Notes (Optional)</label>
              <textarea
                v-model="approvalNotes"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                placeholder="Optional: Add approval notes..."
              ></textarea>
            </div>
            <button
              @click="handleApprove"
              :disabled="processing"
              class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 disabled:opacity-50 transition-colors flex items-center justify-center gap-2 mb-4"
            >
              <span>✓</span> Approve Return
            </button>

            <!-- Reject Section -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Required)</label>
              <textarea
                v-model="rejectionReason"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 text-sm"
                placeholder="Explain why this request is being rejected..."
              ></textarea>
            </div>
            <button
              @click="handleReject"
              :disabled="processing || !rejectionReason.trim()"
              class="w-full bg-red-500 text-white py-3 rounded-lg font-semibold hover:bg-red-600 disabled:opacity-50 transition-colors flex items-center justify-center gap-2"
            >
              <span>✗</span> Reject Return
            </button>
          </div>

          <!-- Approved - Process Refund -->
          <div v-if="currentReturn.status === 'approved'" class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-blue-600 mb-4">Process Refund</h3>
            <p class="text-sm text-gray-600 mb-4">This return has been approved. Click below to process the refund.</p>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Refund Notes (Optional)</label>
              <textarea
                v-model="refundNotes"
                rows="2"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 text-sm"
                placeholder="Optional: Add refund notes..."
              ></textarea>
            </div>
            <button
              @click="handleRefund"
              :disabled="processing"
              class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 disabled:opacity-50 transition-colors"
            >
              💰 Process Refund (₱{{ formatPrice(totalRefund) }})
            </button>
          </div>

          <!-- Status Messages -->
          <div v-if="message" :class="messageType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700'" class="p-4 rounded-lg border">
            {{ message }}
          </div>

          <!-- Timeline -->
          <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-4">Timeline</h3>
            <div class="space-y-4">
              <!-- Request Created -->
              <div class="flex gap-3">
                <div class="w-3 h-3 rounded-full bg-orange-400 mt-1.5 flex-shrink-0"></div>
                <div>
                  <p class="font-medium text-gray-900">📦 Request Created</p>
                  <p class="text-sm text-gray-500">{{ formatDateLong(currentReturn.created_at) }} {{ formatTime(currentReturn.created_at) }}</p>
                </div>
              </div>

              <!-- Pending Review -->
              <div v-if="currentReturn.status === 'pending'" class="flex gap-3">
                <div class="w-3 h-3 rounded-full bg-yellow-400 mt-1.5 flex-shrink-0"></div>
                <div>
                  <p class="font-medium text-gray-900">⏳ Pending Review</p>
                  <p class="text-sm text-gray-500">Awaiting review</p>
                </div>
              </div>

              <!-- Approved -->
              <div v-if="currentReturn.status === 'approved' || currentReturn.status === 'refunded'" class="flex gap-3">
                <div class="w-3 h-3 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></div>
                <div>
                  <p class="font-medium text-gray-900">✓ Approved</p>
                  <p class="text-sm text-gray-500">{{ formatDateLong(currentReturn.updated_at) }}</p>
                </div>
              </div>

              <!-- Rejected -->
              <div v-if="currentReturn.status === 'rejected'" class="flex gap-3">
                <div class="w-3 h-3 rounded-full bg-red-400 mt-1.5 flex-shrink-0"></div>
                <div>
                  <p class="font-medium text-gray-900">✗ Rejected</p>
                  <p class="text-sm text-gray-500">{{ formatDateLong(currentReturn.updated_at) }}</p>
                </div>
              </div>

              <!-- Refunded -->
              <div v-if="currentReturn.status === 'refunded'" class="flex gap-3">
                <div class="w-3 h-3 rounded-full bg-green-400 mt-1.5 flex-shrink-0"></div>
                <div>
                  <p class="font-medium text-gray-900">💰 Refunded</p>
                  <p class="text-sm text-gray-500">{{ formatDateLong(currentReturn.updated_at) }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Admin Notes Display -->
          <div v-if="currentReturn.admin_notes" class="bg-blue-50 rounded-xl p-4 border border-blue-200">
            <h4 class="font-semibold text-blue-800 mb-2">📝 Admin Notes</h4>
            <p class="text-blue-700 text-sm">{{ currentReturn.admin_notes }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12 text-gray-600">
      Return request not found.
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const route = useRoute()
const { currentReturn, loading, fetchReturn, approveReturn, rejectReturn, refundReturn } = useReturns()

const approvalNotes = ref('')
const rejectionReason = ref('')
const refundNotes = ref('')
const processing = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

const returnItems = computed(() => {
  return currentReturn.value?.items || []
})

const totalRefund = computed(() => {
  if (!returnItems.value.length) {
    return currentReturn.value?.refund_amount || 0
  }
  return returnItems.value.reduce((sum: number, item: any) => sum + (item.refund_amount || 0), 0)
})

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-PH', { minimumFractionDigits: 2 }) || '0.00'
}

const formatDateShort = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const formatDateLong = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const formatTime = (date: string) => {
  return new Date(date).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  })
}

const getStatusBadgeClass = (status: string) => {
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-400 text-yellow-900',
    'approved': 'bg-blue-500 text-white',
    'rejected': 'bg-red-500 text-white',
    'refunded': 'bg-green-500 text-white'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

const handleApprove = async () => {
  processing.value = true
  message.value = ''
  try {
    await approveReturn(currentReturn.value!.request_id, approvalNotes.value)
    message.value = 'Return request approved successfully!'
    messageType.value = 'success'
    await fetchReturn(route.params.id as string)
  } catch (error) {
    message.value = 'Failed to approve return request'
    messageType.value = 'error'
  } finally {
    processing.value = false
  }
}

const handleReject = async () => {
  if (!rejectionReason.value.trim()) {
    message.value = 'Please provide a rejection reason'
    messageType.value = 'error'
    return
  }
  
  processing.value = true
  message.value = ''
  try {
    await rejectReturn(currentReturn.value!.request_id, rejectionReason.value)
    message.value = 'Return request rejected'
    messageType.value = 'success'
    await fetchReturn(route.params.id as string)
  } catch (error) {
    message.value = 'Failed to reject return request'
    messageType.value = 'error'
  } finally {
    processing.value = false
  }
}

const handleRefund = async () => {
  processing.value = true
  message.value = ''
  try {
    await refundReturn(currentReturn.value!.request_id, refundNotes.value)
    message.value = 'Refund processed successfully!'
    messageType.value = 'success'
    await fetchReturn(route.params.id as string)
  } catch (error) {
    message.value = 'Failed to process refund'
    messageType.value = 'error'
  } finally {
    processing.value = false
  }
}

onMounted(async () => {
  await fetchReturn(route.params.id as string)
})
</script>
