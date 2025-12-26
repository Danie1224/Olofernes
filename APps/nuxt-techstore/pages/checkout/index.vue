<template>
  <div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
      <span>🛒</span> {{ isBuyNowMode ? 'Buy Now Checkout' : 'Checkout' }}
    </h1>

    <div v-if="checkoutItems.length === 0" class="text-center py-12">
      <p class="text-gray-600 mb-4">{{ isBuyNowMode ? 'No product selected for purchase' : 'Your cart is empty' }}</p>
      <NuxtLink to="/products" class="text-primary-600 hover:underline">
        Continue Shopping
      </NuxtLink>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Left Column - Order Summary -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-bold mb-4 pb-3 border-b-2 border-cyan-400">Order Summary</h2>
        
        <!-- Cart Items -->
        <div class="space-y-4 mb-6">
          <div v-for="(item, index) in checkoutItems" :key="index" class="flex justify-between items-start">
            <div>
              <p class="font-medium text-gray-900">{{ item.name }}</p>
              <p class="text-sm text-gray-500">Qty: {{ item.quantity }} × ${{ formatPrice(item.price) }}</p>
            </div>
            <p class="font-semibold text-cyan-600">${{ formatPrice(item.price * item.quantity) }}</p>
          </div>
        </div>
        
        <!-- Apply Vouchers Section (only for cart checkout) -->
        <div v-if="!isBuyNowMode && checkoutItems.length > 0" class="border-t border-gray-100 pt-4 mb-6">
          <h3 class="font-semibold mb-3 flex items-center gap-2">
            <span>🎟️</span> Apply Vouchers
          </h3>
          <p class="text-sm text-gray-500 mb-3">Select a voucher per product to apply discounts</p>
          
          <div v-for="item in checkoutItems" :key="`voucher-${item.product_id}`" class="flex items-center gap-3 mb-2">
            <span class="text-sm font-medium min-w-[80px]">{{ item.name?.substring(0, 10) }}{{ (item.name?.length || 0) > 10 ? '...' : '' }}:</span>
            <select 
              v-model="itemVouchers[item.product_id]" 
              class="flex-1 px-3 py-2 border border-gray-300 rounded-md bg-white text-sm"
            >
              <option value="">No voucher</option>
              <option v-for="voucher in availableVouchers" :key="voucher.voucher_id" :value="voucher.voucher_id">
                {{ voucher.code }} - {{ voucher.discount_type === 'percentage' ? voucher.discount_value + '%' : '$' + voucher.discount_value }} off
              </option>
            </select>
          </div>
        </div>

        <!-- Apply Voucher for Buy Now -->
        <div v-if="isBuyNowMode && buyNowItem" class="border-t border-gray-100 pt-4 mb-6">
          <h3 class="font-semibold mb-3 flex items-center gap-2">
            <span>🎟️</span> Apply Voucher
          </h3>
          <select 
            v-model="buyNowVoucher" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white text-sm"
          >
            <option value="">No voucher</option>
            <option v-for="voucher in availableVouchers" :key="voucher.voucher_id" :value="voucher.voucher_id">
              {{ voucher.code }} - {{ voucher.discount_type === 'percentage' ? voucher.discount_value + '%' : '$' + voucher.discount_value }} off
            </option>
          </select>
        </div>
        
        <!-- Order Totals -->
        <div class="bg-gray-50 rounded-lg p-4">
          <div class="flex justify-between py-2 text-gray-600">
            <span>Subtotal:</span>
            <span>${{ formatPrice(subtotal) }}</span>
          </div>
          <div v-if="totalDiscount > 0" class="flex justify-between py-2 text-green-600">
            <span>Discount:</span>
            <span>-${{ formatPrice(totalDiscount) }}</span>
          </div>
          <div class="flex justify-between py-2 text-gray-600">
            <span>Shipping:</span>
            <span>$10.00</span>
          </div>
          <div class="flex justify-between py-3 border-t border-gray-200 font-bold text-lg mt-2">
            <span>Total:</span>
            <span>${{ formatPrice(finalTotal) }}</span>
          </div>
        </div>
      </div>

      <!-- Right Column - Customer Info -->
      <div class="bg-white rounded-lg shadow p-6 h-fit">
        <h2 class="text-lg font-bold mb-4 pb-3 border-b-2 border-cyan-400">Customer Info</h2>
        
        <form @submit.prevent="handleCheckout" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
            <input
              v-model="form.email"
              type="email"
              required
              :readonly="!!user"
              :class="[
                'w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500',
                user ? 'bg-gray-100 cursor-not-allowed' : ''
              ]"
            />
            <p v-if="user" class="text-xs text-gray-500 mt-1">Email is linked to your account and cannot be changed</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
            <input
              v-model="form.phone"
              type="tel"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
            <textarea
              v-model="form.address"
              required
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
            ></textarea>
          </div>
          
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
              <input
                v-model="form.city"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code <span class="text-red-500">*</span></label>
              <input
                v-model="form.zip"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-cyan-500"
              />
            </div>
          </div>
          
          <!-- Payment Method -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
            <select 
              v-model="form.payment_method" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500"
            >
              <option value="">Select Payment Method</option>
              <option value="cash">💵 Cash on Delivery</option>
              <option value="gcash">📱 GCash</option>
              <option value="credit_card">💳 Credit Card</option>
              <option value="paypal">🅿️ PayPal</option>
            </select>
          </div>

          <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded-md text-sm">
            {{ error }}
          </div>

          <button
            type="submit"
            :disabled="processing || !form.payment_method"
            class="w-full bg-emerald-500 text-white py-3 rounded-md hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed font-medium mt-4"
          >
            {{ processing ? 'Processing...' : 'Complete Purchase' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

interface Voucher {
  voucher_id: number
  code: string
  discount_type: string
  discount_value: number
}

interface CheckoutItem {
  product_id: number
  name: string
  price: number
  quantity: number
}

const route = useRoute()
const { cartItems, cartTotal, fetchCart, clearCart } = useCart()
const { buyNowItem, isBuyNowMode, buyNowTotal, clearBuyNow } = useBuyNow()
const { post, get } = useApi()
const { user } = useAuth()

const form = reactive({
  name: '',
  email: '',
  phone: '',
  address: '',
  city: '',
  zip: '',
  payment_method: ''
})

const itemVouchers = ref<Record<number, number | string>>({})
const buyNowVoucher = ref<number | string>('')
const availableVouchers = ref<Voucher[]>([])
const processing = ref(false)
const error = ref('')
const shippingFee = 10
const selectedCartIds = ref<number[]>([])

// Determine checkout items based on mode
const checkoutItems = computed<CheckoutItem[]>(() => {
  if (isBuyNowMode.value && buyNowItem.value) {
    return [{
      product_id: buyNowItem.value.product_id,
      name: buyNowItem.value.name,
      price: buyNowItem.value.price,
      quantity: buyNowItem.value.quantity
    }]
  }
  
  // Filter cart items by selected IDs if any are selected
  const itemsToCheckout = selectedCartIds.value.length > 0
    ? cartItems.value.filter(item => selectedCartIds.value.includes(item.cart_id))
    : cartItems.value
  
  return itemsToCheckout.map(item => ({
    product_id: item.product_id,
    name: item.product?.name || '',
    price: item.product?.price || 0,
    quantity: item.quantity
  }))
})

// Calculate subtotal based on mode
const subtotal = computed(() => {
  if (isBuyNowMode.value) {
    return buyNowTotal.value
  }
  
  // Calculate total only for selected items
  return checkoutItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
})

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-US', { minimumFractionDigits: 2 }) || '0.00'
}

const totalDiscount = computed(() => {
  let discount = 0
  
  if (isBuyNowMode.value && buyNowItem.value) {
    // Calculate discount for buy now item
    if (buyNowVoucher.value) {
      const voucher = availableVouchers.value.find(v => v.voucher_id === Number(buyNowVoucher.value))
      if (voucher) {
        const itemTotal = buyNowItem.value.price * buyNowItem.value.quantity
        if (voucher.discount_type === 'percentage') {
          discount += itemTotal * (voucher.discount_value / 100)
        } else {
          discount += voucher.discount_value
        }
      }
    }
  } else {
    // Calculate discount for checkout items (selected items only)
    for (const item of checkoutItems.value) {
      const voucherId = itemVouchers.value[item.product_id]
      if (voucherId) {
        const voucher = availableVouchers.value.find(v => v.voucher_id === Number(voucherId))
        if (voucher) {
          const itemTotal = item.price * item.quantity
          if (voucher.discount_type === 'percentage') {
            discount += itemTotal * (voucher.discount_value / 100)
          } else {
            discount += voucher.discount_value
          }
        }
      }
    }
  }
  return discount
})

const finalTotal = computed(() => {
  return Math.max(0, subtotal.value - totalDiscount.value + shippingFee)
})

const getVouchersArray = () => {
  const vouchers: Array<{ voucher_id: number; product_id: number }> = []
  
  if (isBuyNowMode.value && buyNowItem.value && buyNowVoucher.value) {
    vouchers.push({
      voucher_id: Number(buyNowVoucher.value),
      product_id: buyNowItem.value.product_id
    })
  } else {
    // Only include vouchers for checkout items (selected items)
    for (const item of checkoutItems.value) {
      const voucherId = itemVouchers.value[item.product_id]
      if (voucherId) {
        vouchers.push({
          voucher_id: Number(voucherId),
          product_id: item.product_id
        })
      }
    }
  }
  return vouchers
}

const handleCheckout = async () => {
  // Get items for checkout based on mode
  const items = checkoutItems.value.map(item => ({
    product_id: item.product_id,
    quantity: item.quantity,
    price: item.price
  }))
  
  // For credit card, gcash, paypal - redirect to payment page first
  if (['credit_card', 'gcash', 'paypal'].includes(form.payment_method)) {
    // Store checkout data in session storage
    sessionStorage.setItem('checkoutData', JSON.stringify({
      ...form,
      items,
      total: finalTotal.value,
      shipping: shippingFee,
      vouchers: getVouchersArray(),
      isBuyNow: isBuyNowMode.value
    }))
    
    // Redirect to payment page
    navigateTo(`/checkout/${form.payment_method.replace('_', '-')}`)
    return
  }
  
  // For cash on delivery, process directly
  await processOrder()
}

const processOrder = async () => {
  processing.value = true
  error.value = ''

  try {
    const items = checkoutItems.value.map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
      price: item.price
    }))
    
    const orderData = {
      name: form.name,
      email: form.email,
      phone: form.phone,
      address: form.address,
      city: form.city,
      zip: form.zip,
      payment_method: form.payment_method,
      items,
      total: finalTotal.value,
      shipping: shippingFee,
      vouchers: getVouchersArray()
    }

    const response = await post<{ success: boolean; order_id: number; message: string }>('/checkout', orderData)
    
    if (response.success) {
      // Clear cart only if not in buy now mode
      if (!isBuyNowMode.value) {
        await clearCart()
      }
      // Clear buy now state
      clearBuyNow()
      navigateTo(`/checkout/success?order_id=${response.order_id}`)
    } else {
      error.value = response.message || 'Checkout failed'
    }
  } catch (err: any) {
    console.error('Checkout error:', err)
    error.value = err.data?.message || 'Checkout failed. Please try again.'
  } finally {
    processing.value = false
  }
}

const fetchVouchers = async () => {
  try {
    const response = await get<any[]>('/user/vouchers')
    // Extract voucher details from UserVoucher response
    // Only include vouchers that are available (not used) and not expired
    const now = new Date()
    availableVouchers.value = response
      .filter(uv => {
        if (!uv.voucher || uv.status !== 'available') return false
        // Check if voucher is not expired
        const endDate = new Date(uv.voucher.end_date)
        return endDate >= now
      })
      .map(uv => uv.voucher) || []
  } catch (error) {
    console.error('Failed to fetch vouchers:', error)
  }
}

onMounted(async () => {
  // Check if we're in Buy Now mode from URL
  const mode = route.query.mode
  
  // Check for pending buy now from login redirect
  const pendingBuyNow = sessionStorage.getItem('pendingBuyNow')
  if (pendingBuyNow && mode === 'buynow') {
    try {
      const pending = JSON.parse(pendingBuyNow)
      const { setBuyNowItem } = useBuyNow()
      setBuyNowItem({
        product_id: pending.product_id,
        name: pending.name,
        price: pending.price,
        image: pending.image
      }, pending.quantity)
      sessionStorage.removeItem('pendingBuyNow')
    } catch (e) {
      console.error('Failed to restore pending buy now:', e)
    }
  }
  
  // Restore Buy Now mode from payment page return
  if (mode === 'buynow' && !isBuyNowMode.value) {
    const checkoutData = sessionStorage.getItem('checkoutData')
    if (checkoutData) {
      try {
        const data = JSON.parse(checkoutData)
        if (data.isBuyNow && data.items && data.items.length > 0) {
          const { setBuyNowItem } = useBuyNow()
          const item = data.items[0]
          setBuyNowItem({
            product_id: item.product_id,
            name: item.name || 'Product',
            price: item.price
          }, item.quantity)
        }
      } catch (e) {
        console.error('Failed to restore Buy Now mode from payment page:', e)
      }
    }
  }
  
  // Fetch cart only if not in buy now mode
  if (!isBuyNowMode.value) {
    await fetchCart()
    
    // Load selected cart items from sessionStorage
    const selectedItemsData = sessionStorage.getItem('selectedCartItems')
    if (selectedItemsData) {
      try {
        selectedCartIds.value = JSON.parse(selectedItemsData)
        // Clear after reading to avoid stale data
        sessionStorage.removeItem('selectedCartItems')
      } catch (e) {
        console.error('Failed to parse selected cart items:', e)
      }
    }
  }
  
  await fetchVouchers()
  
  // Auto-apply voucher from URL if provided
  const voucherCode = route.query.voucher as string
  if (voucherCode) {
    // Find the voucher by code
    const voucher = availableVouchers.value.find(v => v.code === voucherCode)
    if (voucher) {
      if (isBuyNowMode.value) {
        buyNowVoucher.value = voucher.voucher_id
      } else if (cartItems.value.length > 0) {
        // Apply to first cart item by default
        itemVouchers.value[cartItems.value[0].cart_id] = voucher.voucher_id
      }
    }
  }
  
  // Pre-fill user info if available
  if (user.value) {
    form.name = user.value.name || ''
    form.email = user.value.email || ''
  }
})

// Clear buy now mode when leaving the page
onBeforeUnmount(() => {
  // Only clear if navigating away (not to payment pages)
  const currentPath = route.path
  if (!currentPath.includes('/checkout/')) {
    clearBuyNow()
  }
})
</script>
