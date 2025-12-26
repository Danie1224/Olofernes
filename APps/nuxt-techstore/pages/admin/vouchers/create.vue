<template>
  <div class="max-w-4xl mx-auto">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Voucher Details</h1>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
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
          <p class="text-sm text-gray-500 mt-2">Must be unique. Use uppercase letters and numbers.</p>
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
          <p class="text-sm text-gray-500 mt-2">Optional. Helps you remember the voucher's purpose.</p>
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
          <p class="text-sm text-gray-500 mt-2">Total times this voucher can be used across all users.</p>
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
        <div class="mb-8">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Status <span class="text-red-500">*</span>
          </label>
          <select
            v-model="form.status"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
          >
            <option value="" disabled>-- Select Status --</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        <!-- Error Message -->
        <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
          {{ error }}
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
          <NuxtLink 
            to="/admin/vouchers"
            class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium"
          >
            Cancel
          </NuxtLink>
          <button
            type="submit"
            :disabled="processing"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center gap-2"
          >
            <span v-if="!processing">✨</span>
            <span>{{ processing ? 'Creating...' : 'Create & Distribute Voucher' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { createVoucher } = useVouchers()

const form = reactive({
  code: '',
  description: '',
  discount_type: 'percentage',
  discount_value: 0,
  usage_limit: null as number | null,
  start_date: '',
  end_date: '',
  status: ''
})

const processing = ref(false)
const error = ref('')

const handleSubmit = async () => {
  processing.value = true
  error.value = ''

  try {
    // Prepare the payload
    const payload: any = {
      code: form.code.toUpperCase(),
      description: form.description || null,
      discount_type: form.discount_type as 'fixed' | 'percentage',
      discount_value: form.discount_value,
      usage_limit: form.usage_limit || null,
      usage_count: 0,
      start_date: form.start_date,
      end_date: form.end_date,
      status: form.status
    }

    await createVoucher(payload)
    navigateTo('/admin/vouchers')
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to create voucher'
  } finally {
    processing.value = false
  }
}
</script>
