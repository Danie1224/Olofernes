// composables/useBuyNow.ts
// Buy Now functionality for single product checkout

interface BuyNowItem {
  product_id: number
  name: string
  price: number
  quantity: number
  image?: string
}

export const useBuyNow = () => {
  const buyNowItem = useState<BuyNowItem | null>('buyNowItem', () => null)
  const isBuyNowMode = useState<boolean>('isBuyNowMode', () => false)

  const setBuyNowItem = (product: {
    product_id: number
    name: string
    price: number
    image?: string
  }, quantity: number = 1) => {
    buyNowItem.value = {
      product_id: product.product_id,
      name: product.name,
      price: product.price,
      quantity,
      image: product.image
    }
    isBuyNowMode.value = true
  }

  const clearBuyNow = () => {
    buyNowItem.value = null
    isBuyNowMode.value = false
  }

  const buyNowTotal = computed(() => {
    if (!buyNowItem.value) return 0
    return buyNowItem.value.price * buyNowItem.value.quantity
  })

  return {
    buyNowItem,
    isBuyNowMode,
    buyNowTotal,
    setBuyNowItem,
    clearBuyNow
  }
}
