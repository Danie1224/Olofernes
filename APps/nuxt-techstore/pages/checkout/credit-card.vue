<template>
  <div class="max-w-xl mx-auto">
    <!-- Back Button -->
    <button @click="handleBackToCheckout" class="text-primary-600 hover:underline mb-4 inline-block">
      ← Back to Checkout
    </button>

    <!-- Payment Card -->
    <div class="bg-white rounded-lg shadow p-8">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Credit Card Payment</h1>
      <p class="text-gray-500 mb-6">Enter your credit card details to complete the payment</p>

      <!-- Order Summary -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
          <div>
            <p class="font-semibold text-gray-900">Order Total</p>
            <p class="text-sm text-gray-500">Payment Amount</p>
          </div>
          <p class="text-2xl font-bold text-primary-600">${{ formatPrice(orderTotal) }}</p>
        </div>
        <p class="mt-3 text-sm text-gray-500">
          <span class="font-semibold text-gray-900">Payment Method:</span> Credit Card
        </p>
      </div>

      <!-- Credit Card Form -->
      <form @submit.prevent="handlePayment" class="space-y-5">
        <!-- Card Number -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            Card Number <span class="text-red-500">*</span>
          </label>
          <input
            v-model="cardNumber"
            type="text"
            placeholder="1234 5678 9012 3456"
            maxlength="19"
            required
            @input="formatCardNumber"
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none"
          />
          <p v-if="errors.cardNumber" class="text-red-500 text-sm mt-1">{{ errors.cardNumber }}</p>
        </div>

        <!-- Cardholder Name -->
        <div>
          <label class="block text-sm font-semibold text-gray-900 mb-2">
            Cardholder Name <span class="text-red-500">*</span>
          </label>
          <input
            v-model="cardholderName"
            type="text"
            placeholder="John Doe"
            required
            class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none"
          />
        </div>

        <!-- Expiry and CVV -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">
              Expiry Date <span class="text-red-500">*</span>
            </label>
            <input
              v-model="cardExpiry"
              type="text"
              placeholder="MM/YY"
              maxlength="5"
              required
              @input="formatExpiry"
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none"
            />
            <p v-if="errors.expiry" class="text-red-500 text-sm mt-1">{{ errors.expiry }}</p>
          </div>
          <div>
            <label class="block text-sm font-semibold text-gray-900 mb-2">
              CVV <span class="text-red-500">*</span>
            </label>
            <input
              v-model="cardCvv"
              type="text"
              placeholder="123"
              maxlength="4"
              required
              class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-primary-500 focus:outline-none"
            />
            <p v-if="errors.cvv" class="text-red-500 text-sm mt-1">{{ errors.cvv }}</p>
          </div>
        </div>

        <!-- Security Notice -->
        <div class="flex items-start gap-3 p-3 bg-green-50 rounded-lg">
          <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <p class="text-sm text-green-800">
            Your payment information is encrypted and secure. We never store your card details.
          </p>
        </div>

        <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-lg">
          {{ error }}
        </div>

        <button
          type="submit"
          :disabled="processing"
          class="w-full bg-primary-600 text-white py-4 rounded-lg font-semibold hover:bg-primary-700 disabled:opacity-50 transition-colors"
        >
          {{ processing ? 'Processing Payment...' : `Pay $${formatPrice(orderTotal)}` }}
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

const cardNumber = ref('')
const cardholderName = ref('')
const cardExpiry = ref('')
const cardCvv = ref('')
const processing = ref(false)
const error = ref('')
const errors = reactive({
  cardNumber: '',
  expiry: '',
  cvv: ''
})

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

const formatCardNumber = (e: Event) => {
  const input = e.target as HTMLInputElement
  let value = input.value.replace(/\D/g, '')
  value = value.replace(/(\d{4})/g, '$1 ').trim()
  cardNumber.value = value.substring(0, 19)
}

const formatExpiry = (e: Event) => {
  const input = e.target as HTMLInputElement
  let value = input.value.replace(/\D/g, '')
  if (value.length >= 2) {
    value = value.substring(0, 2) + '/' + value.substring(2, 4)
  }
  cardExpiry.value = value
}

const validateForm = () => {
  let isValid = true
  errors.cardNumber = ''
  errors.expiry = ''
  errors.cvv = ''
  
  const cleanCardNumber = cardNumber.value.replace(/\s/g, '')
  if (cleanCardNumber.length !== 16) {
    errors.cardNumber = 'Card number must be 16 digits'
    isValid = false
  }
  
  const expiryParts = cardExpiry.value.split('/')
  if (expiryParts.length !== 2 || expiryParts[0].length !== 2 || expiryParts[1].length !== 2) {
    errors.expiry = 'Invalid expiry format (MM/YY)'
    isValid = false
  }
  
  if (cardCvv.value.length < 3) {
    errors.cvv = 'CVV must be 3-4 digits'
    isValid = false
  }
  
  return isValid
}

const handlePayment = async () => {
  if (!validateForm()) return

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
      payment_method: 'credit_card',
      items: checkoutData.value.items || [],
      total: checkoutData.value.total || 0,
      vouchers: checkoutData.value.vouchers || [],
      card_last_four: cardNumber.value.replace(/\s/g, '').slice(-4)
    }

    console.log('Credit card checkout payload:', payload)

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
