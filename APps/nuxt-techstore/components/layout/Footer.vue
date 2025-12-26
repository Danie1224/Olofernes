<template>
  <footer class="bg-gray-800 text-white mt-auto">
    <div class="container mx-auto px-4 py-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Brand -->
        <div>
          <h3 class="text-xl font-bold mb-4">TechStore</h3>
          <p class="text-gray-400">Your one-stop shop for all things tech.</p>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li><NuxtLink to="/products" class="text-gray-400 hover:text-white">Products</NuxtLink></li>
            <li><NuxtLink to="/orders" class="text-gray-400 hover:text-white">My Orders</NuxtLink></li>
            <li><NuxtLink to="/vouchers" class="text-gray-400 hover:text-white">Vouchers</NuxtLink></li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <h4 class="font-semibold mb-4">Support</h4>
          <ul class="space-y-2">
            <li><NuxtLink to="/returns" class="text-gray-400 hover:text-white">Returns</NuxtLink></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Contact Us</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="font-semibold mb-4">Contact</h4>
          <p class="text-gray-400">Email: support@techstore.com</p>
          <p class="text-gray-400">Phone: (123) 456-7890</p>
        </div>
      </div>

      <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
        <p>&copy; {{ new Date().getFullYear() }} TechStore. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>
