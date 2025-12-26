<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Return Requests</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-gray-500 text-sm uppercase mb-1">Total Requests</div>
        <div class="text-3xl font-bold text-gray-900">{{ statistics.total }}</div>
        <div class="text-gray-400 text-xs mt-1">All return requests</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-gray-500 text-sm uppercase mb-1">Pending</div>
        <div class="text-3xl font-bold text-yellow-600">{{ statistics.pending }}</div>
        <div class="text-gray-400 text-xs mt-1">Requires attention</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-gray-500 text-sm uppercase mb-1">Under Review</div>
        <div class="text-3xl font-bold text-blue-600">{{ statistics.approved }}</div>
        <div class="text-gray-400 text-xs mt-1">In processing</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6">
        <div class="text-gray-500 text-sm uppercase mb-1">Completed</div>
        <div class="text-3xl font-bold text-green-600">{{ statistics.refunded }}</div>
        <div class="text-gray-400 text-xs mt-1">Refunded</div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
      <div class="flex border-b">
        <button
          @click="activeFilter = 'all'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors',
            activeFilter === 'all' 
              ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          All Requests
        </button>
        <button
          @click="activeFilter = 'pending'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors',
            activeFilter === 'pending' 
              ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Pending
        </button>
        <button
          @click="activeFilter = 'approved'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors',
            activeFilter === 'approved' 
              ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Under Review
        </button>
        <button
          @click="activeFilter = 'rejected'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors',
            activeFilter === 'rejected' 
              ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Rejected
        </button>
        <button
          @click="activeFilter = 'refunded'"
          :class="[
            'px-6 py-3 text-sm font-medium transition-colors',
            activeFilter === 'refunded' 
              ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50' 
              : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
          ]"
        >
          Completed
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
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Request ID</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Order ID</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Customer</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Items</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <template v-for="(returnReq, index) in filteredReturns" :key="returnReq?.request_id ?? index">
            <tr v-if="returnReq">
              <td class="px-6 py-4">#{{ returnReq.request_id }}</td>
              <td class="px-6 py-4">
                <NuxtLink :to="`/admin/orders/${returnReq.order_id}`" class="text-primary-600 hover:underline">
                  #{{ returnReq.order_id }}
                </NuxtLink>
              </td>
              <td class="px-6 py-4">{{ returnReq.customer?.name || 'N/A' }}</td>
              <td class="px-6 py-4">
                <span class="text-gray-700">{{ getItemsCount(returnReq) }} item{{ getItemsCount(returnReq) !== 1 ? 's' : '' }}</span>
              </td>
              <td class="px-6 py-4">
                <span :class="getStatusClass(returnReq.status)" class="px-2 py-1 rounded-full text-xs">
                  {{ returnReq.status || 'unknown' }}
                </span>
              </td>
              <td class="px-6 py-4 text-gray-500">{{ returnReq.created_at ? formatDate(returnReq.created_at) : 'N/A' }}</td>
              <td class="px-6 py-4">
                <NuxtLink 
                  :to="`/admin/returns/${returnReq.request_id}`"
                  class="text-primary-600 hover:underline"
                >
                  View
                </NuxtLink>
              </td>
            </tr>
          </template>
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

const { returns, loading, fetchReturns } = useReturns()
const activeFilter = ref('all')

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const getStatusClass = (status?: string) => {
  if (!status) return 'bg-gray-100 text-gray-800'
  const classes: Record<string, string> = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'approved': 'bg-blue-100 text-blue-800',
    'rejected': 'bg-red-100 text-red-800',
    'refunded': 'bg-green-100 text-green-800'
  }
  return classes[status.toLowerCase()] || 'bg-gray-100 text-gray-800'
}

const getItemsCount = (returnReq: any) => {
  const items = returnReq.return_items || returnReq.items || []
  return items.length
}

// Calculate statistics
const statistics = computed(() => {
  const stats = {
    total: returns.value.length,
    pending: 0,
    approved: 0,
    rejected: 0,
    refunded: 0
  }

  returns.value.forEach((returnReq: any) => {
    const status = returnReq.status?.toLowerCase()
    if (status === 'pending') stats.pending++
    else if (status === 'approved') stats.approved++
    else if (status === 'rejected') stats.rejected++
    else if (status === 'refunded') stats.refunded++
  })

  return stats
})

// Filter returns based on active filter
const filteredReturns = computed(() => {
  if (activeFilter.value === 'all') {
    return returns.value
  }
  return returns.value.filter((returnReq: any) => {
    return returnReq.status?.toLowerCase() === activeFilter.value
  })
})

onMounted(async () => {
  await fetchReturns()
})
</script>
