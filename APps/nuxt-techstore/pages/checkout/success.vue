<template>
  <div class="max-w-xl mx-auto text-center py-12">
    <div class="bg-white rounded-lg shadow p-8">
      <!-- Success Icon -->
      <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>

      <h1 class="text-3xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
      <p class="text-gray-600 mb-6">Thank you for your purchase. Your order has been received and is being processed.</p>

      <!-- Order Details -->
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <p class="text-sm text-gray-500">Order Number</p>
        <p class="text-2xl font-bold text-primary-600">#{{ orderId }}</p>
      </div>

      <p class="text-gray-600 mb-6">
        A confirmation email has been sent to your email address. 
        You can track your order status in the "My Orders" section.
      </p>

      <!-- Action Buttons -->
      <div class="space-y-3">
        <NuxtLink
          to="/orders"
          class="block w-full bg-primary-600 text-white py-3 rounded-lg font-semibold hover:bg-primary-700 transition-colors"
        >
          View My Orders
        </NuxtLink>
        <NuxtLink
          to="/products"
          class="block w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors"
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

const route = useRoute()
const orderId = computed(() => route.query.order_id || 'N/A')
</script>
