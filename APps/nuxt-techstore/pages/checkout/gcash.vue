<template>
  <div class="max-w-xl mx-auto">
    <!-- Back Button -->
    <button @click="handleBackToCheckout" class="text-primary-600 hover:underline mb-4 inline-block">
      ← Back to Checkout
    </button>

    <!-- Payment Card -->
    <div class="bg-white rounded-lg shadow p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">GCash Payment</h1>
      <p class="text-gray-500 mb-6">Complete your payment using GCash</p>

      <!-- Order Summary -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
          <div>
            <p class="font-semibold text-gray-900">Order Total</p>
            <p class="text-sm text-gray-500">Payment Amount</p>
          </div>
          <p class="text-2xl font-bold text-blue-600">${{ formatPrice(orderTotal) }}</p>
        </div>
        <p class="mt-3 text-sm text-gray-500">
          <span class="font-semibold text-gray-900">Payment Method:</span> GCash
        </p>
      </div>

      <!-- GCash Instructions -->
      <div class="bg-blue-50 rounded-lg p-6 mb-6 text-center">
        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
          <span class="text-white text-2xl font-bold">G</span>
        </div>
        <h3 class="font-semibold text-gray-900 mb-2">Send payment to:</h3>
        <p class="text-2xl font-bold text-blue-600 mb-2">0917-123-4567</p>
        <p class="text-gray-600">TechStore Payment</p>
      </div>

      <!-- GCash Form -->
      <form @submit.prevent="handlePayment" class="space-y-5">
        <!-- GCash Number -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            Your GCash Number <span class="text-red-500">*</span>
          </label>
          <input
            v-model="gcashNumber"
            type="text"
            placeholder="09XX XXX XXXX"
            maxlength="13"
            required
            @input="formatPhoneNumber"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none"
          />
        </div>

        <!-- Reference Number -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            GCash Reference Number <span class="text-red-500">*</span>
          </label>
          <input
            v-model="referenceNumber"
            type="text"
            placeholder="Enter the reference number from GCash"
            required
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none"
          />
          <p class="text-sm text-gray-500 mt-1">You can find this in your GCash transaction history</p>
        </div>

        <!-- Instructions -->
        <div class="bg-yellow-50 rounded-lg p-4">
          <h4 class="font-semibold text-yellow-800 mb-2">📋 How to pay:</h4>
          <ol class="text-sm text-yellow-700 space-y-1 list-decimal list-inside">
            <li>Open your GCash app</li>
            <li>Select "Send Money"</li>
            <li>Enter the number above: 0917-123-4567</li>
            <li>Enter the exact amount: ${{ formatPrice(orderTotal) }}</li>
            <li>Complete the payment and copy the reference number</li>
            <li>Paste the reference number above and click "Confirm Payment"</li>
          </ol>
        </div>

        <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-lg">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="processing"
          class="w-full bg-blue-600 text-white py-4 rounded-lg font-semibold hover:bg-blue-700 disabled:opacity-50 transition-colors"
        >
          {{ processing ? 'Verifying Payment...' : 'Confirm Payment' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { post } = useApi()
const { clearCart } = useCart()

const gcashNumber = ref('')
const referenceNumber = ref('')
const processing = ref(false)
const error = ref('')

const orderTotal = ref(0)
const checkoutData = ref<any>(null)

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-US', { minimumFractionDigits: 2 }) || '0.00'
}

const handleBackToCheckout = () => {
  // Check if we're in Buy Now mode from sessionStorage
  const storedData = sessionStorage.getItem('checkoutData')
  if (storedData) {
    const data = JSON.parse(storedData)
    if (data.isBuyNow) {
      // Navigate back with mode parameter to preserve Buy Now context
      navigateTo('/checkout?mode=buynow')
      return
    }
  }
  // Default to regular checkout
  navigateTo('/checkout')
}

const formatPhoneNumber = (e: Event) => {
  const input = e.target as HTMLInputElement
  let value = input.value.replace(/\D/g, '')
  if (value.length > 4 && value.length <= 7) {
    value = value.substring(0, 4) + ' ' + value.substring(4)
  } else if (value.length > 7) {
    value = value.substring(0, 4) + ' ' + value.substring(4, 7) + ' ' + value.substring(7, 11)
  }
  gcashNumber.value = value
}

const handlePayment = async () => {
  if (!referenceNumber.value.trim()) {
    error.value = 'Please enter the GCash reference number'
    return
  }

  if (!checkoutData.value || !checkoutData.value.items || checkoutData.value.items.length === 0) {
    error.value = 'No items in checkout. Please go back and try again.'
    return
  }
  
  processing.value = true
  error.value = ''

  try {
    // Build the request payload properly
    const payload = {
      name: checkoutData.value.name || '',
      email: checkoutData.value.email || '',
      phone: checkoutData.value.phone || '',
      address: checkoutData.value.address || '',
      city: checkoutData.value.city || '',
      zip: checkoutData.value.zip || '',
      payment_method: 'gcash',
      items: checkoutData.value.items || [],
      total: checkoutData.value.total || 0,
      vouchers: checkoutData.value.vouchers || [],
      gcash_number: gcashNumber.value.replace(/\s/g, ''),
      gcash_reference: referenceNumber.value
    }

    console.log('GCash checkout payload:', payload)

    const response = await post<{ success: boolean; order_id: number; message: string }>('/checkout', payload)
    
    if (response.success) {
      // Clear cart if not buy now mode
      if (!checkoutData.value.isBuyNow) {
        await clearCart()
      }
      sessionStorage.removeItem('checkoutData')
      navigateTo(`/checkout/success?order_id=${response.order_id}`)
    } else {
      error.value = response.message || 'Payment verification failed'
    }
  } catch (err: any) {
    console.error('Payment error:', err)
    // Try to extract detailed validation errors
    if (err.data?.errors) {
      const errors = Object.values(err.data.errors).flat()
      error.value = errors.join(', ')
    } else {
      error.value = err.data?.message || 'Payment verification failed. Please try again.'
    }
  } finally {
    processing.value = false
  }
}

onMounted(() => {
  const storedData = sessionStorage.getItem('checkoutData')
  if (!storedData) {
    navigateTo('/checkout')
    return
  }
  
  checkoutData.value = JSON.parse(storedData)
  orderTotal.value = checkoutData.value.total || 0
})
</script>
