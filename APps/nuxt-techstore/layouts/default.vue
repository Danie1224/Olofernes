<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <LayoutHeader />

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <LayoutFooter />
  </div>
</template>

<script setup lang="ts">
// Default layout
</script>
