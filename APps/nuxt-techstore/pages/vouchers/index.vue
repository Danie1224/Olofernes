<template>
  <div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
        <span class="text-4xl">💳</span> My Vouchers
      </h1>
      <p class="text-gray-600 mt-2">Claim and manage your discount vouchers</p>
    </div>

    <!-- Claim Voucher Section -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-8 rounded-xl mb-8 shadow-lg">
      <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
        🎁 Have a Voucher Code?
      </h2>
      <p class="mb-6 opacity-95">Enter your voucher code below to claim and start saving!</p>
      <div class="flex gap-3">
        <input
          v-model="claimCode"
          type="text"
          placeholder="ENTER VOUCHER CODE"
          class="flex-1 px-5 py-3 rounded-lg text-gray-900 uppercase font-medium placeholder:normal-case"
          @keyup.enter="claimVoucher"
        />
        <button
          @click="claimVoucher"
          :disabled="claiming || !claimCode.trim()"
          class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg hover:bg-gray-100 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ claiming ? 'Claiming...' : 'Claim Voucher' }}
        </button>
      </div>
    </div>

    <!-- Success/Error Messages -->
    <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 flex items-center gap-3">
      <span>✓</span>
      <span>{{ successMessage }}</span>
    </div>
    <div v-if="errorMessage" class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 flex items-center gap-3">
      <span>✕</span>
      <span>{{ errorMessage }}</span>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
    </div>

    <!-- Your Vouchers Section -->
    <div v-else>
      <!-- Available Vouchers to Claim -->
      <div v-if="availableToClaimVouchers.length > 0" class="mb-10">
        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
          <span>🎁</span> Available Vouchers ({{ availableToClaimVouchers.length }})
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="(voucher, index) in availableToClaimVouchers" 
            :key="voucher.voucher_id"
            class="bg-white rounded-xl shadow-md overflow-hidden border-2 border-dashed border-indigo-300 hover:shadow-xl transition-all"
          >
            <!-- Voucher Header -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-white">
              <div class="text-xl font-bold tracking-wider mb-2">{{ voucher.code }}</div>
              <div class="text-4xl font-bold mb-1">
                {{ voucher.discount_type === 'percentage' 
                  ? `${voucher.discount_value}%` 
                  : `₱${voucher.discount_value}` }}
              </div>
              <div class="text-sm opacity-90">Discount</div>
            </div>
            
            <!-- Voucher Body -->
            <div class="p-5 bg-gradient-to-br from-indigo-50 to-purple-50">
              <p class="text-gray-700 text-sm mb-4 min-h-[40px]">{{ voucher.description || 'No description' }}</p>
              
              <!-- Status Badge -->
              <div class="mb-4">
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-bold uppercase">
                  ⭐ UNCLAIMED
                </span>
              </div>
              
              <!-- Dates Grid -->
              <div class="grid grid-cols-2 gap-3 text-xs mb-4">
                <div>
                  <div class="flex items-center gap-1 text-gray-500 mb-1">
                    <span>📅</span> Valid From
                  </div>
                  <div class="font-semibold text-gray-900">{{ formatDate(voucher.start_date) }}</div>
                </div>
                <div>
                  <div class="flex items-center gap-1 text-gray-500 mb-1">
                    <span>⏰</span> Expires
                  </div>
                  <div class="font-semibold text-gray-900">{{ formatDate(voucher.end_date) }}</div>
                </div>
                <div>
                  <div class="flex items-center gap-1 text-gray-500 mb-1">
                    <span>💡</span> Type
                  </div>
                  <div class="font-semibold text-gray-900 capitalize">{{ voucher.discount_type || 'N/A' }}</div>
                </div>
              </div>
              
              <!-- Copy Code Button -->
              <div class="mb-3">
                <button
                  @click="copyCodeToClaim(voucher.code)"
                  class="w-full px-4 py-2 bg-white border-2 border-indigo-300 text-indigo-700 rounded-lg font-semibold hover:bg-indigo-50 transition-colors flex items-center justify-center gap-2"
                >
                  <span>{{ copiedCode === voucher.code ? '✓ Copied!' : '📋 Copy Code' }}</span>
                </button>
              </div>
              
              <!-- Claim Hint -->
              <p class="text-xs text-gray-500 text-center italic">Copy code and paste above to claim</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Claimed Vouchers -->
      <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <span>🎫</span> Your Claimed Vouchers ({{ userVouchers.length }})
      </h2>
      
      <div v-if="userVouchers.length === 0" class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
        <div class="text-5xl mb-4">🎟️</div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">No Claimed Vouchers Yet</h3>
        <p class="text-gray-600 mb-4">Claim vouchers from the available section above!</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="(voucher, index) in userVouchers" 
          :key="voucher?.user_voucher_id || index"
          class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:shadow-xl transition-all"
        >
          <!-- Voucher Header -->
          <div :class="[
            'p-6 text-white',
            getVoucherColor(index)
          ]">
            <div class="text-xl font-bold tracking-wider mb-2">{{ voucher?.voucher?.code || 'N/A' }}</div>
            <div class="text-4xl font-bold mb-1">
              {{ voucher?.voucher?.discount_type === 'percentage' 
                ? `${voucher?.voucher?.discount_value}%` 
                : `₱${voucher?.voucher?.discount_value}` }}
            </div>
            <div class="text-sm opacity-90">Discount</div>
          </div>
          
          <!-- Voucher Body -->
          <div class="p-5 bg-gray-50">
            <p class="text-gray-700 text-sm mb-4 min-h-[40px]">{{ voucher?.voucher?.description || 'No description' }}</p>
            
            <!-- Status Badge -->
            <div class="mb-4">
              <span v-if="voucher?.status === 'used'" class="inline-block px-3 py-1 bg-gray-200 text-gray-700 rounded text-xs font-bold uppercase">
                ✓ USED
              </span>
              <span v-else-if="voucher && getVoucherStatus(voucher) === 'coming-soon'" class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded text-xs font-bold uppercase">
                COMING SOON
              </span>
              <span v-else-if="voucher && getVoucherStatus(voucher) === 'active'" class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded text-xs font-bold uppercase flex items-center gap-1 w-fit">
                ✓ ACTIVE
              </span>
              <span v-else-if="voucher && getVoucherStatus(voucher) === 'expired'" class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded text-xs font-bold uppercase">
                EXPIRED
              </span>
              <span v-else class="inline-block px-3 py-1 bg-gray-200 text-gray-700 rounded text-xs font-bold uppercase">
                {{ voucher?.status || 'N/A' }}
              </span>
            </div>

            <!-- Expiry Warning -->
            <div v-if="voucher && getVoucherStatus(voucher) === 'active' && getDaysUntilExpiry(voucher?.voucher?.end_date) !== null" class="mb-4 p-2 bg-red-50 border border-red-200 rounded flex items-center gap-2 text-sm">
              <span>⏰</span>
              <span class="text-red-700 font-medium">Expires in {{ getDaysUntilExpiry(voucher?.voucher?.end_date) }} days</span>
            </div>
            
            <!-- Dates Grid -->
            <div class="grid grid-cols-2 gap-3 text-xs mb-4">
              <div>
                <div class="flex items-center gap-1 text-gray-500 mb-1">
                  <span>📅</span> Valid From
                </div>
                <div class="font-semibold text-gray-900">{{ formatDate(voucher?.voucher?.start_date) }}</div>
              </div>
              <div>
                <div class="flex items-center gap-1 text-gray-500 mb-1">
                  <span>⏰</span> Expires
                </div>
                <div class="font-semibold text-gray-900">{{ formatDate(voucher?.voucher?.end_date) }}</div>
              </div>
              <div>
                <div class="flex items-center gap-1 text-gray-500 mb-1">
                  <span>🎁</span> Claimed
                </div>
                <div class="font-semibold text-gray-900">{{ formatDate(voucher?.claimed_at) }}</div>
              </div>
              <div>
                <div class="flex items-center gap-1 text-gray-500 mb-1">
                  <span>💡</span> Type
                </div>
                <div class="font-semibold text-gray-900 capitalize">{{ voucher?.voucher?.discount_type || 'N/A' }}</div>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div v-if="voucher" class="flex gap-2">
              <button
                v-if="voucher?.status === 'used'"
                disabled
                class="flex-1 px-4 py-2 bg-gray-100 text-gray-500 rounded-lg font-semibold cursor-not-allowed"
              >
                Already Used
              </button>
              <button
                v-else-if="getVoucherStatus(voucher) === 'coming-soon'"
                disabled
                class="flex-1 px-4 py-2 bg-indigo-100 text-indigo-600 rounded-lg font-semibold flex items-center justify-center gap-2 cursor-not-allowed"
              >
                <span>⭐</span> Coming Soon
              </button>
              <template v-else-if="getVoucherStatus(voucher) === 'active'">
                <NuxtLink
                  :to="`/checkout?voucher=${voucher?.voucher?.code}`"
                  class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition-colors text-center flex items-center justify-center gap-2"
                >
                  <span>🎁</span> Use Now
                </NuxtLink>
                <button
                  @click="removeVoucher(voucher?.user_voucher_id)"
                  :disabled="removing === voucher?.user_voucher_id"
                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors disabled:opacity-50"
                  title="Remove voucher"
                >
                  🗑️
                </button>
              </template>
              <button
                v-else
                @click="removeVoucher(voucher?.user_voucher_id)"
                :disabled="removing === voucher?.user_voucher_id"
                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition-colors disabled:opacity-50"
              >
                {{ removing === voucher?.user_voucher_id ? 'Removing...' : '🗑️ Remove' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const { get, post, del } = useApi()

interface Voucher {
  voucher_id: number
  code: string
  description?: string
  discount_type: 'percentage' | 'fixed'
  discount_value: number
  start_date?: string
  end_date?: string
  status: string
}

interface UserVoucher {
  user_voucher_id: number
  voucher_id: number
  user_id: number
  status: 'available' | 'used' | 'expired'
  claimed_at: string
  used_at?: string
  voucher?: Voucher
}

const userVouchers = ref<UserVoucher[]>([])
const allVouchers = ref<Voucher[]>([])
const loading = ref(true)
const claimCode = ref('')
const claiming = ref(false)
const removing = ref<number | null>(null)
const copiedCode = ref('')
const successMessage = ref('')
const errorMessage = ref('')

// Computed: vouchers available to claim (not yet claimed by user)
const availableToClaimVouchers = computed(() => {
  const claimedVoucherIds = userVouchers.value.map(uv => uv.voucher_id)
  const now = new Date()
  
  return allVouchers.value.filter(v => {
    // Not claimed by user yet
    if (claimedVoucherIds.includes(v.voucher_id)) return false
    
    // Active status
    if (v.status !== 'active') return false
    
    // Within date range
    if (v.start_date && new Date(v.start_date) > now) return false
    if (v.end_date && new Date(v.end_date) < now) return false
    
    return true
  })
})

const voucherColors = ['bg-gradient-to-br from-orange-400 to-orange-500', 'bg-gradient-to-br from-indigo-500 to-purple-600', 'bg-gradient-to-br from-pink-500 to-rose-600', 'bg-gradient-to-br from-blue-500 to-cyan-600', 'bg-gradient-to-br from-green-500 to-emerald-600']

const getVoucherColor = (index: number) => {
  return voucherColors[index % voucherColors.length]
}

const formatDate = (date?: string) => {
  if (!date) return 'N/A'
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const getVoucherStatus = (userVoucher: UserVoucher) => {
  const now = new Date()
  const startDate = userVoucher.voucher?.start_date ? new Date(userVoucher.voucher.start_date) : null
  const endDate = userVoucher.voucher?.end_date ? new Date(userVoucher.voucher.end_date) : null

  if (startDate && now < startDate) {
    return 'coming-soon'
  }
  
  if (endDate && now > endDate) {
    return 'expired'
  }

  if (userVoucher.status === 'used') {
    return 'used'
  }

  if (userVoucher.status === 'available' && userVoucher.voucher?.status === 'active') {
    return 'active'
  }

  return userVoucher.status
}

const getDaysUntilExpiry = (endDate?: string) => {
  if (!endDate) return null
  const now = new Date()
  const expiry = new Date(endDate)
  const diff = expiry.getTime() - now.getTime()
  const days = Math.ceil(diff / (1000 * 60 * 60 * 24))
  return days > 0 ? days : 0
}

const copyCodeToClaim = async (code: string) => {
  await navigator.clipboard.writeText(code)
  copiedCode.value = code
  claimCode.value = code
  setTimeout(() => { copiedCode.value = '' }, 2000)
}

const claimVoucher = async () => {
  if (!claimCode.value.trim()) return
  
  claiming.value = true
  errorMessage.value = ''
  successMessage.value = ''
  
  try {
    await post('/vouchers/claim', { code: claimCode.value.toUpperCase() })
    successMessage.value = 'Voucher claimed successfully! 🎉'
    claimCode.value = ''
    await fetchVouchers()
    
    // Clear success message after 3 seconds
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error: any) {
    errorMessage.value = error.data?.message || 'Failed to claim voucher'
    
    // Clear error message after 5 seconds
    setTimeout(() => {
      errorMessage.value = ''
    }, 5000)
  } finally {
    claiming.value = false
  }
}

const removeVoucher = async (userVoucherId: number | undefined) => {
  if (!userVoucherId) {
    errorMessage.value = 'Invalid voucher ID'
    return
  }
  
  if (!confirm('Are you sure you want to remove this voucher?')) return
  
  removing.value = userVoucherId
  errorMessage.value = ''
  
  try {
    await del(`/user/vouchers/${userVoucherId}`)
    successMessage.value = 'Voucher removed successfully'
    await fetchVouchers()
    
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
  } catch (error: any) {
    errorMessage.value = error.data?.message || 'Failed to remove voucher'
    
    setTimeout(() => {
      errorMessage.value = ''
    }, 5000)
  } finally {
    removing.value = null
  }
}

const fetchVouchers = async () => {
  loading.value = true
  try {
    // Fetch user's claimed vouchers
    const userVouchersResponse = await get<UserVoucher[]>('/user/vouchers')
    userVouchers.value = userVouchersResponse || []
    
    // Fetch all active vouchers
    const allVouchersResponse = await get<{ data: Voucher[] }>('/vouchers')
    allVouchers.value = allVouchersResponse.data || []
  } catch (error) {
    console.error('Failed to fetch vouchers:', error)
    userVouchers.value = []
    allVouchers.value = []
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchVouchers()
})
</script>
