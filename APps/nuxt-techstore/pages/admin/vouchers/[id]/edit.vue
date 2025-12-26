<template>
  <div class="max-w-7xl mx-auto">
    <div v-if="loading" class="bg-white rounded-lg shadow-md p-8 text-center">
      <p class="text-gray-500">Loading voucher...</p>
    </div>

    <div v-else-if="!voucher" class="bg-white rounded-lg shadow-md p-8 text-center">
      <p class="text-red-500">Voucher not found</p>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left side: Edit Form (2 columns) -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Voucher Details</h2>
          
          <form @submit.prevent="handleSubmit">
            <!-- Voucher Code -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Voucher Code <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.code"
                type="text"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono uppercase"
                placeholder="e.g., SUMMER20, WELCOME10"
              />
              <p class="text-sm text-gray-500 mt-1">Must be unique. Use uppercase letters and numbers.</p>
            </div>

            <!-- Description -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                placeholder="What is this voucher for?"
              ></textarea>
              <p class="text-sm text-gray-500 mt-1">Optional. Helps you remember the voucher's purpose.</p>
            </div>

            <!-- Discount Type and Value -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Discount Type <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.discount_type"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                >
                  <option value="percentage">Percentage (%)</option>
                  <option value="fixed">Fixed Amount (₱)</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Discount Value <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                  <input
                    v-model.number="form.discount_value"
                    type="number"
                    min="0"
                    step="0.01"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent pr-12"
                    placeholder="0.00"
                  />
                  <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                    {{ form.discount_type === 'percentage' ? '%' : '₱' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Usage Limit -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Usage Limit (Optional)
              </label>
              <input
                v-model.number="form.usage_limit"
                type="number"
                min="0"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Leave empty for unlimited uses"
              />
              <p class="text-sm text-gray-500 mt-1">Total times this voucher can be used across all users. Currently used: {{ voucher.usage_count || 0 }} times.</p>
            </div>

            <!-- Start and End Date -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Start Date <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.start_date"
                  type="date"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  End Date <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.end_date"
                  type="date"
                  required
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Status <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.status"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
              >
                <option value="" disabled>-- Select Status --</option>
                <option value="active">Active (Available for users)</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
              {{ error }}
            </div>

            <!-- Success Message -->
            <div v-if="success" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
              {{ success }}
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
              <button
                type="button"
                @click="navigateTo('/admin/vouchers')"
                class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="processing"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-2"
              >
                <span v-if="!processing">💾</span>
                <span>{{ processing ? 'Saving...' : 'Save Changes' }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Right side: Info Panel (1 column) -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Voucher Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
          <div class="flex items-center gap-2 mb-4">
            <span class="text-blue-500">ℹ️</span>
            <h3 class="font-bold text-gray-800">Voucher Info</h3>
          </div>
          
          <div class="space-y-4">
            <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Created</p>
              <p class="text-sm font-medium text-gray-800">{{ formatDateTime(voucher.created_at) }}</p>
            </div>
            
            <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Last Updated</p>
              <p class="text-sm font-medium text-gray-800">{{ formatDateTime(voucher.updated_at) }}</p>
            </div>
            
            <div>
              <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Users with Voucher</p>
              <p class="text-sm font-medium text-gray-800">{{ userVouchers.length }} users</p>
            </div>
          </div>

          <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-xs text-gray-600 leading-relaxed">
              Editing a voucher only affects new applications. Users who already have this voucher keep their copy.
            </p>
          </div>
        </div>

        <!-- Danger Zone Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
          <div class="flex items-center gap-2 mb-3">
            <span class="text-red-500">⚠️</span>
            <h3 class="font-bold text-gray-800">Danger Zone</h3>
          </div>
          
          <p class="text-sm text-gray-600 mb-4">
            Delete this voucher and all its user distributions. This action cannot be undone.
          </p>
          
          <button
            type="button"
            @click="confirmDelete"
            :disabled="deleting"
            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center justify-center gap-2"
          >
            <span v-if="!deleting">🗑️</span>
            <span>{{ deleting ? 'Deleting...' : 'Delete This Voucher' }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const route = useRoute()
const { fetchVoucher, updateVoucher, deleteVoucher } = useVouchers()
const { get } = useApi()

const voucher = ref<any>(null)
const loading = ref(true)
const userVouchers = ref<any[]>([])

const form = reactive({
  code: '',
  description: '',
  discount_type: 'percentage' as 'percentage' | 'fixed',
  discount_value: 0,
  usage_limit: null as number | null,
  start_date: '',
  end_date: '',
  status: ''
})

const processing = ref(false)
const deleting = ref(false)
const error = ref('')
const success = ref('')

// Load voucher data on mount
onMounted(async () => {
  try {
    const response = await get(`/vouchers/${route.params.id}`)
    voucher.value = (response as any).data || response
    
    // Pre-fill form with existing data
    form.code = voucher.value.code
    form.description = voucher.value.description || ''
    form.discount_type = voucher.value.discount_type
    form.discount_value = voucher.value.discount_value
    form.usage_limit = voucher.value.usage_limit
    form.start_date = voucher.value.start_date?.split('T')[0] || voucher.value.start_date
    form.end_date = voucher.value.end_date?.split('T')[0] || voucher.value.end_date
    form.status = voucher.value.status

    // Load user vouchers to show count
    try {
      const usersResponse = await get(`/vouchers/${route.params.id}/users`)
      userVouchers.value = (usersResponse as any).data || []
    } catch (err) {
      console.error('Failed to load user vouchers:', err)
      userVouchers.value = []
    }
  } catch (err) {
    console.error('Error loading voucher:', err)
    error.value = 'Failed to load voucher'
  } finally {
    loading.value = false
  }
})

// Format date and time
const formatDateTime = (dateString: string) => {
  if (!dateString) return 'N/A'
  const date = new Date(dateString)
  return date.toLocaleString('en-US', {
    month: 'short',
    day: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
  })
}

// Calculate used count from userVouchers who have status 'used' or from voucher fields
const usedCount = computed(() => {
  if (!voucher.value) return 0
  
  // If voucher has usage_count field, use it
  if (voucher.value.usage_count !== undefined && voucher.value.usage_count !== null) {
    return voucher.value.usage_count
  }
  
  // If voucher has used_count field, use it
  if (voucher.value.used_count !== undefined && voucher.value.used_count !== null) {
    return voucher.value.used_count
  }
  
  // If voucher has total_quantity and remaining_quantity, calculate it
  if (voucher.value.total_quantity !== undefined && voucher.value.remaining_quantity !== undefined) {
    return voucher.value.total_quantity - voucher.value.remaining_quantity
  }
  
  // Count from userVouchers with 'used' status
  const usedVouchers = userVouchers.value.filter((uv: any) => uv.status === 'used' || uv.status === 'redeemed')
  return usedVouchers.length
})

const handleSubmit = async () => {
  processing.value = true
  error.value = ''
  success.value = ''

  try {
    const payload: any = {
      code: form.code.toUpperCase(),
      description: form.description || undefined,
      discount_type: form.discount_type,
      discount_value: form.discount_value,
      usage_limit: form.usage_limit || undefined,
      start_date: form.start_date,
      end_date: form.end_date,
      status: form.status
    }

    await updateVoucher(Number(route.params.id), payload)
    success.value = 'Voucher updated successfully!'
    
    // Reload voucher data to reflect changes
    const response = await get(`/vouchers/${route.params.id}`)
    voucher.value = (response as any).data || response
    
    // Redirect after showing success message
    setTimeout(() => {
      navigateTo(`/admin/vouchers/${route.params.id}`)
    }, 1500)
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to update voucher'
  } finally {
    processing.value = false
  }
}

const confirmDelete = () => {
  const confirmed = confirm(
    `Are you sure you want to delete voucher "${voucher.value.code}"?\n\n` +
    `This will delete the voucher and all ${userVouchers.value.length} user distributions.\n\n` +
    'This action cannot be undone!'
  )
  
  if (confirmed) {
    handleDelete()
  }
}

const handleDelete = async () => {
  deleting.value = true
  error.value = ''

  try {
    await deleteVoucher(Number(route.params.id))
    navigateTo('/admin/vouchers')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to delete voucher'
  } finally {
    deleting.value = false
  }
}
</script>
