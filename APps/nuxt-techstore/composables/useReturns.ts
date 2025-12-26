// composables/useReturns.ts
// Return request operations

interface ReturnRequestItem {
  return_item_id?: number
  item_id?: number
  return_request_id: number
  order_item_id: number
  quantity: number
  reason: string
  notes?: string
  unit_price?: number
  refund_amount?: number
  orderItem?: {
    unit_price?: number
    product?: {
      name: string
      sku?: string
    }
  }
  product?: {
    name: string
    sku?: string
  }
  order_item?: {
    product?: {
      name: string
    }
  }
}

interface ReturnRequest {
  request_id: number
  order_id: number
  user_id: number
  customer_id?: number
  status: 'pending' | 'approved' | 'rejected' | 'refunded'
  reason: string
  admin_notes?: string
  refund_amount?: number
  created_at: string
  updated_at: string
  return_items?: ReturnRequestItem[]
  items?: ReturnRequestItem[]
  customer?: {
    name: string
    email: string
    customer_id: number
  }
  order?: {
    order_id: number
    total_amount: number
    status?: string
    created_at?: string
  }
}

export const useReturns = () => {
  const { get, post, del } = useApi()

  const returns = useState<ReturnRequest[]>('returns', () => [])
  const currentReturn = useState<ReturnRequest | null>('currentReturn', () => null)
  const loading = useState<boolean>('returnsLoading', () => false)

  const fetchReturns = async (params?: Record<string, any>) => {
    loading.value = true
    try {
      const response = await get<any>('/return-requests', params)
      // Handle various response formats:
      // 1. { success: true, data: { data: [...] } } - paginated with wrapper
      // 2. { data: [...] } - paginated
      // 3. [...] - direct array
      let data: ReturnRequest[] = []
      if (Array.isArray(response)) {
        data = response
      } else if (response?.data?.data && Array.isArray(response.data.data)) {
        data = response.data.data
      } else if (response?.data && Array.isArray(response.data)) {
        data = response.data
      }
      // Filter out null values
      returns.value = data.filter((r): r is ReturnRequest => r !== null && r !== undefined)
      return returns.value
    } finally {
      loading.value = false
    }
  }

  const fetchReturn = async (id: number | string) => {
    loading.value = true
    try {
      const response = await get<any>(`/return-requests/${id}`)
      // Handle: { success: true, data: {...} } or { data: {...} } or direct object
      if (response?.data?.request_id) {
        currentReturn.value = response.data
      } else if (response?.request_id) {
        currentReturn.value = response
      } else {
        currentReturn.value = null
      }
      return currentReturn.value
    } finally {
      loading.value = false
    }
  }

  const createReturn = async (data: {
    order_id: number
    reason: string
    items: { order_item_id: number; quantity: number; reason: string }[]
  }) => {
    loading.value = true
    try {
      const response = await post<{ data: ReturnRequest }>('/return-requests', data)
      return response.data
    } finally {
      loading.value = false
    }
  }

  const cancelReturn = async (id: number) => {
    loading.value = true
    try {
      await del(`/return-requests/${id}`)
      await fetchReturns()
    } finally {
      loading.value = false
    }
  }

  // Admin actions
  const approveReturn = async (id: number, notes?: string) => {
    loading.value = true
    try {
      const response = await post<{ data: ReturnRequest }>(`/return-requests/${id}/approve`, { admin_notes: notes })
      return response.data
    } finally {
      loading.value = false
    }
  }

  const rejectReturn = async (id: number, notes?: string) => {
    loading.value = true
    try {
      const response = await post<{ data: ReturnRequest }>(`/return-requests/${id}/reject`, { admin_notes: notes })
      return response.data
    } finally {
      loading.value = false
    }
  }

  const refundReturn = async (id: number, notes?: string) => {
    loading.value = true
    try {
      const response = await post<{ data: ReturnRequest }>(`/return-requests/${id}/refund`, { admin_notes: notes })
      return response.data
    } finally {
      loading.value = false
    }
  }

  const fetchStats = async () => {
    try {
      const response = await get<{ data: any }>('/return-requests-stats')
      return response.data
    } catch (error) {
      return null
    }
  }

  return {
    returns,
    currentReturn,
    loading,
    fetchReturns,
    fetchReturn,
    createReturn,
    cancelReturn,
    approveReturn,
    rejectReturn,
    refundReturn,
    fetchStats,
  }
}
