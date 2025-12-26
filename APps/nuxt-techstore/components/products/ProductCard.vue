<template>
  <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-gray-100 relative">
    <!-- Success Notification -->
    <Transition name="fade">
      <div 
        v-if="addSuccess" 
        class="absolute top-2 left-2 right-2 z-10 bg-green-500 text-white text-sm font-medium px-3 py-2 rounded-md shadow-lg flex items-center gap-2"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        Added to cart!
      </div>
    </Transition>
    
    <!-- Product Image -->
    <NuxtLink :to="`/products/${product.product_id}`">
      <div class="relative h-32 bg-gray-50 flex items-center justify-center overflow-hidden">
        <img 
          :src="getImageUrl(product.image_url || product.image)" 
          :alt="product.name"
          class="max-h-full max-w-full object-contain"
          @error="handleImageError"
        />
      </div>
    </NuxtLink>
    
    <div class="p-4">
      <!-- Product Name -->
      <NuxtLink :to="`/products/${product.product_id}`">
        <h3 class="font-bold text-base mb-1 hover:text-primary-600 line-clamp-1">
          {{ product.name }}
        </h3>
      </NuxtLink>
      
      <!-- Product Code -->
      <p class="text-gray-500 text-xs mb-1">{{ product.product_code || 'N/A' }}</p>
      
      <!-- Description -->
      <p class="text-gray-600 text-xs mb-2 line-clamp-2 min-h-[2rem]">
        {{ product.description || 'No description available' }}
      </p>
      
      <!-- Price -->
      <p class="text-lg font-bold text-gray-900 mb-1">
        ${{ formatPrice(product.price) }}
      </p>
      
      <!-- Stock -->
      <p class="text-xs mb-2" :class="product.stock_quantity > 0 ? 'text-gray-500' : 'text-red-500'">
        Stock: {{ product.stock_quantity }}
      </p>
      
      <!-- Category Badge -->
      <span 
        v-if="product.category"
        class="inline-block px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-700 mb-3"
      >
        {{ product.category }}
      </span>
      
      <!-- Action Buttons -->
      <div class="space-y-2">
        <NuxtLink 
          :to="`/products/${product.product_id}`"
          class="block w-full text-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded hover:bg-green-600 transition-colors"
        >
          View Details
        </NuxtLink>
        <button 
          v-if="product.stock_quantity > 0"
          @click.prevent="handleAddToCart"
          :disabled="adding"
          class="w-full px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded hover:bg-blue-600 disabled:opacity-50 transition-colors"
        >
          {{ adding ? 'Adding...' : 'Add to Cart' }}
        </button>
        <button 
          v-else
          disabled
          class="w-full px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded cursor-not-allowed"
        >
          Out of Stock
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Product {
  product_id: number
  product_code?: string
  name: string
  description: string
  price: number
  stock_quantity: number
  category?: string
  image?: string
  image_url?: string
}

const props = defineProps<{
  product: Product
}>()

const router = useRouter()
const { addToCart } = useCart()
const { isAuthenticated } = useAuth()
const adding = ref(false)
const addSuccess = ref(false)

const placeholderImage = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300"><rect width="300" height="300" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="16">No Image</text></svg>')

const getImageUrl = (url?: string) => {
  if (!url || url === '') return placeholderImage
  
  // If it's already a full URL, return as is
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url
  }
  
  // If it starts with /storage, convert to Laravel backend URL
  if (url.startsWith('/storage/')) {
    return `http://localhost:8000${url}`
  }
  
  // If it starts with storage/ (no leading slash), convert to Laravel backend URL
  if (url.startsWith('storage/')) {
    return `http://localhost:8000/${url}`
  }
  
  // If it's a relative path, convert to Laravel backend URL
  return `http://localhost:8000/storage/products/${url}`
}

const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = placeholderImage
}

const formatPrice = (price: number) => {
  return price?.toLocaleString('en-US', { minimumFractionDigits: 2 }) || '0.00'
}

const handleAddToCart = async () => {
  // Check if user is logged in
  if (!isAuthenticated.value) {
    router.push('/auth/login?redirect=/products')
    return
  }
  
  adding.value = true
  try {
    await addToCart(props.product.product_id, 1)
    addSuccess.value = true
    setTimeout(() => {
      addSuccess.value = false
    }, 3000)
  } catch (error: any) {
    console.error('Failed to add to cart:', error)
    const errorMessage = error?.message || 'Failed to add to cart. Please try again.'
    alert(errorMessage)
  } finally {
    adding.value = false
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
