<template>
  <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Create Return Request</h1>
      <NuxtLink to="/returns" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
        Back to Returns
      </NuxtLink>
    </div>

    <!-- Error Messages -->
    <div v-if="error" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
      <strong>⚠️ Error:</strong> {{ error }}
    </div>

    <!-- Success Message -->
    <div v-if="success" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
      <strong>✓ Success:</strong> {{ success }}
    </div>

    <!-- Step 1: Select Order -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
        📦 Step 1: Select Order
      </h3>
      
      <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-2">
          Order to Return <span class="text-red-500">*</span>
        </label>
        <select
          v-model="form.order_id"
          required
          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-gray-900"
          @change="loadOrderDetails"
        >
          <option value="">-- Select an order to return --</option>
          <option v-for="order in eligibleOrders" :key="order.order_id" :value="order.order_id">
            Order #{{ order.order_id }} - ₱{{ formatPrice(order.total_price || 0) }} ({{ formatDate(order.created_at) }})
          </option>
        </select>
        <p class="text-sm text-gray-500 mt-2">Only delivered or completed orders can be returned</p>
      </div>
    </div>

    <!-- Step 2: Select Items (if order selected) -->
    <div v-if="selectedOrder && (selectedOrder.items?.length || selectedOrder.order_items?.length)" class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
        🛒 Step 2: Select Items to Return
      </h3>
      
      <div class="bg-gray-50 rounded-lg p-4">
        <div 
          v-for="item in (selectedOrder.items || selectedOrder.order_items)" 
          :key="item.order_item_id"
          class="bg-white rounded-lg p-4 mb-3 last:mb-0 border border-gray-200"
        >
          <div class="flex items-start gap-4">
            <!-- Checkbox -->
            <input 
              type="checkbox"
              :id="`item-${item.order_item_id}`"
              v-model="selectedItems"
              :value="item.order_item_id"
              class="w-5 h-5 mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
            />
            
            <!-- Item Info -->
            <div class="flex-1">
              <label :for="`item-${item.order_item_id}`" class="font-semibold text-gray-900 cursor-pointer">
                {{ item.product?.name || 'Product' }}
              </label>
              <div class="grid grid-cols-3 gap-4 mt-2 text-sm text-gray-600">
                <div>
                  <span class="font-semibold text-gray-800">Quantity:</span> {{ item.quantity }}
                </div>
                <div>
                  <span class="font-semibold text-gray-800">Price:</span> ₱{{ formatPrice(item.unit_price || 0) }}
                </div>
                <div>
                  <span class="font-semibold text-gray-800">Subtotal:</span> ₱{{ formatPrice((item.unit_price || 0) * item.quantity) }}
                </div>
              </div>
              
              <!-- Item-specific details (required when selected) -->
              <div v-if="selectedItems.includes(item.order_item_id)" class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Return Qty <span class="text-red-500">*</span></label>
                  <input
                    v-model.number="itemQuantities[item.order_item_id]"
                    type="number"
                    :min="1"
                    :max="item.quantity"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Reason <span class="text-red-500">*</span></label>
                  <select
                    v-model="itemReasons[item.order_item_id]"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white"
                  >
                    <option value="">-- Select --</option>
                    <option value="Damaged During Shipment">Damaged During Shipment</option>
                    <option value="Defective Product">Defective Product</option>
                    <option value="Wrong Item Received">Wrong Item Received</option>
                    <option value="Not as Described">Not as Described</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
                <div>
                  <label class="block text-xs font-medium text-gray-600 mb-1">Notes (optional)</label>
                  <input
                    v-model="itemNotes[item.order_item_id]"
                    type="text"
                    placeholder="Additional notes..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Step 3: Reason -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
        ✍️ Step 3: Reason for Return
      </h3>
      
      <p class="text-gray-600 mb-4">Please select reason and quantity for each item in Step 2 above.</p>
      <p class="text-sm text-gray-500">Each item requires a reason from: Damaged During Shipment, Defective Product, Wrong Item Received, Not as Described, or Other.</p>
    </div>

    <!-- Summary & Submit -->
    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
      <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b-2 border-indigo-500">
        📋 Summary
      </h3>
      
      <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <span class="text-gray-500">Order ID:</span>
            <span class="font-semibold text-gray-900 ml-2">{{ form.order_id ? `#${form.order_id}` : 'Not selected' }}</span>
          </div>
          <div>
            <span class="text-gray-500">Items to Return:</span>
            <span class="font-semibold text-gray-900 ml-2">{{ selectedItems.length || 0 }}</span>
          </div>
          <div>
            <span class="text-gray-500">Status:</span>
            <span class="font-semibold ml-2" :class="canSubmit ? 'text-green-600' : 'text-amber-600'">{{ canSubmit ? 'Ready to submit' : 'Please complete all fields' }}</span>
          </div>
          <div>
            <span class="text-gray-500">Estimated Refund:</span>
            <span class="font-semibold text-green-600 ml-2">₱{{ formatPrice(estimatedRefund) }}</span>
          </div>
        </div>
      </div>

      <div class="flex gap-4">
        <button
          type="button"
          @click="handleSubmit"
          :disabled="processing || !canSubmit"
          class="flex-1 px-6 py-3 text-white rounded-lg font-semibold transition-all disabled:opacity-50 disabled:cursor-not-allowed"
          style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"
        >
          {{ processing ? 'Submitting...' : 'Submit Return Request' }}
        </button>
        <NuxtLink 
          to="/returns"
          class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors"
        >
          Cancel
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
const router = useRouter()
const { orders, fetchOrders } = useOrders()
const { post } = useApi()

const form = reactive({
  order_id: route.query.order_id ? Number(route.query.order_id) : ''
})

const selectedItems = ref<number[]>([])
const itemReasons = ref<Record<number, string>>({})
const itemQuantities = ref<Record<number, number>>({})
const itemNotes = ref<Record<number, string>>({})
const processing = ref(false)
const error = ref('')
const success = ref('')

// Only show delivered/completed orders
const eligibleOrders = computed(() => {
  return orders.value.filter(o => 
    o.status === 'delivered' || o.status === 'completed'
  )
})

const selectedOrder = computed(() => {
  if (!form.order_id) return null
  return orders.value.find(o => o.order_id === Number(form.order_id))
})

const estimatedRefund = computed(() => {
  if (!selectedOrder.value) return 0
  if (selectedItems.value.length === 0) {
    return selectedOrder.value.total_price || 0
  }
  const items = selectedOrder.value.items || selectedOrder.value.order_items || []
  return items
    .filter((item: any) => selectedItems.value.includes(item.order_item_id || item.item_id))
    .reduce((sum: number, item: any) => sum + (item.unit_price || item.price || 0) * item.quantity, 0) || 0
})

const formatPrice = (price: number) => {
  return price.toLocaleString('en-US', { minimumFractionDigits: 2 })
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const loadOrderDetails = () => {
  selectedItems.value = []
  itemReasons.value = {}
  itemQuantities.value = {}
  itemNotes.value = {}
}

// Check if all selected items have required fields
const canSubmit = computed(() => {
  if (!form.order_id) return false
  if (selectedItems.value.length === 0) return false
  
  return selectedItems.value.every(id => {
    const qty = itemQuantities.value[id]
    const reason = itemReasons.value[id]
    return qty && qty > 0 && reason && reason.length > 0
  })
})

const handleSubmit = async () => {
  processing.value = true
  error.value = ''
  success.value = ''

  try {
    const items = selectedItems.value.map(id => ({
      order_item_id: id,
      quantity: itemQuantities.value[id] || 1,
      reason: itemReasons.value[id],
      notes: itemNotes.value[id] || ''
    }))

    await post('/return-requests', {
      order_id: Number(form.order_id),
      items: items
    })
    
    success.value = 'Return request submitted successfully!'
    setTimeout(() => {
      router.push('/returns')
    }, 1500)
  } catch (err: any) {
    error.value = err.data?.message || 'Failed to create return request'
  } finally {
    processing.value = false
  }
}

onMounted(async () => {
  await fetchOrders()
})
</script>
