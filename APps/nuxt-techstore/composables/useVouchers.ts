// composables/useVouchers.ts
// Voucher operations

interface Voucher {
  voucher_id: number
  code: string
  description?: string
  discount_type: 'percentage' | 'fixed'
  discount_value: number
  min_purchase?: number | null
  max_discount?: number | null
  usage_limit?: number | null
  used_count: number
  usage_count?: number
  distributed_count?: number
  status: 'active' | 'inactive' | 'expired'
  expires_at?: string
  start_date?: string
  end_date?: string
  created_at: string
}

export const useVouchers = () => {
  const { get, post, put, del } = useApi()

  const vouchers = useState<Voucher[]>('vouchers', () => [])
  const currentVoucher = useState<Voucher | null>('currentVoucher', () => null)
  const loading = useState<boolean>('vouchersLoading', () => false)

  const fetchVouchers = async (params?: Record<string, any>) => {
    loading.value = true
    try {
      const response = await get<{ data: Voucher[] }>('/vouchers', params)
      vouchers.value = response.data
      return response.data
    } finally {
      loading.value = false
    }
  }

  const fetchVoucher = async (id: number | string) => {
    loading.value = true
    try {
      const response = await get<{ data: Voucher }>(`/vouchers/${id}`)
      currentVoucher.value = response.data
      return response.data
    } finally {
      loading.value = false
    }
  }

  // Admin CRUD
  const createVoucher = async (data: Partial<Voucher>) => {
    loading.value = true
    try {
      const response = await post<{ data: Voucher }>('/vouchers', data)
      return response.data
    } finally {
      loading.value = false
    }
  }

  const updateVoucher = async (id: number, data: Partial<Voucher>) => {
    loading.value = true
    try {
      const response = await put<{ data: Voucher }>(`/vouchers/${id}`, data)
      return response.data
    } finally {
      loading.value = false
    }
  }

  const deleteVoucher = async (id: number) => {
    loading.value = true
    try {
      await del(`/vouchers/${id}`)
      await fetchVouchers()
    } finally {
      loading.value = false
    }
  }

  return {
    vouchers,
    currentVoucher,
    loading,
    fetchVouchers,
    fetchVoucher,
    createVoucher,
    updateVoucher,
    deleteVoucher,
  }
}
