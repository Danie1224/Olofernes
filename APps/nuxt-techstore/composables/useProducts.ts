// composables/useProducts.ts
// Product fetching and operations

interface Product {
  product_id: number
  product_code?: string
  name: string
  description: string
  price: number
  stock_quantity: number
  category?: string
  brand_id?: number
  brand?: {
    brand_id: number
    name: string
  }
  chipset?: string
  memory?: string
  storage?: string
  image?: string
  image_url?: string
  created_at: string
  updated_at: string
}

interface ProductsResponse {
  data: Product[]
  meta?: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export const useProducts = () => {
  const { get } = useApi()

  const products = useState<Product[]>('products', () => [])
  const currentProduct = useState<Product | null>('currentProduct', () => null)
  const loading = useState<boolean>('productsLoading', () => false)

  const fetchProducts = async (params?: Record<string, any>) => {
    loading.value = true
    try {
      const response = await get<ProductsResponse>('/products', params)
      products.value = response.data
      return response
    } finally {
      loading.value = false
    }
  }

  const fetchProduct = async (id: number | string) => {
    loading.value = true
    try {
      // API returns product directly, not wrapped in { data: Product }
      const response = await get<Product>(`/products/${id}`)
      currentProduct.value = response
      return response
    } catch (error) {
      console.error('Failed to fetch product:', error)
      currentProduct.value = null
      return null
    } finally {
      loading.value = false
    }
  }

  return {
    products,
    currentProduct,
    loading,
    fetchProducts,
    fetchProduct,
  }
}
