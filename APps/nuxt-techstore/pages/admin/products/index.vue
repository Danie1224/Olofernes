<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Products</h1>
      <NuxtLink 
        to="/admin/products/create"
        class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700"
      >
        + Add Product
      </NuxtLink>
    </div>

    <!-- Stock Filter Tabs -->
    <div class="mb-6 flex gap-2">
      <button
        @click="stockFilter = 'all'"
        :class="[
          'px-6 py-2 rounded-full font-medium transition-colors',
          stockFilter === 'all'
            ? 'bg-blue-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        All Products
      </button>
      <button
        @click="stockFilter = 'in-stock'"
        :class="[
          'px-6 py-2 rounded-full font-medium transition-colors',
          stockFilter === 'in-stock'
            ? 'bg-blue-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        In Stock
      </button>
      <button
        @click="stockFilter = 'low-stock'"
        :class="[
          'px-6 py-2 rounded-full font-medium transition-colors',
          stockFilter === 'low-stock'
            ? 'bg-blue-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        Low Stock
      </button>
      <button
        @click="stockFilter = 'out-of-stock'"
        :class="[
          'px-6 py-2 rounded-full font-medium transition-colors',
          stockFilter === 'out-of-stock'
            ? 'bg-blue-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        Out of Stock
      </button>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Product</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="product in filteredProducts" :key="product.product_id">
            <td class="px-6 py-4">
              <div class="flex items-center">
                <img 
                  :src="getImageUrl(product.image_url || product.image)" 
                  :alt="product.name"
                  class="w-10 h-10 rounded object-cover mr-3"
                  @error="handleImageError"
                />
                <span>{{ product.name }}</span>
              </div>
            </td>
            <td class="px-6 py-4">₱{{ formatPrice(product.price) }}</td>
            <td class="px-6 py-4">
              <span :class="getStockClass(product.stock_quantity)">
                {{ product.stock_quantity }}
              </span>
            </td>
            <td class="px-6 py-4">
              <div class="flex gap-2">
                <NuxtLink 
                  :to="`/admin/products/${product.product_id}/edit`"
                  class="text-blue-600 hover:text-blue-800"
                >
                  Edit
                </NuxtLink>
                <button 
                  @click="deleteProduct(product.product_id)"
                  class="text-red-600 hover:text-red-800"
                >
                  Delete
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <div v-if="filteredProducts.length === 0" class="text-center py-8 text-gray-500">
        No products found in this category
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

const { products, loading, fetchProducts } = useProducts()
const { del } = useApi()

const stockFilter = ref<'all' | 'in-stock' | 'low-stock' | 'out-of-stock'>('all')
const LOW_STOCK_THRESHOLD = 10

const placeholderImage = 'data:image/svg+xml,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300"><rect width="300" height="300" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="sans-serif" font-size="16">No Image</text></svg>')

const filteredProducts = computed(() => {
  if (stockFilter.value === 'all') {
    return products.value
  }
  
  return products.value.filter(product => {
    const stock = product.stock_quantity
    
    switch (stockFilter.value) {
      case 'in-stock':
        return stock > LOW_STOCK_THRESHOLD
      case 'low-stock':
        return stock > 0 && stock <= LOW_STOCK_THRESHOLD
      case 'out-of-stock':
        return stock === 0
      default:
        return true
    }
  })
})

const getStockClass = (stock: number) => {
  if (stock === 0) return 'text-red-600 font-semibold'
  if (stock <= LOW_STOCK_THRESHOLD) return 'text-orange-600 font-semibold'
  return 'text-green-600 font-semibold'
}

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
  
  // If it's a relative path, convert to Laravel backend URL
  return `http://localhost:8000/storage/products/${url}`
}

const handleImageError = (e: Event) => {
  const img = e.target as HTMLImageElement
  img.src = placeholderImage
}

const formatPrice = (price: number) => {
  return price.toLocaleString('en-PH', { minimumFractionDigits: 2 })
}

const deleteProduct = async (id: number) => {
  if (!confirm('Are you sure you want to delete this product?')) return
  
  try {
    await del(`/products/${id}`)
    await fetchProducts()
  } catch (error) {
    alert('Failed to delete product')
  }
}

onMounted(async () => {
  await fetchProducts()
})
</script>
