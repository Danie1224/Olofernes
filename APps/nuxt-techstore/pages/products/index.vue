<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Products</h1>

    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <input
        v-model="searchQuery"
        type="text"
        placeholder="Search products..."
        class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
      />
      <select
        v-model="categoryFilter"
        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white min-w-[160px]"
      >
        <option value="">All Categories</option>
        <option value="Laptop">Laptop</option>
        <option value="Smartphone">Smartphone</option>
        <option value="Tablet">Tablet</option>
        <option value="Desktop">Desktop</option>
        <option value="Gaming">Gaming</option>
        <option value="Accessory">Accessory</option>
      </select>
      <select
        v-model="sortBy"
        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white min-w-[140px]"
      >
        <option value="">Sort by Name</option>
        <option value="name_asc">Name: A to Z</option>
        <option value="name_desc">Name: Z to A</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
      </select>
      <button 
        @click="applyFilters"
        class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors"
      >
        Filter
      </button>
    </div>

    <!-- Products Grid -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
      <p class="mt-4 text-gray-600">Loading products...</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
      <ProductsProductCard 
        v-for="product in paginatedProducts" 
        :key="product.product_id" 
        :product="product" 
      />
    </div>

    <div v-if="!loading && filteredProducts.length === 0" class="text-center py-12 text-gray-600">
      No products found matching your criteria.
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="flex items-center justify-start gap-2 mt-8">
      <button
        @click="currentPage = Math.max(1, currentPage - 1)"
        :disabled="currentPage === 1"
        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1"
      >
        ← Previous
      </button>
      
      <button
        v-for="page in visiblePages"
        :key="page"
        @click="currentPage = page"
        :class="[
          'w-10 h-10 rounded-md font-medium transition-colors',
          currentPage === page 
            ? 'bg-green-500 text-white' 
            : 'bg-green-400 text-white hover:bg-green-500'
        ]"
      >
        {{ page }}
      </button>
      
      <button
        @click="currentPage = Math.min(totalPages, currentPage + 1)"
        :disabled="currentPage === totalPages"
        class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1"
      >
        Next →
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
const { products, loading, fetchProducts } = useProducts()

const searchQuery = ref('')
const categoryFilter = ref('')
const sortBy = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

const filteredProducts = computed(() => {
  let result = [...products.value]

  // Filter by search
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(p => 
      p.name?.toLowerCase().includes(query) ||
      p.description?.toLowerCase().includes(query) ||
      p.product_code?.toLowerCase().includes(query)
    )
  }

  // Filter by category
  if (categoryFilter.value) {
    result = result.filter(p => p.category === categoryFilter.value)
  }

  // Sort
  if (sortBy.value === 'price_asc') {
    result.sort((a, b) => a.price - b.price)
  } else if (sortBy.value === 'price_desc') {
    result.sort((a, b) => b.price - a.price)
  } else if (sortBy.value === 'name_asc') {
    result.sort((a, b) => (a.name || '').localeCompare(b.name || ''))
  } else if (sortBy.value === 'name_desc') {
    result.sort((a, b) => (b.name || '').localeCompare(a.name || ''))
  }

  return result
})

const totalPages = computed(() => Math.ceil(filteredProducts.value.length / itemsPerPage))

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredProducts.value.slice(start, end)
})

const visiblePages = computed(() => {
  const pages: number[] = []
  const maxVisible = 5
  let start = Math.max(1, currentPage.value - Math.floor(maxVisible / 2))
  let end = Math.min(totalPages.value, start + maxVisible - 1)
  
  if (end - start + 1 < maxVisible) {
    start = Math.max(1, end - maxVisible + 1)
  }
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  return pages
})

const applyFilters = () => {
  currentPage.value = 1
}

// Reset to page 1 when filters change
watch([searchQuery, categoryFilter, sortBy], () => {
  currentPage.value = 1
})

onMounted(async () => {
  await fetchProducts()
})
</script>
