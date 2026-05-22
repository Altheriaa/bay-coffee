<template>
  <AppLayout>
    <section class="bg-primary py-10 sm:py-14">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-on-primary/50 mb-4">
          <Link href="/" class="hover:text-on-primary transition-colors duration-200">Home</Link>
          <span class="material-symbols-outlined text-xs">chevron_right</span>
          <span class="text-on-primary font-medium">Riwayat Transaksi</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-on-primary">Riwayat Transaksi</h1>
        <p class="text-sm text-on-primary/60 mt-1">Pantau status pesanan dan riwayat pembelian kopi terbaik Anda</p>
      </div>
    </section>

    <section class="py-8 sm:py-12 bg-background min-h-[500px]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search & Tabs -->
        <div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-8 pb-4 border-b border-outline-variant/20">
          <!-- Status Tabs -->
          <div class="flex items-center gap-1.5 bg-surface-container-low p-1 rounded-xl w-full md:w-auto overflow-x-auto">
            <button v-for="tab in tabs" :key="tab.value"
              @click="activeTab = tab.value"
              class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap"
              :class="activeTab === tab.value
                ? 'bg-secondary text-on-secondary shadow-sm'
                : 'text-on-surface-variant hover:text-primary hover:bg-surface-container'"
            >
              {{ tab.label }}
              <span class="ml-1 text-[10px] px-1.5 py-0.5 rounded-full bg-black/10"
                :class="activeTab === tab.value ? 'bg-white/20' : ''">
                {{ getCountForTab(tab.value) }}
              </span>
            </button>
          </div>

          <!-- Search Bar -->
          <div class="relative w-full md:w-80">
            <input type="text" v-model="searchQuery" placeholder="Cari nomor invoice / produk..."
              class="w-full bg-surface-container-lowest border border-outline-variant/30 rounded-xl pl-10 pr-4 py-2.5 text-xs sm:text-sm text-on-surface placeholder-outline focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-lg">search</span>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredTransactions.length === 0" class="text-center py-16">
          <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-surface-container flex items-center justify-center">
            <span class="material-symbols-outlined text-outline/40 text-3xl">receipt_long</span>
          </div>
          <h3 class="text-base sm:text-lg font-bold text-primary mb-1">Tidak ada transaksi ditemukan</h3>
          <p class="text-xs sm:text-sm text-on-surface-variant mb-6">Coba ubah filter atau lakukan transaksi belanja kopi terlebih dahulu.</p>
          <Link href="/shop"
            class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary/90 text-on-secondary font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 text-xs sm:text-sm">
            <span class="material-symbols-outlined text-sm">storefront</span>
            Belanja Sekarang
          </Link>
        </div>

        <!-- Transactions List -->
        <div v-else class="space-y-6">
          <div v-for="tx in filteredTransactions" :key="tx.id"
            class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
            
            <!-- Header Card -->
            <div class="bg-surface-container-low px-4 sm:px-6 py-4 flex flex-wrap gap-4 items-center justify-between border-b border-outline-variant/20">
              <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">{{ tx.invoice }}</span>
                <span class="text-xs text-outline/80">·</span>
                <span class="text-xs text-on-surface-variant">{{ tx.date }}</span>
                <span class="text-xs text-outline/80">·</span>
                <!-- Status Badge -->
                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full"
                  :class="getStatusClass(tx.status)">
                  {{ tx.statusLabel }}
                </span>
              </div>
              
              <div class="flex items-center gap-1.5 text-xs text-outline">
                <span class="material-symbols-outlined text-sm">payments</span>
                Metode: <span class="font-semibold text-primary uppercase">{{ tx.paymentMethod }}</span>
              </div>
            </div>

            <!-- Items list -->
            <div class="p-4 sm:p-6 divide-y divide-outline-variant/10">
              <div v-for="item in tx.items" :key="item.name" class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                <div class="w-16 h-16 rounded-xl overflow-hidden bg-surface-container-low flex-shrink-0 flex items-center justify-center bg-gradient-to-br from-surface-container to-surface-container-high border border-outline-variant/10">
                  <span class="material-symbols-outlined text-outline/50" style="font-size:28px; font-variation-settings:'FILL' 1">{{ item.icon }}</span>
                </div>
                <div class="flex-grow min-w-0">
                  <h4 class="font-bold text-xs sm:text-sm text-primary leading-tight truncate">{{ item.name }}</h4>
                  <p class="text-[10px] sm:text-xs text-on-surface-variant mt-0.5">{{ item.weight }} · {{ item.qty }} barang x {{ formatRupiah(item.price) }}</p>
                </div>
                <div class="text-right">
                  <span class="font-bold text-xs sm:text-sm text-primary">{{ formatRupiah(item.price * item.qty) }}</span>
                </div>
              </div>
            </div>

            <!-- Summary & Actions -->
            <div class="bg-surface-container-lowest px-4 sm:px-6 py-4 flex flex-col sm:flex-row gap-4 items-center justify-between border-t border-outline-variant/10">
              <div>
                <p class="text-xs text-on-surface-variant">Total Pembayaran</p>
                <p class="text-lg font-extrabold text-primary leading-none mt-1">{{ formatRupiah(tx.total) }}</p>
              </div>

              <!-- Context Actions -->
              <div class="flex items-center gap-2.5 w-full sm:w-auto">
                <template v-if="tx.status === 'pending'">
                  <button @click="openPayment(tx)"
                    class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center gap-1.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold px-4 py-2.5 rounded-xl transition-all duration-200 text-xs shadow-sm">
                    <span class="material-symbols-outlined text-sm">payment</span>
                    Bayar Sekarang
                  </button>
                  <button @click="cancelTransaction(tx)"
                    class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center gap-1.5 bg-surface border border-error/30 hover:bg-error/5 text-error font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 text-xs">
                    <span class="material-symbols-outlined text-sm">cancel</span>
                    Batalkan
                  </button>
                </template>
                <template v-else-if="tx.status === 'selesai'">
                  <button @click="buyAgain(tx)"
                    class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center gap-1.5 bg-secondary/10 hover:bg-secondary hover:text-on-secondary text-secondary font-bold px-4 py-2.5 rounded-xl transition-all duration-200 text-xs">
                    <span class="material-symbols-outlined text-sm">replay</span>
                    Beli Lagi
                  </button>
                  <button @click="downloadInvoice(tx)"
                    class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center gap-1.5 bg-surface border border-outline-variant/40 hover:border-secondary text-on-surface-variant hover:text-secondary font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 text-xs">
                    <span class="material-symbols-outlined text-sm">download</span>
                    Unduh Invoice
                  </button>
                </template>
                <template v-else-if="tx.status === 'expired'">
                  <button @click="buyAgain(tx)"
                    class="flex-grow sm:flex-grow-0 inline-flex items-center justify-center gap-1.5 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold px-5 py-2.5 rounded-xl transition-all duration-200 text-xs">
                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                    Pesan Ulang
                  </button>
                </template>
              </div>
            </div>

          </div>
        </div>

      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '../Layouts/App.vue';

const activeTab = ref('all');
const searchQuery = ref('');

const tabs = [
  { label: 'Semua Transaksi', value: 'all' },
  { label: 'Belum Bayar',     value: 'pending' },
  { label: 'Selesai',         value: 'selesai' },
  { label: 'Kedaluwarsa',     value: 'expired' },
];

// Mock Transactions data for beautiful dynamic presentation
const transactions = ref([
  {
    id: 1,
    invoice: 'INV/20260522/BC/1089',
    date: '22 Mei 2026 · 10:30 WIB',
    status: 'pending',
    statusLabel: 'Menunggu Pembayaran',
    paymentMethod: 'Midtrans QRIS',
    total: 390000,
    items: [
      { name: 'Ethiopian Yirgacheffe', weight: '12oz · Light Roast', price: 180000, qty: 1, icon: 'coffee' },
      { name: 'House Blend – Medium',  weight: '16oz · Medium Roast', price: 210000, qty: 1, icon: 'coffee_maker' },
    ]
  },
  {
    id: 2,
    invoice: 'INV/20260518/BC/0981',
    date: '18 Mei 2026 · 14:15 WIB',
    status: 'selesai',
    statusLabel: 'Pesanan Selesai',
    paymentMethod: 'Bank Mandiri Transfer',
    total: 165000,
    items: [
      { name: 'Guatemala Antigua', weight: '12oz · Medium Roast', price: 165000, qty: 1, icon: 'local_cafe' }
    ]
  },
  {
    id: 3,
    invoice: 'INV/20260505/BC/0762',
    date: '05 Mei 2026 · 09:00 WIB',
    status: 'selesai',
    statusLabel: 'Pesanan Selesai',
    paymentMethod: 'GoPay',
    total: 575000,
    items: [
      { name: 'French Roast Dark',  weight: '16oz · Dark Roast',   price: 210000, qty: 2, icon: 'inventory_2' },
      { name: 'Guatemala Antigua',  weight: '12oz · Medium Roast', price: 165000, qty: 1, icon: 'local_cafe' }
    ]
  },
  {
    id: 4,
    invoice: 'INV/20260420/BC/0410',
    date: '20 April 2026 · 16:45 WIB',
    status: 'expired',
    statusLabel: 'Pembayaran Kedaluwarsa',
    paymentMethod: 'ShopeePay',
    total: 195000,
    items: [
      { name: 'Sumatra Mandheling', weight: '12oz · Dark Roast', price: 195000, qty: 1, icon: 'coffee' }
    ]
  }
]);

const filteredTransactions = computed(() => {
  return transactions.value.filter(tx => {
    // Status Filter
    if (activeTab.value !== 'all' && tx.status !== activeTab.value) {
      return false;
    }
    // Search Query Filter
    if (searchQuery.value) {
      const q = searchQuery.value.toLowerCase();
      const invoiceMatch = tx.invoice.toLowerCase().includes(q);
      const itemsMatch = tx.items.some(item => item.name.toLowerCase().includes(q));
      return invoiceMatch || itemsMatch;
    }
    return true;
  });
});

function getCountForTab(tabValue) {
  if (tabValue === 'all') return transactions.value.length;
  return transactions.value.filter(tx => tx.status === tabValue).length;
}

function getStatusClass(status) {
  switch (status) {
    case 'pending': return 'bg-yellow-500/15 text-yellow-600';
    case 'selesai': return 'bg-secondary/15 text-secondary';
    case 'expired': return 'bg-outline-variant/20 text-on-surface-variant/70';
    default:        return 'bg-outline-variant/30 text-outline';
  }
}

const formatRupiah = (value) => {
  return 'Rp ' + value.toLocaleString('id-ID');
};

// Simple actions feedback (functional placeholders)
function openPayment(tx) {
  alert(`Buka gerbang pembayaran untuk invoice ${tx.invoice}`);
}

function cancelTransaction(tx) {
  if (confirm(`Apakah Anda yakin ingin membatalkan transaksi ${tx.invoice}?`)) {
    tx.status = 'expired';
    tx.statusLabel = 'Pembayaran Dibatalkan';
  }
}

function buyAgain(tx) {
  alert(`Menambahkan ${tx.items.length} item dari invoice ${tx.invoice} ke keranjang belanja Anda.`);
}

function downloadInvoice(tx) {
  alert(`Unduh invoice PDF untuk transaksi ${tx.invoice}`);
}
</script>
