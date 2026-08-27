<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { 
  Copy, 
  Wallet, 
  TrendingUp, 
  Users, 
  Trophy, 
  Sparkles, 
  Award, 
  ShieldCheck, 
  Gift, 
  ArrowUpRight, 
  Check, 
  Package
} from '@lucide/vue';

const props = defineProps({
  referral_link: String,
  referral_links: Object,
  wallet: Object,
  binary_legs: Object,
  rewards: Array,
  packages: Array,
  steping_status: Object
});

const copySuccessMsg = ref('');

const copyToClipboard = (text) => {
  const urlToCopy = text || props.referral_link || props.referral_links?.auto || props.referral_links?.left || window.location.origin + '/register';
  navigator.clipboard.writeText(urlToCopy);
  copySuccessMsg.value = `Link Operasional berhasil disalin!`;
  setTimeout(() => {
    copySuccessMsg.value = '';
  }, 3000);
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};
</script>

<template>
  <Head title="Dashboard Sistem" />

  <AdminLayout>
    <div class="space-y-6">
      <!-- Copy Link Toast Alert Banner -->
      <div v-if="copySuccessMsg" class="p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 rounded-xl text-xs font-semibold flex items-center gap-2 animate-bounce">
        <Check class="w-4 h-4 text-emerald-500" />
        <span>{{ copySuccessMsg }}</span>
      </div>

      <!-- 1. Link Operasional Banner Card -->
      <div class="bg-[#f0f5ff] border border-indigo-100 rounded-3xl p-5 md:p-6 shadow-sm relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
          <div class="p-3 bg-[#e0e7ff] text-[#4f46e5] rounded-2xl shrink-0 hidden sm:block">
            <span class="text-xl font-bold">🔗</span>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="text-sm font-extrabold text-[#4f46e5] tracking-tight">Link Operasional Anda</h3>
              <span class="px-2 py-0.5 text-[9px] font-bold bg-[#e0e7ff] text-[#4f46e5] rounded-md">Auto Placement</span>
            </div>
            <p class="text-xs text-[#6366f1] mt-1 font-medium">Bagikan link ini untuk mendaftarkan mitra baru</p>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <button 
            @click="copyToClipboard(referral_link || referral_links?.auto || referral_links?.left)"
            class="px-5 py-2.5 bg-[#4f46e5] hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all flex items-center gap-2 cursor-pointer shadow-md"
          >
            <Copy class="w-4 h-4" />
            <span>Copy Link</span>
          </button>
        </div>
      </div>

      <!-- 2. Main Metrics Grid (Saldo Wallet, Total Bonus Cair, Perkembangan Kaki Binary) -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Saldo Wallet Card (DARK Indigo Card matching Gambar 2) -->
        <div class="bg-[#0d131d] text-white rounded-3xl p-6 relative overflow-hidden shadow-lg space-y-4 border border-slate-800">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
              <Wallet class="w-3.5 h-3.5 text-emerald-400" />
              SALDO WALLET
            </span>
            <span class="px-2.5 py-0.5 text-[10px] font-bold bg-emerald-500/10 text-emerald-400 rounded-full border border-emerald-500/20">
              VOUCHER Aktif: {{ wallet?.voucher_aktif || 2 }} Pcs
            </span>
          </div>

          <div>
            <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ formatRupiah(wallet?.saldo || 2500000) }}</h2>
          </div>

          <div class="pt-2 flex items-center justify-between border-t border-slate-800">
            <span class="text-[11px] text-slate-400 font-medium">Status Wallet: Terverifikasi</span>
            <button class="px-3.5 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md flex items-center gap-1.5 transition-all cursor-pointer">
              <span>Tarik Dana</span>
              <ArrowUpRight class="w-4 h-4" />
            </button>
          </div>
        </div>

        <!-- Total Bonus Cair Card (WHITE Card matching Gambar 2) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
              <TrendingUp class="w-3.5 h-3.5 text-indigo-500" />
              TOTAL BONUS CAIR
            </span>
            <div class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
              <TrendingUp class="w-3.5 h-3.5" />
            </div>
          </div>

          <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">{{ formatRupiah(wallet?.total_bonus_cair || 400000) }}</h2>
          </div>

          <!-- Bonus Breakdown -->
          <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-xs">
            <div class="p-2 bg-slate-50 rounded-xl">
              <p class="text-[10px] text-slate-400 font-bold uppercase">Operasional (20%)</p>
              <p class="font-bold text-indigo-600 mt-0.5">{{ formatRupiah(wallet?.bonus_sponsor || 300000) }}</p>
            </div>
            <div class="p-2 bg-slate-50 rounded-xl">
              <p class="text-[10px] text-slate-400 font-bold uppercase">Pasangan/Tier</p>
              <p class="font-bold text-emerald-600 mt-0.5">{{ formatRupiah(wallet?.bonus_pasangan || 100000) }}</p>
            </div>
            <div class="p-2 bg-slate-50 rounded-xl">
              <p class="text-[10px] text-slate-400 font-bold uppercase">Titik RO</p>
              <p class="font-bold text-amber-600 mt-0.5">{{ formatRupiah(wallet?.bonus_titik || 0) }}</p>
            </div>
          </div>
        </div>

        <!-- Perkembangan Kaki Binary Card (WHITE Card matching Gambar 2) -->
        <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 flex items-center gap-1.5">
              <Users class="w-3.5 h-3.5 text-indigo-500" />
              PERKEMBANGAN KAKI BINARY
            </span>
            <span class="px-2.5 py-0.5 text-[10px] font-bold bg-emerald-50 text-emerald-600 rounded-full border border-emerald-200">
              2 KAKI BINARY
            </span>
          </div>

          <div class="grid grid-cols-2 gap-3 pt-2">
            <!-- Left Leg -->
            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 space-y-2 text-center">
              <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">KAKI KIRI (LEFT)</span>
              <h3 class="text-2xl font-black text-slate-900">{{ binary_legs?.left?.members || 3 }} <span class="text-xs font-semibold text-slate-400">Orang</span></h3>
              <div class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold">
                MENUNGGU: {{ binary_legs?.left?.pending_points || 1 }} Poin
              </div>
            </div>

            <!-- Right Leg -->
            <div class="p-4 bg-slate-50/80 rounded-2xl border border-slate-100 space-y-2 text-center">
              <span class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">KAKI KANAN (RIGHT)</span>
              <h3 class="text-2xl font-black text-slate-900">{{ binary_legs?.right?.members || 2 }} <span class="text-xs font-semibold text-slate-400">Orang</span></h3>
              <div class="px-2 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold">
                MENUNGGU: {{ binary_legs?.right?.pending_points || 0 }} Poin
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </AdminLayout>
</template>
