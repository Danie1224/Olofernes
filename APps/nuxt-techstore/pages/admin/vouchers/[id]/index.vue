<template>
  <div>
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="voucher">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
          Voucher Details: <span class="text-primary-600">{{ voucher.code }}</span>
        </h1>
        <div class="flex gap-3">
          <NuxtLink 
            :to="`/admin/vouchers/${voucher.voucher_id}/edit`"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
          </NuxtLink>
          <NuxtLink 
            to="/admin/vouchers"
            class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 transition-colors flex items-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
          </NuxtLink>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Status Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
          <div class="text-gray-500 text-xs uppercase tracking-wide mb-2">Status</div>
          <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div class="text-xl font-bold text-gray-900">{{ getStatusText() }}</div>
          </div>
        </div>

        <!-- Discount Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
          <div class="text-gray-500 text-xs uppercase tracking-wide mb-2">Discount</div>
          <div class="text-2xl font-bold text-blue-600">
            <span v-if="voucher.discount_type === 'percentage'">{{ voucher.discount_value }}%</span>
            <span v-else>₱{{ formatPrice(voucher.discount_value) }}</span>
          </div>
          <div class="text-gray-500 text-sm capitalize">{{ voucher.discount_type }}</div>
        </div>

        <!-- Distributed To Card -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
          <div class="text-gray-500 text-xs uppercase tracking-wide mb-2">Distributed To</div>
          <div class="text-3xl font-bold text-teal-600">{{ distributedCount }}</div>
          <div class="text-gray-500 text-sm">users have this voucher</div>
        </div>
      </div>

      <!-- Two Column Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Voucher Information -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="font-bold text-gray-900">Voucher Information</h3>
            </div>
            <div class="p-6 space-y-4">
              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Code</div>
                <div class="font-mono font-bold text-gray-900">{{ voucher.code }}</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Description</div>
                <div class="text-gray-900">{{ voucher.description || 'No description' }}</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Discount</div>
                <div class="font-bold text-gray-900">
                  <span v-if="voucher.discount_type === 'percentage'">{{ voucher.discount_value }}% Off</span>
                  <span v-else>₱{{ formatPrice(voucher.discount_value) }} Off</span>
                </div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Usage Limit</div>
                <div class="text-gray-900">{{ voucher.total_quantity }} total uses</div>
                <div class="text-sm text-gray-500">Used: {{ usedCount }} ({{ usagePercentage }}%)</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Valid From</div>
                <div class="text-gray-900">{{ formatDate(voucher.start_date) }}</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Valid Until</div>
                <div class="text-gray-900">{{ formatDate(voucher.expiry_date) }}</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Created</div>
                <div class="text-gray-900">{{ formatDateTime(voucher.created_at) }}</div>
              </div>

              <div>
                <div class="text-xs text-gray-500 uppercase tracking-wide mb-1">Last Updated</div>
                <div class="text-gray-900">{{ formatDateTime(voucher.updated_at) }}</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column - Users with This Voucher -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
              <h3 class="font-bold text-gray-900">Users with This Voucher ({{ distributedCount }})</h3>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead class="bg-gray-50 border-b">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">User ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wide">Received</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-if="userVouchers.length === 0">
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                      No users have claimed this voucher yet
                    </td>
                  </tr>
                  <tr v-for="userVoucher in userVouchers" :key="userVoucher.user_voucher_id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-900">{{ userVoucher.user_id }}</td>
                    <td class="px-6 py-4 text-gray-900">{{ userVoucher.user?.name || 'N/A' }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ userVoucher.user?.email || 'N/A' }}</td>
                    <td class="px-6 py-4">
                      <span :class="[
                        'px-3 py-1 rounded text-xs font-medium flex items-center gap-1 w-fit',
                        userVoucher.status === 'used' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                      ]">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ userVoucher.status === 'used' ? 'Used' : 'Available' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ formatDateShort(userVoucher.created_at) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-12">
      <p class="text-gray-600">Voucher not found</p>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const route = useRoute()
const { get } = useApi()

const voucher = ref<any>(null)
const userVouchers = ref<any[]>([])
const loading = ref(true)

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatDateShort = (date: string) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric'
  })
}

const formatDateTime = (date: string) => {
  if (!date) return 'N/A'
  const d = new Date(date)
  return d.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }) + ' ' + d.toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })
}

const getStatusClass = () => {
  if (!voucher.value) return 'bg-gray-100 text-gray-800'
  
  const now = new Date()
  const startDate = new Date(voucher.value.start_date)
  const expiryDate = new Date(voucher.value.expiry_date)
  
  if (voucher.value.remaining_quantity === 0) {
    return 'bg-red-100 text-red-800'
  }
  
  if (now < startDate) {
    return 'bg-blue-100 text-blue-800'
  }
  
  if (now > expiryDate) {
    return 'bg-gray-100 text-gray-800'
  }
  
  return 'bg-green-100 text-green-800'
}

const getStatusText = () => {
  if (!voucher.value) return 'Unknown'
  
  const now = new Date()
  const startDate = new Date(voucher.value.start_date)
  const expiryDate = new Date(voucher.value.expiry_date)
  
  if (voucher.value.remaining_quantity === 0) {
    return 'Out of Stock'
  }
  
  if (now < startDate) {
    return 'Upcoming'
  }
  
  if (now > expiryDate) {
    return 'Expired'
  }
  
  return 'Active'
}

const distributedCount = computed(() => {
  return userVouchers.value.length
})

const usedCount = computed(() => {
  if (!voucher.value) return 0
  return voucher.value.total_quantity - voucher.value.remaining_quantity
})

const usagePercentage = computed(() => {
  if (!voucher.value || voucher.value.total_quantity === 0) return 0
  return Math.round((usedCount.value / voucher.value.total_quantity) * 100)
})

const redistributeVoucher = () => {
  alert('Redistribute functionality coming soon!')
}

// Load voucher data and user vouchers
onMounted(async () => {
  try {
    // Fetch voucher details
    const voucherResponse = await get(`/vouchers/${route.params.id}`)
    voucher.value = voucherResponse
    
    // Fetch user vouchers (users who have claimed this voucher)
    try {
      const userVouchersResponse = await get(`/vouchers/${route.params.id}/users`) as any
      if (Array.isArray(userVouchersResponse)) {
        userVouchers.value = userVouchersResponse
      } else if (userVouchersResponse?.data && Array.isArray(userVouchersResponse.data)) {
        userVouchers.value = userVouchersResponse.data
      }
    } catch (err) {
      console.log('No user vouchers endpoint available')
      userVouchers.value = []
    }
  } catch (err) {
    console.error('Error loading voucher:', err)
  } finally {
    loading.value = false
  }
})
</script>
