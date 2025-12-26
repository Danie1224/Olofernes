// composables/useCart.ts
// Cart operations

interface CartItem {
  cart_id: number
  customer_id: string
  product_id: number
  quantity: number
  product?: {
    product_id: number
    name: string
    price: number
    image?: string
  }
}

export const useCart = () => {
  const { get, post, put, del } = useApi()

  const cartItems = useState<CartItem[]>('cartItems', () => [])
  const loading = useState<boolean>('cartLoading', () => false)

  const cartTotal = computed(() => {
    return cartItems.value.reduce((total, item) => {
      const price = item.product?.price || 0
      return total + (price * item.quantity)
    }, 0)
  })

  const cartCount = computed(() => {
    return cartItems.value.reduce((count, item) => count + item.quantity, 0)
  })

  const fetchCart = async () => {
    loading.value = true
    try {
      console.log('Fetching cart...')
      const response = await get<{ data: CartItem[] } | CartItem[]>('/add-to-cart')
      console.log('Cart API response:', response)
      
      // Handle both paginated response {data: [...]} and direct array [...]
      if (Array.isArray(response)) {
        cartItems.value = response
      } else if (response && response.data) {
        cartItems.value = response.data
      } else {
        cartItems.value = []
      }
      
      console.log('Cart items after fetch:', cartItems.value)
      return cartItems.value
    } catch (error) {
      console.error('Failed to fetch cart:', error)
      cartItems.value = []
      return []
    } finally {
      loading.value = false
    }
  }

  const addToCart = async (productId: number, quantity: number = 1) => {
    loading.value = true
    try {
      console.log('Calling addToCart API:', { product_id: productId, quantity })
      const response = await post<any>('/add-to-cart', {
        product_id: productId,
        quantity,
      })
      console.log('AddToCart API response:', response)
      
      // Check if response indicates an error
      if (response?.message && !response?.cart_id && !response?.product_id) {
        throw new Error(response.message)
      }
      
      await fetchCart()
      return response
    } catch (error: any) {
      console.error('AddToCart API error:', error)
      // Extract error message from response if available
      const errorMessage = error?.data?.message || error?.message || 'Failed to add to cart'
      throw new Error(errorMessage)
    } finally {
      loading.value = false
    }
  }

  const updateQuantity = async (cartId: number, quantity: number) => {
    loading.value = true
    try {
      await put(`/add-to-cart/${cartId}`, { quantity })
      await fetchCart()
    } finally {
      loading.value = false
    }
  }

  const removeFromCart = async (cartId: number) => {
    loading.value = true
    try {
      await del(`/add-to-cart/${cartId}`)
      await fetchCart()
    } finally {
      loading.value = false
    }
  }

  const clearCart = async () => {
    loading.value = true
    try {
      // Remove all items
      for (const item of cartItems.value) {
        await del(`/add-to-cart/${item.cart_id}`)
      }
      cartItems.value = []
    } finally {
      loading.value = false
    }
  }

  return {
    cartItems,
    cartTotal,
    cartCount,
    loading,
    fetchCart,
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
  }
}
