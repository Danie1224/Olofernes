<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else-if="cartItems.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      <p class="text-gray-600 mb-4">Your cart is empty</p>
      <NuxtLink to="/products" class="text-primary-600 hover:underline">
        Continue Shopping
      </NuxtLink>
    </div>

    <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
      <!-- Cart Items Header -->
      <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <input 
            type="checkbox"
            :checked="selectedItems.length === cartItems.length && cartItems.length > 0"
            @change="toggleSelectAll"
            class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500 cursor-pointer"
          />
          <h2 class="text-lg font-bold text-gray-900">
            Cart Items
            <span v-if="selectedItems.length > 0" class="text-sm font-normal text-gray-600">
              ({{ selectedItems.length }} selected)
            </span>
          </h2>
        </div>
      </div>

      <!-- Cart Items List -->
      <div class="divide-y divide-gray-200">
        <div 
          v-for="item in cartItems" 
          :key="item.cart_id"
          class="px-6 py-4 flex items-center gap-4"
          :class="{ 'bg-indigo-50': selectedItems.includes(item.cart_id) }"
        >
          <!-- Product Info -->
          <div class="flex-1">
            <h3 class="font-semibold text-gray-900">{{ item.product?.name }}</h3>
            <p class="text-green-600 font-medium">₱{{ formatPrice(item.product?.price || 0) }}</p>
          </div>

          <!-- Quantity Controls -->
          <div class="flex items-center gap-3">
            <button 
              @click="updateQty(item.cart_id, item.quantity - 1)"
              :disabled="item.quantity <= 1"
              class="w-10 h-10 bg-gray-700 text-white rounded flex items-center justify-center hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              -
            </button>
            <span class="w-8 text-center font-medium">{{ item.quantity }}</span>
            <button 
              @click="updateQty(item.cart_id, item.quantity + 1)"
              class="w-10 h-10 bg-gray-700 text-white rounded flex items-center justify-center hover:bg-gray-800"
            >
              +
            </button>
          </div>

          <!-- Item Total -->
          <div class="w-32 text-right">
            <span class="font-bold text-gray-900">₱{{ formatPrice((item.product?.price || 0) * item.quantity) }}</span>
          </div>

          <!-- Checkbox and Remove Button -->
          <div class="flex items-center gap-3">
            <input 
              type="checkbox"
              :checked="selectedItems.includes(item.cart_id)"
              @change="toggleSelectItem(item.cart_id)"
              class="w-5 h-5 text-indigo-600 rounded focus:ring-2 focus:ring-indigo-500 cursor-pointer"
            />
            <button 
              @click="removeItem(item.cart_id)"
              class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded hover:bg-red-600 transition-colors"
            >
              Remove
            </button>
          </div>
        </div>
      </div>

      <!-- Cart Footer -->
      <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-2">
            <span class="text-lg font-bold text-gray-900">Total:</span>
            <span class="text-xl font-bold text-green-600">₱{{ formatPrice(selectedTotal) }}</span>
            <span v-if="selectedItems.length > 0" class="text-sm text-gray-600">
              ({{ selectedItems.length }} item{{ selectedItems.length > 1 ? 's' : '' }})
            </span>
          </div>
          
          <div class="flex items-center gap-3">
            <button 
              @click="handleClearCart"
              class="px-6 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 transition-colors"
            >
              Clear Cart
            </button>
            <button
              @click="handleCheckoutSelected"
              :disabled="selectedItems.length === 0"
              class="px-6 py-2 bg-indigo-600 text-white font-medium rounded hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
            >
              <span>🛒</span>
              Checkout Selected ({{ selectedItems.length }})
            </button>
          </div>
        </div>
        
        <div v-if="selectedItems.length === 0" class="text-sm text-amber-600 flex items-center gap-2">
          <span>⚠️</span>
          <span>Please select at least one item to checkout</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { cartItems, cartTotal, loading, fetchCart, updateQuantity, removeFromCart, clearCart } = useCart()

const selectedItems = ref<number[]>([])

const selectedTotal = computed(() => {
  return cartItems.value
    .filter(item => selectedItems.value.includes(item.cart_id))
    .reduce((sum, item) => sum + ((item.product?.price || 0) * item.quantity), 0)
})

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

const updateQty = async (cartId: number, quantity: number) => {
  if (quantity < 1) return
  await updateQuantity(cartId, quantity)
}

const removeItem = async (cartId: number) => {
  // Remove from selection if selected
  const index = selectedItems.value.indexOf(cartId)
  if (index > -1) {
    selectedItems.value.splice(index, 1)
  }
  await removeFromCart(cartId)
}

const handleClearCart = async () => {
  if (confirm('Are you sure you want to clear your cart?')) {
    selectedItems.value = []
    await clearCart()
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
