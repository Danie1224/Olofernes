<template>
  <div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">My Return Requests</h1>
      <NuxtLink 
        to="/returns/create"
        class="px-5 py-2.5 text-white rounded-lg font-medium transition-all shadow-md hover:shadow-lg"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
      >
        + Request Return
      </NuxtLink>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-5 rounded-xl shadow-lg text-center transform hover:-translate-y-1 transition-transform">
        <div class="text-sm opacity-90 mb-1">Total Returns</div>
        <div class="text-4xl font-bold">{{ stats.total }}</div>
      </div>
      <div class="bg-gradient-to-br from-amber-400 to-orange-500 text-white p-5 rounded-xl shadow-lg text-center transform hover:-translate-y-1 transition-transform">
        <div class="text-sm opacity-90 mb-1">Pending</div>
        <div class="text-4xl font-bold">{{ stats.pending }}</div>
      </div>
      <div class="bg-gradient-to-br from-green-400 to-green-600 text-white p-5 rounded-xl shadow-lg text-center transform hover:-translate-y-1 transition-transform">
        <div class="text-sm opacity-90 mb-1">Approved</div>
        <div class="text-4xl font-bold">{{ stats.approved }}</div>
      </div>
      <div class="bg-gradient-to-br from-blue-400 to-blue-600 text-white p-5 rounded-xl shadow-lg text-center transform hover:-translate-y-1 transition-transform">
        <div class="text-sm opacity-90 mb-1">Refunded</div>
        <div class="text-4xl font-bold">{{ stats.refunded }}</div>
      </div>
    </div>

    <!-- Filter Buttons -->
    <div class="flex flex-wrap gap-2 mb-6">
      <button
        @click="statusFilter = ''"
        :class="[
          'px-4 py-2 rounded-lg font-medium border-2 transition-all',
          statusFilter === '' 
            ? 'bg-blue-600 text-white border-blue-600' 
            : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'
        ]"
      >
        All Returns
      </button>
      <button
        @click="statusFilter = 'pending'"
        :class="[
          'px-4 py-2 rounded-lg font-medium border-2 transition-all',
          statusFilter === 'pending' 
            ? 'bg-blue-600 text-white border-blue-600' 
            : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'
        ]"
      >
        Pending
      </button>
      <button
        @click="statusFilter = 'approved'"
        :class="[
          'px-4 py-2 rounded-lg font-medium border-2 transition-all',
          statusFilter === 'approved' 
            ? 'bg-blue-600 text-white border-blue-600' 
            : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'
        ]"
      >
        Approved
      </button>
      <button
        @click="statusFilter = 'refunded'"
        :class="[
          'px-4 py-2 rounded-lg font-medium border-2 transition-all',
          statusFilter === 'refunded' 
            ? 'bg-blue-600 text-white border-blue-600' 
            : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300'
        ]"
      >
        Refunded
      </button>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
      {{ errorMessage }}
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="filteredReturns.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
      <div class="text-5xl mb-4">📦</div>
      <h3 class="text-xl font-bold text-gray-800 mb-2">No Return Requests</h3>
      <p class="text-gray-600 mb-4">You haven't submitted any return requests yet. If you received a damaged product, we're here to help!</p>
      <NuxtLink 
        to="/returns/create"
        class="inline-block px-6 py-3 text-white rounded-lg font-medium"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
      >
        Create Return Request
      </NuxtLink>
    </div>

    <!-- Returns List -->
    <div v-else class="space-y-4">
      <div 
        v-for="returnReq in filteredReturns" 
        :key="returnReq.request_id"
        class="bg-white rounded-xl shadow-md p-6 border-l-4"
        :class="getBorderClass(returnReq.status)"
      >
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
          <!-- Return Info -->
          <div class="flex-1">
            <h3 class="font-bold text-lg text-gray-900 mb-4">Return Request #{{ returnReq.request_id }}</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
              <div>
                <span class="text-gray-500 block text-xs font-medium">Order ID</span>
                <span class="text-gray-900 font-semibold">#{{ returnReq.order_id }}</span>
              </div>
              <div>
                <span class="text-gray-500 block text-xs font-medium">Items Count</span>
                <span class="text-gray-900 font-semibold">{{ returnReq.items?.length || 1 }} item(s)</span>
              </div>
              <div>
                <span class="text-gray-500 block text-xs font-medium">Requested Date</span>
                <span class="text-gray-900 font-semibold">{{ formatDate(returnReq.created_at) }}</span>
              </div>
              <div>
                <span class="text-gray-500 block text-xs font-medium">Total Refund</span>
                <span class="text-green-600 font-semibold">₱{{ formatPrice(returnReq.refund_amount || 0) }}</span>
              </div>
              <div>
                <span class="text-gray-500 block text-xs font-medium">Status</span>
                <span :class="getStatusClass(returnReq.status)" class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase mt-1">
                  {{ returnReq.status }}
                </span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-col gap-2 lg:min-w-[140px]">
            <NuxtLink 
              :to="`/returns/${returnReq.request_id}`"
              class="px-4 py-2.5 text-white text-center rounded-lg font-medium transition-all hover:shadow-lg"
              style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
            >
              View Details
            </NuxtLink>
            <button
              v-if="returnReq.status === 'pending'"
              @click="cancelReturn(returnReq.request_id)"
              :disabled="cancelling === returnReq.request_id"
              class="px-4 py-2.5 bg-white text-red-600 border-2 border-red-500 rounded-lg font-medium hover:bg-red-50 transition-colors disabled:opacity-50"
            >
              {{ cancelling === returnReq.request_id ? 'Cancelling...' : 'Cancel Request' }}
            </button>
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

const { get, del } = useApi()

interface ReturnRequest {
  request_id: number
  order_id: number
  reason: string
  status: string
  refund_amount?: number
  admin_notes?: string
  created_at: string
  items?: any[]
}

const returns = ref<ReturnRequest[]>([])
const loading = ref(true)
const statusFilter = ref('')
const cancelling = ref<number | null>(null)
const successMessage = ref('')
const errorMessage = ref('')

const stats = computed(() => {
  const list = Array.isArray(returns.value) ? returns.value : []
  return {
    total: list.length,
    pending: list.filter(r => r.status === 'pending').length,
    approved: list.filter(r => r.status === 'approved').length,
    refunded: list.filter(r => r.status === 'refunded').length,
  }
})

const filteredReturns = computed(() => {
  const list = Array.isArray(returns.value) ? returns.value : []
  if (!statusFilter.value) return list
  return list.filter(r => r.status === statusFilter.value)
})

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatPrice = (price: number) => {
  return price.toLocaleString('en-US', { minimumFractionDigits: 2 })
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-300 text-yellow-900',
    'approved': 'bg-green-100 text-green-800',
    'rejected': 'bg-red-100 text-red-800',
    'refunded': 'bg-purple-100 text-purple-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

const getBorderClass = (status: string) => {
  const classes: Record<string, string> = {
    'pending': 'border-yellow-400',
    'approved': 'border-green-500',
    'rejected': 'border-red-500',
    'refunded': 'border-purple-500'
  }
  return classes[status.toLowerCase()] || 'border-gray-300'
}

const fetchReturns = async () => {
  loading.value = true
  try {
    const response = await get<{ data: any }>('/return-requests')
    // Handle paginated response (data.data) or direct array (data)
    const responseData = response.data
    if (Array.isArray(responseData)) {
      returns.value = responseData
    } else if (responseData && Array.isArray(responseData.data)) {
      returns.value = responseData.data
    } else {
      returns.value = []
    }
  } catch (error) {
    console.error('Failed to fetch returns:', error)
    returns.value = []
  } finally {
    loading.value = false
  }
}

const cancelReturn = async (requestId: number) => {
  if (!confirm('Are you sure you want to cancel this return request?')) return
  
  cancelling.value = requestId
  errorMessage.value = ''
  successMessage.value = ''
  
  try {
    await del(`/return-requests/${requestId}`)
    successMessage.value = 'Return request cancelled successfully'
    await fetchReturns()
  } catch (error: any) {
    errorMessage.value = error.data?.message || 'Failed to cancel return request'
  } finally {
    cancelling.value = null
  }
}

onMounted(async () => {
  await fetchReturns()
})
</script>
