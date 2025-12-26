<template>
  <div class="max-w-xl mx-auto">
    <!-- Back Button -->
    <button @click="handleBackToCheckout" class="text-primary-600 hover:underline mb-4 inline-block">
      ← Back to Checkout
    </button>

    <!-- Payment Card -->
    <div class="bg-white rounded-lg shadow p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">PayPal Payment</h1>
      <p class="text-gray-500 mb-6">Complete your payment using PayPal</p>

      <!-- Order Summary -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
          <div>
            <p class="font-semibold text-gray-900">Order Total</p>
            <p class="text-sm text-gray-500">Payment Amount</p>
          </div>
          <p class="text-2xl font-bold text-blue-700">${{ formatPrice(orderTotal) }}</p>
        </div>
        <p class="mt-3 text-sm text-gray-500">
          <span class="font-semibold text-gray-900">Payment Method:</span> PayPal
        </p>
      </div>

      <!-- PayPal Button -->
      <div class="space-y-5">
        <!-- PayPal Logo -->
        <div class="bg-blue-900 rounded-lg p-6 text-center">
          <div class="text-3xl font-bold text-white mb-2">
            <span class="text-blue-300">Pay</span><span class="text-blue-100">Pal</span>
          </div>
          <p class="text-blue-200 text-sm">The safer, easier way to pay</p>
        </div>

        <!-- PayPal Email -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            PayPal Email <span class="text-red-500">*</span>
          </label>
          <input
            v-model="paypalEmail"
            type="email"
            placeholder="your@email.com"
            required
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-blue-500 focus:outline-none"
          />
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 rounded-lg p-4">
          <h4 class="font-semibold text-blue-800 mb-2">🔒 Secure Payment</h4>
          <p class="text-sm text-blue-700">
            You will be redirected to PayPal to complete your payment securely. 
            After payment, you will be returned to our site.
          </p>
        </div>

        <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-lg">
          {{ error }}
        </div>

        <button
          @click="handlePayment"
          :disabled="processing"
          class="w-full bg-yellow-400 text-blue-900 py-4 rounded-lg font-bold hover:bg-yellow-500 disabled:opacity-50 transition-colors flex items-center justify-center gap-2"
        >
          <span v-if="processing">Processing...</span>
          <span v-else>
            Pay with <span class="font-extrabold">PayPal</span>
          </span>
        </button>

        <p class="text-center text-sm text-gray-500">
          By clicking "Pay with PayPal", you agree to our Terms of Service and Privacy Policy.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { post } = useApi()
const { clearCart } = useCart()

const paypalEmail = ref('')
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

const handlePayment = async () => {
  if (!paypalEmail.value.trim()) {
    error.value = 'Please enter your PayPal email'
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
      payment_method: 'paypal',
      items: checkoutData.value.items || [],
      total: checkoutData.value.total || 0,
      vouchers: checkoutData.value.vouchers || [],
      paypal_email: paypalEmail.value
    }

    console.log('PayPal checkout payload:', payload)

    const response = await post<{ success: boolean; order_id: number; message: string }>('/checkout', payload)
    
    if (response.success) {
      // Clear cart if not buy now mode
      if (!checkoutData.value.isBuyNow) {
        await clearCart()
      }
      sessionStorage.removeItem('checkoutData')
      navigateTo(`/checkout/success?order_id=${response.order_id}`)
    } else {
      error.value = response.message || 'Payment failed'
    }
  } catch (err: any) {
    console.error('Payment error:', err)
    // Try to extract detailed validation errors
    if (err.data?.errors) {
      const errors = Object.values(err.data.errors).flat()
      error.value = (errors as string[]).join(', ')
    } else {
      error.value = err.data?.message || 'Payment failed. Please try again.'
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
