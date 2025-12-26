<template>
  <div>
    <h1 class="text-2xl font-bold mb-6">Customers</h1>

    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
    </div>

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Joined</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="customer in customers" :key="customer.id">
            <td class="px-6 py-4">#{{ customer.id }}</td>
            <td class="px-6 py-4">{{ customer.name }}</td>
            <td class="px-6 py-4">{{ customer.email }}</td>
            <td class="px-6 py-4 text-gray-500">{{ formatDate(customer.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script setup lang="ts">
definePageMeta({
  layout: 'admin',
  middleware: 'admin'
})

//Imports and Data State
const { get } = useApi()

const customers = ref<any[]>([])
const loading = ref(true)


//Format Date Function
const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('en-PH', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}


//Data Fetch Customers on Mount
onMounted(async () => {
  try {
    const response = await get<{ data: any[] }>('/customers')
    customers.value = response.data
  } finally {
    loading.value = false
  }
})
</script>
