<template>
  <AppLayout>
    <section class="bg-primary py-10 sm:py-14">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-on-primary/50 mb-4">
          <Link href="/" class="hover:text-on-primary transition-colors duration-200">Home</Link>
          <span class="material-symbols-outlined text-xs">chevron_right</span>
          <span class="text-on-primary font-medium">Profil Saya</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-on-primary">Profil Saya</h1>
        <p class="text-sm text-on-primary/60 mt-1">Kelola data informasi diri, alamat, dan keamanan akun Anda</p>
      </div>
    </section>

    <section class="py-8 sm:py-12 bg-background">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
          
          <!-- SIDEBAR: AVATAR CARD & MENU TABS -->
          <div class="w-full lg:w-80 flex-shrink-0 flex flex-col gap-6">
            
            <!-- Loyalty & Avatar Card -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-5 text-center shadow-sm">
              <div class="w-20 h-20 mx-auto rounded-full bg-secondary/15 border-2 border-secondary flex items-center justify-center text-secondary font-bold text-2xl mb-4">
                {{ userInitials }}
              </div>
              <h3 class="text-base sm:text-lg font-bold text-primary">{{ profileForm.name }}</h3>
              <p class="text-xs text-on-surface-variant mb-4">{{ profileForm.email }}</p>
              
              <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary/10 text-secondary text-xs font-semibold mb-2">
                <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">stars</span>
                Member Eksklusif
              </div>
              <div class="bg-surface-container-low rounded-xl p-3 text-left mt-2 flex justify-between items-center">
                <div>
                  <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-wider">Poin Loyalitas</p>
                  <p class="text-sm font-extrabold text-primary">3,450 Poin</p>
                </div>
                <span class="material-symbols-outlined text-secondary">workspace_premium</span>
              </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-2 shadow-sm">
              <nav class="flex flex-col gap-1">
                <button v-for="menu in menus" :key="menu.id"
                  @click="activeMenu = menu.id"
                  class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-left transition-all duration-200"
                  :class="activeMenu === menu.id
                    ? 'bg-secondary/10 text-secondary'
                    : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low'"
                >
                  <span class="material-symbols-outlined text-lg" :style="activeMenu === menu.id ? 'font-variation-settings:\'FILL\' 1' : ''">{{ menu.icon }}</span>
                  <span class="flex-grow">{{ menu.label }}</span>
                  <span class="material-symbols-outlined text-xs text-outline/50">chevron_right</span>
                </button>
              </nav>
            </div>

          </div>

          <!-- DETAIL CONTENT PANEL -->
          <div class="flex-grow bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-5 sm:p-8 shadow-sm">
            
            <!-- PANEL: INFORMASI PROFIL -->
            <div v-show="activeMenu === 'profile'">
              <h2 class="text-lg sm:text-xl font-bold text-primary mb-1">Detail Informasi Diri</h2>
              <p class="text-xs sm:text-sm text-on-surface-variant mb-6 border-b border-outline-variant/20 pb-4">Pastikan data kontak Anda aktif untuk kelancaran pengiriman kopi.</p>
              
              <form @submit.prevent="saveProfile" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Nama Lengkap</label>
                    <input type="text" v-model="profileForm.name" required
                      class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Email</label>
                    <input type="email" v-model="profileForm.email" disabled
                      class="w-full bg-surface-container border border-outline-variant/20 rounded-xl px-4 py-3 text-sm text-outline cursor-not-allowed" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">No Handphone</label>
                    <input type="text" v-model="profileForm.no_hp" required
                      class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Tanggal Bergabung</label>
                    <input type="text" value="22 Mei 2026 (Baru)" disabled
                      class="w-full bg-surface-container border border-outline-variant/20 rounded-xl px-4 py-3 text-sm text-outline cursor-not-allowed" />
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Alamat Pengiriman Utama</label>
                  <textarea rows="3" v-model="profileForm.alamat" required
                    class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200 placeholder-outline"></textarea>
                </div>

                <div class="pt-4 border-t border-outline-variant/20 flex justify-end">
                  <button type="submit" :disabled="saving"
                    class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold px-6 py-3 rounded-xl transition-all duration-200 text-sm shadow-sm disabled:opacity-60">
                    <span class="material-symbols-outlined text-base">save</span>
                    {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- PANEL: UBAH PASSWORD -->
            <div v-show="activeMenu === 'security'">
              <h2 class="text-lg sm:text-xl font-bold text-primary mb-1">Ubah Password</h2>
              <p class="text-xs sm:text-sm text-on-surface-variant mb-6 border-b border-outline-variant/20 pb-4">Gunakan password yang kuat demi menjaga keamanan akun Anda.</p>
              
              <form @submit.prevent="changePassword" class="space-y-5">
                <div class="max-w-md space-y-5">
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Password Saat Ini</label>
                    <input type="password" v-model="securityForm.current_password" required
                      class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Password Baru</label>
                    <input type="password" v-model="securityForm.password" required
                      class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
                  </div>
                  <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Konfirmasi Password Baru</label>
                    <input type="password" v-model="securityForm.password_confirmation" required
                      class="w-full bg-surface border border-outline-variant/30 rounded-xl px-4 py-3 text-sm text-on-surface focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200" />
                  </div>
                </div>

                <div class="pt-4 border-t border-outline-variant/20 flex justify-end">
                  <button type="submit" :disabled="savingPassword"
                    class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary/90 text-on-secondary font-bold px-6 py-3 rounded-xl transition-all duration-200 text-sm shadow-sm disabled:opacity-60">
                    <span class="material-symbols-outlined text-base">lock_reset</span>
                    {{ savingPassword ? 'Mengubah...' : 'Perbarui Password' }}
                  </button>
                </div>
              </form>
            </div>

            <!-- PANEL: DAFTAR ALAMAT -->
            <div v-show="activeMenu === 'addresses'">
              <div class="flex justify-between items-center mb-1">
                <h2 class="text-lg sm:text-xl font-bold text-primary">Daftar Alamat Pengiriman</h2>
                <button @click="addAddress" class="inline-flex items-center gap-1 text-xs sm:text-sm text-secondary font-bold hover:underline">
                  <span class="material-symbols-outlined text-xs sm:text-sm">add_circle</span>
                  Tambah Alamat
                </button>
              </div>
              <p class="text-xs sm:text-sm text-on-surface-variant mb-6 border-b border-outline-variant/20 pb-4">Simpan beberapa alamat untuk mempermudah pesanan Anda.</p>
              
              <div class="space-y-4">
                <div class="border-2 border-secondary/35 bg-secondary/[0.02] rounded-2xl p-5 relative">
                  <span class="absolute top-4 right-4 bg-secondary text-on-secondary text-[10px] font-bold px-2 py-0.5 rounded-full">UTAMA</span>
                  <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-secondary text-lg">home</span>
                    <h4 class="font-bold text-sm sm:text-base text-primary">Alamat Rumah</h4>
                  </div>
                  <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-3 max-w-xl">{{ profileForm.alamat }}</p>
                  <p class="text-xs text-on-surface-variant font-medium">No Kontak: {{ profileForm.no_hp }}</p>
                </div>
                
                <div class="border border-outline-variant/30 rounded-2xl p-5 hover:border-outline transition-colors duration-200">
                  <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-outline text-lg">work</span>
                    <h4 class="font-bold text-sm sm:text-base text-primary">Alamat Kantor</h4>
                  </div>
                  <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed mb-3 max-w-xl">Gedung Astra Tower Lt. 18, Jl. Jend. Sudirman Kav. 5-6, Jakarta Pusat, DKI Jakarta 10220</p>
                  <p class="text-xs text-on-surface-variant font-medium">No Kontak: 081299998888</p>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AppLayout from '../Layouts/App.vue';

const page = usePage();
const authUser = page.props.auth.user || {};

const activeMenu = ref('profile');
const saving = ref(false);
const savingPassword = ref(false);

const menus = [
  { id: 'profile',   label: 'Informasi Profil', icon: 'account_circle' },
  { id: 'security',  label: 'Ubah Password',    icon: 'lock' },
  { id: 'addresses', label: 'Buku Alamat',      icon: 'map' },
];

// Profile form state populated with authenticated user props
const profileForm = ref({
  name: authUser.name || '',
  email: authUser.email || '',
  no_hp: authUser.no_hp || '',
  alamat: authUser.alamat || '',
});

const securityForm = ref({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const userInitials = computed(() => {
  const parts = profileForm.value.name.split(' ');
  if (parts.length >= 2) {
    return (parts[0][0] + parts[1][0]).toUpperCase();
  }
  return parts[0] ? parts[0][0].toUpperCase() : 'U';
});

function saveProfile() {
  saving.value = true;
  setTimeout(() => {
    saving.value = false;
    alert('Informasi profil berhasil diperbarui.');
  }, 1000);
}

function changePassword() {
  if (securityForm.value.password !== securityForm.value.password_confirmation) {
    alert('Konfirmasi password tidak cocok.');
    return;
  }
  savingPassword.value = true;
  setTimeout(() => {
    savingPassword.value = false;
    securityForm.value.current_password = '';
    securityForm.value.password = '';
    securityForm.value.password_confirmation = '';
    alert('Password berhasil diperbarui.');
  }, 1000);
}

function addAddress() {
  alert('Fitur tambah alamat akan segera hadir.');
}
</script>
