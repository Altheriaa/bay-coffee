<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/App.vue';

const categories = ['Single Origin', 'Blends', 'Decaf', 'Flavoured'];
const roastLevels = ['Light', 'Medium', 'Medium-Dark', 'Dark'];
const priceRanges = ['Di bawah Rp 150.000', 'Rp 150.000 – Rp 200.000', 'Rp 200.000 – Rp 300.000', 'Di atas Rp 300.000'];

const props = defineProps({
    products: Array,
    error: Object,
})

</script>

<template>
  <AppLayout>
    <!-- ===== PAGE HEADER ===== -->
    <section class="bg-primary py-12 sm:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-on-primary/50 mb-4">
          <Link href="/" class="hover:text-on-primary transition-colors duration-200">Home</Link>
          <span class="material-symbols-outlined text-xs">chevron_right</span>
          <span class="text-on-primary font-medium">Shop</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-on-primary">Our Coffee</h1>
        <p class="text-sm sm:text-base text-on-primary/65 mt-2 max-w-xl">Browse our curated selection of freshly roasted, single-origin and blended coffees.</p>
      </div>
    </section>

    <!-- ===== MAIN CONTENT ===== -->
    <section class="py-8 sm:py-12 lg:py-16 bg-background">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">

          <!-- SIDEBAR FILTERS -->
          <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-5 sm:p-6 sticky top-28">
              <h3 class="text-sm font-bold text-primary mb-5 flex items-center gap-2">
                <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">tune</span>
                Filters
              </h3>

              <!-- Category -->
              <div class="mb-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Category</h4>
                <div class="flex flex-col gap-2">
                  <label v-for="cat in categories" :key="cat" class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" class="w-4 h-4 rounded border-outline-variant text-secondary accent-secondary" />
                    <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors duration-200">{{ cat }}</span>
                  </label>
                </div>
              </div>

              <!-- Roast Level -->
              <div class="mb-6">
                <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Roast Level</h4>
                <div class="flex flex-col gap-2">
                  <label v-for="roast in roastLevels" :key="roast" class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="checkbox" class="w-4 h-4 rounded border-outline-variant text-secondary accent-secondary" />
                    <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors duration-200">{{ roast }}</span>
                  </label>
                </div>
              </div>

              <!-- Price Range -->
              <div>
                <h4 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3">Price Range</h4>
                <div class="flex flex-col gap-2">
                  <label v-for="range in priceRanges" :key="range" class="flex items-center gap-2.5 cursor-pointer group">
                    <input type="radio" name="price" class="w-4 h-4 border-outline-variant text-secondary accent-secondary" />
                    <span class="text-sm text-on-surface-variant group-hover:text-primary transition-colors duration-200">{{ range }}</span>
                  </label>
                </div>
              </div>
            </div>
          </aside>

          <!-- PRODUCT GRID AREA -->
          <div class="flex-grow">

            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-6">
              <p class="text-sm text-on-surface-variant">Showing <span class="font-semibold text-primary">{{ products.length }}</span> products</p>
              <div class="flex items-center gap-3">
                <select class="text-sm bg-surface-container-lowest border border-outline-variant/30 rounded-xl px-3 py-2 text-on-surface-variant focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200 cursor-pointer">
                  <option>Sort by: Featured</option>
                  <option>Price: Low to High</option>
                  <option>Price: High to Low</option>
                  <option>Rating</option>
                  <option>Newest</option>
                </select>
              </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-5">
              <div v-for="product in products" :key="product.product_name"
                class="group bg-surface-container-lowest rounded-xl sm:rounded-2xl overflow-hidden shadow-sm hover:shadow-md border border-outline-variant/20 flex flex-col transition-shadow duration-300"
              >
                <Link :href="`/shop/${product.id}`">
                    <div class="relative aspect-square overflow-hidden bg-surface-container-low">
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-surface-container to-surface-container-high">
                        <img loading="lazy" :src="product.foto_product ? `/storage/${product.foto_product}` : null" alt="" class="w-full h-full object-cover">
                    </div>
                    <span v-if="product.badge"
                        class="absolute top-2 sm:top-3 left-2 sm:left-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest px-2 sm:px-2.5 py-0.5 sm:py-1 rounded-full"
                        :class="product.badge === 'Best Seller' ? 'bg-secondary text-on-secondary' : 'bg-primary text-on-primary'"
                    >Badges</span>
                    </div>

                    <div class="p-3 sm:p-4 flex flex-col flex-grow">
                    <h4 class="font-bold text-xs sm:text-sm text-primary leading-snug mb-0.5">{{ product.nama_product }}</h4>
                    <p class="text-[10px] sm:text-xs text-on-surface-variant mb-3">26g</p>
                    <div class="mt-auto flex items-center justify-between">
                        <span class="font-bold text-sm sm:text-base text-primary">Rp. {{ product.harga }}</span>
                        <button class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-secondary/10 hover:bg-secondary text-secondary hover:text-on-secondary transition-all duration-200">
                        <span class="material-symbols-outlined text-sm sm:text-base" style="font-variation-settings:'FILL' 1">add_shopping_cart</span>
                        </button>
                    </div>
                    </div>
                </Link>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>

  </AppLayout>
</template>


