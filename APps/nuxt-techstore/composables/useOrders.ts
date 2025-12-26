// composables/useOrders.ts
// Order operations

interface OrderItem {
  order_item_id: number
  order_id: number
  product_id: number
  quantity: number
  unit_price: number
  product?: {
    name: string
    image?: string
  }
}

interface Order {
  order_id: number
  customer_id: string
  total_price: number
  status: string
  payment_method: string
  payment_status?: string
  order_date?: string
  completed_at?: string
  created_at: string
  updated_at: string
  order_items?: OrderItem[]
  items?: OrderItem[]
  has_return_request?: boolean
}


// Composable for managing orders
export const useOrders = () => {
  const { get, post } = useApi()// Use the API composable

  const orders = useState<Order[]>('orders', () => [])// List of orders
  const currentOrder = useState<Order | null>('currentOrder', () => null)// Currently viewed order
  const loading = useState<boolean>('ordersLoading', () => false)


  // Fetch list of orders with optional params
  const fetchOrders = async (params?: Record<string, any>, forceRefresh = false) => {
    loading.value = true
    try {
      // Clear existing orders if force refresh
      if (forceRefresh) {
        orders.value = []
      }

      // Fetch orders from API
      const response = await get<any>('/orders', params)
      console.log('Orders API response:', response)
      
      // Handle various response formats
      if (Array.isArray(response)) {
        orders.value = response
      } else if (response.data && Array.isArray(response.data)) {
        orders.value = response.data
      } else if (response.data?.data && Array.isArray(response.data.data)) {
        // Paginated response: { data: { data: [...], current_page, ... } }
        orders.value = response.data.data
      } else {
        console.warn('Unexpected orders response format:', response)// Fallback to empty array
        orders.value = []
      }
      console.log('Orders loaded:', orders.value.length, orders.value)
      return orders.value
    } catch (error) {
      console.error('Error fetching orders:', error)// On error, clear orders
      orders.value = []
      return []
    } finally {
      loading.value = false
    }
  }


  // Fetch a single order by ID
  const fetchOrder = async (id: number | string) => {
    loading.value = true
    try {
      const response = await get<Order | { data: Order }>(`/orders/${id}`)
      // Handle both wrapped and direct response
      currentOrder.value = 'data' in response ? response.data : response
      return currentOrder.value
    } finally {
      loading.value = false
    }
  }


  // Mark an order as completed
  const completeOrder = async (orderId: number) => {
    loading.value = true
    try {
      const response = await post<{ data: Order }>(`/orders/${orderId}/complete`)
      return response.data
    } finally {
      loading.value = false
    }
  }


  // Get completion status of an order
  const getCompletionStatus = async (orderId: number) => {
    try {
      const response = await get<{ data: any }>(`/orders/${orderId}/completion-status`)
      return response.data
    } catch (error) {
      return null
    }
  }

  return {
    orders,
    currentOrder,
    loading,
    fetchOrders,
    fetchOrder,
    completeOrder,
    getCompletionStatus,
  }
}
