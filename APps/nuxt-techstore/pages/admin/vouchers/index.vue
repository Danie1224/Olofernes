<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Voucher Management</h1>
      <NuxtLink 
        to="/admin/vouchers/create"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2"
      >
        <span class="text-xl">+</span> Create New Voucher
      </NuxtLink>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Discount</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Distributed</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Usage</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Valid Period</th>
            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="voucher in vouchers" :key="voucher.voucher_id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <span class="font-mono font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded">
                {{ voucher.code }}
              </span>
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm">
              {{ voucher.description || '-' }}
            </td>
            <td class="px-6 py-4">
              <span :class="voucher.discount_type === 'percentage' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'" 
                    class="px-3 py-1 rounded-full text-sm font-medium">
                {{ voucher.discount_type === 'percentage' 
                  ? `${voucher.discount_value}% off` 
                  : `₱${formatPrice(voucher.discount_value)}` }}
              </span>
            </td>
            <td class="px-6 py-4">
              <span 
                :class="voucher.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700'"
                class="px-3 py-1 rounded-full text-sm font-medium"
              >
                {{ voucher.status === 'active' ? 'Active' : voucher.status }}
              </span>
            </td>
            <td class="px-6 py-4 text-sm">
              <span class="text-blue-600 font-medium">{{ voucher.distributed_count || 0 }} users</span>
            </td>
            <td class="px-6 py-4">
              <span class="text-gray-700 text-sm">
                {{ voucher.usage_count || 0 }}<span class="text-gray-400">/{{ voucher.usage_limit || '∞' }}</span>
              </span>
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm">
              {{ voucher.start_date && voucher.end_date ? formatDateRange(voucher.start_date, voucher.end_date) : '-' }}
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <NuxtLink 
                  :to="`/admin/vouchers/${voucher.voucher_id}`"
                  class="text-gray-500 hover:text-gray-700"
                  title="View"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </NuxtLink>
                <NuxtLink 
                  :to="`/admin/vouchers/${voucher.voucher_id}/edit`"
                  class="text-gray-500 hover:text-blue-600"
                  title="Edit"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </NuxtLink>
                <button 
                  @click="handleDelete(voucher.voucher_id)"
                  class="text-gray-500 hover:text-red-600"
                  title="Delete"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
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

const { vouchers, loading, fetchVouchers, deleteVoucher } = useVouchers()

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const formatDateRange = (startDate: string, endDate: string) => {
  const start = new Date(startDate)
  const end = new Date(endDate)
  
  const formatShort = (date: Date) => {
    return date.toLocaleDateString('en-US', {
      month: 'short',
      day: 'numeric'
    })
  }
  
  return `${formatShort(start)} - ${formatShort(end)}`
}

const handleDelete = async (id: number) => {
  if (!confirm('Are you sure you want to delete this voucher?')) return
  
  try {
    await deleteVoucher(id)
  } catch (error) {
    alert('Failed to delete voucher')
  }
}

onMounted(async () => {
  await fetchVouchers()
})
</script>
