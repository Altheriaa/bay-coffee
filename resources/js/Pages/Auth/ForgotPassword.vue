<template>
  <div class="min-h-screen bg-background flex">

    <!-- ── Left: Image Panel (hidden on mobile) ── -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-primary">
      <img
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBEwHc3IV0n2jDbBXV60Pw_8uTm65SHxgLgASuvFAoqGUafZCpa4YHWMxEP1EZEAYROMB8t_D2QOKFyfi8QmQPtLW1FJc3hK3JQF9OW5XFPwV711cOPstQpIhPLrKRrZGon5CW1_7uTYR2dnTMx-rYTu98IlmAVlvma4_sr87z_H9Be2de2MznMamJANH67hxiu0Mitr2MYOHgR3PfwVkFaJ_ht7EbnCFiMvPCOk41aS-G80kBC_oKAGjxz5khFvHLSPqqPJvnXaEI"
        alt="Coffee background"
        class="absolute inset-0 w-full h-full object-cover opacity-60"
      />
      <div class="absolute inset-0 bg-gradient-to-br from-primary/95 via-primary/80 to-primary/50"></div>
      <div class="relative z-10 flex flex-col justify-between p-12 h-full">
        <!-- Logo -->
        <Link href="/" class="flex items-center gap-2.5">
          <span class="font-bold text-xl text-on-primary tracking-tight" style="font-family:'Playfair Display',serif;">Bay &amp; Coffee</span>
        </Link>

        <!-- Quote -->
        <div>
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center">
              <span class="material-symbols-outlined text-on-secondary text-base" style="font-variation-settings:'FILL' 1">person</span>
            </div>
            <div>
              <p class="text-sm font-semibold text-on-primary">Bay &amp; Coffee</p>
              <p class="text-xs text-on-primary/60">Khairun Razikin</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── Right: Form Panel ── -->
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12">

      <!-- Mobile logo -->
      <div class="flex lg:hidden justify-center gap-2.5 mb-12">
        <Link href="/" class="font-bold text-2xl text-primary tracking-tight" style="font-family:'Playfair Display',serif;">Bay &amp; Coffee</Link>
      </div>

      <div class="w-full max-w-md mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-2xl sm:text-4xl font-bold text-primary mb-2">Lupa Password?</h1>
          <p class="text-xs font-bold uppercase tracking-widest text-secondary mb-2">Masukkan email Anda untuk menerima link reset password</p>
        </div>

        <!-- Form (No backend logic) -->
        <form @submit.prevent="submit" class="space-y-5">

          <!-- Email -->
            <div v-if="successMessage" class="rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700 text-center">
                {{ successMessage }}
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-primary mb-1.5">Email</label>
                <div class="relative">
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="nama@email.com"
                    autocomplete="email"
                    class="w-full bg-surface border border-outline-variant rounded-xl pl-4 py-3 text-sm text-on-surface placeholder-outline focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-200"
                />
                </div>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-on-primary font-semibold py-3.5 rounded-xl shadow-md shadow-primary/20 hover:shadow-lg text-sm"
                :disabled="form.processing"
            >
                <span class="material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1">send</span>
                {{ form.processing ? 'Mengirim...' : 'Kirim Link Reset' }}
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center gap-3 my-6">
          <div class="flex-grow h-px bg-outline-variant/40"></div>
          <span class="text-xs text-on-surface-variant font-medium">atau</span>
          <div class="flex-grow h-px bg-outline-variant/40"></div>
        </div>

        <!-- Register link -->
        <p class="text-center text-sm text-on-surface-variant">
          Masuk akun
          <Link href="/login" class="font-semibold text-secondary hover:underline">Login</Link>
        </p>

      </div>
    </div>

  </div>
</template>

<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();

const showPassword = ref(false);

const form = useForm({
    email: ''
});

const submit = () => {
    form.post('/forgot-password');
};
const successMessage = computed(() => page.props.flash?.success);


</script>