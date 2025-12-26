<template>
  <div class="max-w-6xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
      <span>🛒</span> Shopping Cart
    </h1>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="cartItems.length === 0" class="text-center py-12">
      <div class="text-6xl mb-4">🛒</div>
      <p class="text-gray-600 mb-4">Your cart is empty</p>
      <NuxtLink to="/products" class="inline-block bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700">
        Continue Shopping
      </NuxtLink>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-lg shadow">
          <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <input 
                type="checkbox"
                :checked="selectedItems.length === cartItems.length && cartItems.length > 0"
                @change="toggleSelectAll"
                class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500 cursor-pointer"
              />
              <h2 class="text-lg font-semibold">
                Cart Items ({{ cartCount }})
                <span v-if="selectedItems.length > 0" class="text-sm font-normal text-gray-600 ml-2">
                  ({{ selectedItems.length }} selected)
                </span>
              </h2>
            </div>
          </div>
          
          <div class="divide-y divide-gray-100">
            <div 
              v-for="item in cartItems" 
              :key="item.cart_id" 
              class="p-4 flex items-center gap-4"
              :class="{ 'bg-indigo-50': selectedItems.includes(item.cart_id) }"
            >
              <!-- Checkbox -->
              <div class="flex-shrink-0">
                <input 
                  type="checkbox"
                  :checked="selectedItems.includes(item.cart_id)"
                  @change="toggleSelectItem(item.cart_id)"
                  class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500 cursor-pointer"
                />
              </div>
              <!-- Product Image -->
              <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <img
                  v-if="item.product?.image"
                  :src="getImageUrl(item.product.image)"
                  :alt="item.product?.name"
                  class="max-h-full max-w-full object-contain"
                  @error="handleImageError"
                />
                <span v-else class="text-gray-400 text-xs">No Image</span>
              </div>

              <!-- Product Info -->
              <div class="flex-1">
                <h3 class="font-medium text-gray-900">{{ item.product?.name }}</h3>
                <p class="text-sm text-gray-500">Price: ${{ formatPrice(item.product?.price || 0) }}</p>
              </div>

              <!-- Quantity Controls -->
              <div class="flex items-center gap-2">
                <button
                  @click="decreaseQuantity(item)"
                  class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-100"
                  :disabled="item.quantity <= 1 || updating === item.cart_id"
                >
                  -
                </button>
                <span class="w-10 text-center font-medium">{{ item.quantity }}</span>
                <button
                  @click="increaseQuantity(item)"
                  class="w-8 h-8 rounded border border-gray-300 flex items-center justify-center hover:bg-gray-100"
                  :disabled="updating === item.cart_id"
                >
                  +
                </button>
              </div>

              <!-- Item Total -->
              <div class="text-right">
                <p class="font-semibold text-primary-600">${{ formatPrice((item.product?.price || 0) * item.quantity) }}</p>
              </div>

              <!-- Remove Button -->
              <button
                @click="handleRemove(item.cart_id)"
                class="text-red-500 hover:text-red-700 p-2"
                :disabled="removing === item.cart_id"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="bg-white rounded-lg shadow p-6 h-fit sticky top-4">
        <h2 class="text-lg font-bold mb-4 pb-3 border-b border-gray-200">Order Summary</h2>
        
        <div class="space-y-3">
          <div class="flex justify-between text-gray-600">
            <span>Subtotal ({{ selectedItems.length || cartCount }} items)</span>
            <span>${{ formatPrice(selectedTotal) }}</span>
          </div>
          <div class="flex justify-between text-gray-600">
            <span>Shipping</span>
            <span>$10.00</span>
          </div>
          <div class="flex justify-between font-bold text-lg pt-3 border-t border-gray-200">
            <span>Total</span>
            <span>${{ formatPrice(selectedTotal + 10) }}</span>
          </div>
        </div>

        <button
          @click="handleCheckoutSelected"
          :disabled="selectedItems.length === 0"
          class="block w-full bg-emerald-500 text-white text-center py-3 rounded-md hover:bg-emerald-600 mt-6 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Proceed to Checkout{{ selectedItems.length > 0 ? ` (${selectedItems.length})` : '' }}
        </button>

        <div v-if="selectedItems.length === 0" class="text-sm text-amber-600 text-center mt-2">
          ⚠️ Please select at least one item
        </div>

        <NuxtLink
          to="/products"
          class="block w-full text-center text-primary-600 hover:underline mt-3"
        >
          Continue Shopping
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { cartItems, cartTotal, cartCount, loading, fetchCart, updateQuantity, removeFromCart } = useCart()

const updating = ref<number | null>(null)
const removing = ref<number | null>(null)
const selectedItems = ref<number[]>([])

const selectedTotal = computed(() => {
  return cartItems.value
    .filter(item => selectedItems.value.includes(item.cart_id))
    .reduce((sum, item) => sum + ((item.product?.price || 0) * item.quantity), 0)
})

const placeholderImage = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="10">No Image</text></svg>')

const getImageUrl = (url?: string) => {
  if (!url || url === '') return placeholderImage
  if (url.startsWith('storage/')) {
    return `http://localhost:8000/${url}`
  }
  return url
}

const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = placeholderImage
}

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-US', { minimumFractionDigits: 2 }) || '0.00'
}

const toggleSelectAll = () => {
  if (selectedItems.value.length === cartItems.value.length) {
    selectedItems.value = []
  } else {
    selectedItems.value = cartItems.value.map(item => item.cart_id)
  }
}

const toggleSelectItem = (cartId: number) => {
  const index = selectedItems.value.indexOf(cartId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  } else {
    selectedItems.value.push(cartId)
  }
}

const handleCheckoutSelected = () => {
  if (selectedItems.value.length === 0) {
    return
  }
  
  // Store selected cart IDs in sessionStorage to pass to checkout
  sessionStorage.setItem('selectedCartItems', JSON.stringify(selectedItems.value))
  navigateTo('/checkout')
}

const increaseQuantity = async (item: any) => {
  updating.value = item.cart_id
  try {
    await updateQuantity(item.cart_id, item.quantity + 1)
  } finally {
    updating.value = null
  }
}

const decreaseQuantity = async (item: any) => {
  if (item.quantity <= 1) return
  updating.value = item.cart_id
  try {
    await updateQuantity(item.cart_id, item.quantity - 1)
  } finally {
    updating.value = null
  }
}

const handleRemove = async (cartId: number) => {
  // Remove from selection if selected
  const index = selectedItems.value.indexOf(cartId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  }
  
  removing.value = cartId
  try {
    await removeFromCart(cartId)
  } finally {
    removing.value = null
  }
}

onMounted(async () => {
  await fetchCart()
  
  // Auto-select all items by default
  selectedItems.value = cartItems.value.map(item => item.cart_id)
})

// Watch for cart changes and update selection
watch(cartItems, (newItems) => {
  // Remove selected items that no longer exist in cart
  selectedItems.value = selectedItems.value.filter(id => 
    newItems.some(item => item.cart_id === id)
  )
}, { deep: true })
</script>
