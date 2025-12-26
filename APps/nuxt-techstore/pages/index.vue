<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20 -mx-4 -mt-8 px-4 mb-12">
      <div class="container mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to TechStore</h1>
        <p class="text-xl mb-8">Your one-stop shop for the latest technology products</p>
        <NuxtLink 
          to="/products" 
          class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
        >
          Shop Now
        </NuxtLink>
      </div>
    </section>

    <!-- Featured Products -->
    <section>
      <h2 class="text-2xl font-bold mb-6">Featured Products</h2>
      <div v-if="loading" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
        <p class="mt-4 text-gray-600">Loading products...</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <ProductsProductCard 
          v-for="product in products" 
          :key="product.product_id" 
          :product="product" 
        />
      </div>
      <div v-if="!loading && products.length === 0" class="text-center py-12 text-gray-600">
        No products available at the moment.
      </div>
      <div class="text-center mt-8">
        <NuxtLink 
          to="/products" 
          class="text-primary-600 hover:text-primary-700 font-semibold"
        >
          View All Products →
        </NuxtLink>
      </div>
    </section>

    <!-- Features Section -->
    <section class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h3 class="font-semibold text-lg mb-2">Quality Products</h3>
        <p class="text-gray-600">We offer only the best tech products from trusted brands.</p>
      </div>
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
        </div>
        <h3 class="font-semibold text-lg mb-2">Fast Delivery</h3>
        <p class="text-gray-600">Quick and reliable shipping to your doorstep.</p>
      </div>
      <div class="text-center p-6">
        <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
          </svg>
        </div>
        <h3 class="font-semibold text-lg mb-2">Secure Payment</h3>
        <p class="text-gray-600">Multiple payment options with secure transactions.</p>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { products, loading, fetchProducts } = useProducts()

onMounted(async () => {
  await fetchProducts()
})
</script>
