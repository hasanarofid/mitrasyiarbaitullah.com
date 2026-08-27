<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  Settings as SettingsIcon, 
  Check, 
  RotateCcw, 
  CheckCircle2, 
  AlertCircle,
  Info
} from '@lucide/vue';

const props = defineProps({
  config: Object,
});

const page = usePage();
const flashSuccess = computed(() => page.props.flash?.success);
const flashError = computed(() => page.props.flash?.error);

const form = useForm({
  pin_price:            props.config?.pin_price || 200000,
  voucher_price:        props.config?.voucher_price || 500000,
  payment_bank_name:    props.config?.payment_bank_name || 'BCA',
  payment_account_no:   props.config?.payment_account_no || '',
  payment_account_name: props.config?.payment_account_name || 'Mitra Syiar Baitullah',
  sponsor_percent:      props.config?.sponsor_percent || 100,
  pairing_percent:      props.config?.pairing_percent || 50,
  titik_percent:        props.config?.titik_percent || 1,
  business_mode:        props.config?.business_mode ?? true,
  min_withdrawal:       props.config?.min_withdrawal || 50000,
  max_level_depth:      props.config?.max_level_depth || 0,
  allow_sponsor_exceed: props.config?.allow_sponsor_exceed ?? true,
  allow_pairing_exceed: props.config?.allow_pairing_exceed ?? true,
  allow_titik_exceed:   props.config?.allow_titik_exceed ?? true,
});

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val);
};

const calcBonus = (percent) => {
  return formatRupiah((form.pin_price * (percent || 0)) / 100);
};

const submitSettings = () => {
  form.post(route('admin.settings.update'), {
    preserveScroll: true,
  });
};

const resetDefaults = () => {
  if (confirm('Apakah Anda yakin ingin mengembalikan pengaturan ke default?')) {
    form.pin_price = 200000;
    form.voucher_price = 500000;
    form.sponsor_percent = 100;
    form.pairing_percent = 50;
    form.titik_percent = 1;
    form.min_withdrawal = 50000;
    form.max_level_depth = 0;
    submitSettings();
  }
};
</script>

<template>
  <Head title="Pengaturan Sistem Binary - XSELLER" />

  <AdminLayout>
    <div class="space-y-6">
      
      <!-- Flash Notifications -->
      <div v-if="flashSuccess" class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
          <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
          <span>{{ flashSuccess }}</span>
        </div>
      </div>

      <div v-if="flashError" class="p-4 bg-rose-50 border border-rose-300 text-rose-800 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
          <AlertCircle class="w-4 h-4 text-rose-500 shrink-0" />
          <span>{{ flashError }}</span>
        </div>
      </div>

      <!-- MAIN CONTAINER: KONFIGURASI BONUS BINARY & HARGA -->
      <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm space-y-6">
        
        <!-- Header -->
        <div class="flex items-start gap-3 border-b border-slate-100 pb-5">
          <div class="p-2.5 bg-amber-50 text-amber-600 rounded-2xl shrink-0 mt-0.5">
            <SettingsIcon class="w-6 h-6" />
          </div>
          <div>
            <h2 class="text-lg md:text-xl font-black text-slate-900 tracking-tight">
              Pengaturan Sistem & Konfigurasi Bonus Binary
            </h2>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
              Sebagai Admin Utama, Anda dapat menyesuaikan persentase distribusi bonus binary (Sponsor, Pasangan/Pairing, & Titik/Generasi), harga DP Awal/Voucher, serta aturan minimal penarikan.
            </p>
          </div>
        </div>

        <form @submit.prevent="submitSettings" class="space-y-6">
          
          <!-- SECTION 1: PENGATURAN HARGA DP AWAL / PIN & ALOKASI BONUS BINARY -->
          <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-5 space-y-4">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              1. PENGATURAN HARGA DP AWAL & ALOKASI BONUS BINARY
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
              <div class="md:col-span-5">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                  HARGA PER DP AWAL / PIN (RP)
                </label>
                <input 
                  v-model="form.pin_price"
                  type="number"
                  required
                  step="1000"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-slate-900 text-xs font-black focus:outline-none focus:border-amber-500"
                />
                <p class="text-[10px] text-slate-400 mt-1">Digunakan sebagai acuan dasar kalkulasi persentase seluruh bonus binary.</p>
              </div>

              <div class="md:col-span-7 p-3 bg-blue-50/70 border border-blue-200/80 rounded-xl text-xs text-blue-900 flex items-start gap-2">
                <Info class="w-4 h-4 text-blue-600 shrink-0 mt-0.5" />
                <span class="text-[11px] leading-relaxed">Semua komisi binary dihitung dalam bentuk persentase dasar (%) dikalikan dengan Biaya Pendaftaran DP Awal / PIN yang diaktifkan ini secara otomatis.</span>
              </div>
            </div>

            <!-- Bonus 3 Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
              <!-- Bonus Operasional -->
              <div class="p-4 bg-white border border-slate-200/80 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-black text-slate-900">BONUS OPERASIONAL</span>
                  <span class="px-2 py-0.5 text-[9px] font-extrabold bg-emerald-100 text-emerald-700 rounded uppercase">OPERASIONAL</span>
                </div>
                <div>
                  <label class="text-[10px] text-slate-400 block mb-0.5">PERSENTASE (%)</label>
                  <input v-model="form.sponsor_percent" type="number" step="0.1" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold" />
                </div>
                <div class="text-[11px] text-slate-500 flex justify-between pt-1">
                  <span>Nilai Cair:</span>
                  <strong class="text-emerald-600 font-mono">{{ calcBonus(form.sponsor_percent) }}</strong>
                </div>
              </div>

              <!-- Bonus Pasangan -->
              <div class="p-4 bg-white border border-slate-200/80 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-black text-slate-900">BONUS PASANGAN</span>
                  <span class="px-2 py-0.5 text-[9px] font-extrabold bg-indigo-100 text-indigo-700 rounded uppercase">PASANGAN</span>
                </div>
                <div>
                  <label class="text-[10px] text-slate-400 block mb-0.5">PERSENTASE (%)</label>
                  <input v-model="form.pairing_percent" type="number" step="0.1" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold" />
                </div>
                <div class="text-[11px] text-slate-500 flex justify-between pt-1">
                  <span>Nilai Cair:</span>
                  <strong class="text-indigo-600 font-mono">{{ calcBonus(form.pairing_percent) }}</strong>
                </div>
              </div>

              <!-- Bonus Titik -->
              <div class="p-4 bg-white border border-slate-200/80 rounded-xl space-y-2">
                <div class="flex items-center justify-between">
                  <span class="text-xs font-black text-slate-900">BONUS TITIK (MAKS 10 LEVEL)</span>
                  <span class="px-2 py-0.5 text-[9px] font-extrabold bg-emerald-100 text-emerald-700 rounded uppercase">TITIK</span>
                </div>
                <div>
                  <label class="text-[10px] text-slate-400 block mb-0.5">PERSENTASE (%)</label>
                  <input v-model="form.titik_percent" type="number" step="0.1" class="w-full px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold" />
                </div>
                <div class="text-[11px] text-slate-500 flex justify-between pt-1">
                  <span>Nilai Cair:</span>
                  <strong class="text-emerald-600 font-mono">{{ calcBonus(form.titik_percent) }}</strong>
                </div>
              </div>
            </div>
          </div>

          <!-- SECTION 1b: PEMBAYARAN MANUAL (Harga Voucher & Rekening Tujuan) -->
          <div class="bg-amber-50/60 border border-amber-200/80 rounded-2xl p-5 space-y-4">
            <h3 class="text-xs font-black text-amber-900 uppercase tracking-tight flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span>
              1b. PEMBAYARAN MANUAL — HARGA VOUCHER & REKENING TUJUAN
            </h3>
            <p class="text-[11px] text-amber-700">Konfigurasi ini akan ditampilkan di halaman publik <code class="bg-amber-100 px-1 rounded">/beli-voucher</code> untuk calon mitra yang ingin beli Voucher via transfer bank.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Harga Voucher -->
              <div>
                <label class="block text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1">HARGA PER VOUCHER (Rp)</label>
                <input
                  v-model="form.voucher_price"
                  type="number"
                  required
                  step="10000"
                  class="w-full px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl text-slate-900 text-xs font-black focus:outline-none focus:border-amber-500"
                />
                <p class="text-[10px] text-amber-600 mt-1">Default Rp 500.000 (sesuai DP Join). Calon mitra bayar sesuai jumlah voucher × harga ini.</p>
              </div>

              <!-- Nama Bank -->
              <div>
                <label class="block text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1">NAMA BANK TUJUAN</label>
                <input
                  v-model="form.payment_bank_name"
                  type="text"
                  required
                  placeholder="Contoh: BCA, BRI, Mandiri, BSI"
                  class="w-full px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl text-slate-900 text-xs font-semibold focus:outline-none focus:border-amber-500"
                />
              </div>

              <!-- No Rekening -->
              <div>
                <label class="block text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1">NOMOR REKENING</label>
                <input
                  v-model="form.payment_account_no"
                  type="text"
                  required
                  placeholder="Contoh: 1234567890"
                  class="w-full px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl text-slate-900 text-xs font-black font-mono tracking-wider focus:outline-none focus:border-amber-500"
                />
              </div>

              <!-- Nama Pemilik Rekening -->
              <div>
                <label class="block text-[10px] font-bold text-amber-700 uppercase tracking-wider mb-1">NAMA PEMILIK REKENING</label>
                <input
                  v-model="form.payment_account_name"
                  type="text"
                  required
                  placeholder="Nama sesuai rekening bank"
                  class="w-full px-3.5 py-2.5 bg-white border border-amber-300 rounded-xl text-slate-900 text-xs font-semibold focus:outline-none focus:border-amber-500"
                />
              </div>
            </div>
          </div>

          <!-- SECTION 2: MODE BISNIS & MINIMAL PENARIKAN -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Business Mode -->
            <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-5 space-y-3">
              <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                2. PENGATURAN MODE BISNIS
              </h3>
              <div class="flex items-center gap-3 pt-1">
                <input v-model="form.business_mode" type="checkbox" class="w-5 h-5 rounded border-slate-300 text-amber-600 focus:ring-amber-500 cursor-pointer" />
                <span class="text-xs font-extrabold text-slate-800">Mode: Murni Jual PIN / Voucher (Mitra bisa beli PIN langsung)</span>
              </div>
            </div>

            <!-- Min Withdrawal -->
            <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-5 space-y-3">
              <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                3. PENGATURAN MINIMAL PENARIKAN (WD)
              </h3>
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">MINIMAL PENARIKAN (RUPIAH)</label>
                <input v-model="form.min_withdrawal" type="number" step="5000" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold" />
              </div>
            </div>
          </div>

          <!-- SECTION 3: BATASAN KEDALAMAN LEVEL JARINGAN BINARY -->
          <div class="bg-slate-50/70 border border-slate-100 rounded-2xl p-5 space-y-4">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-tight flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
              4. BATASAN KEDALAMAN LEVEL JARINGAN BINARY
            </h3>
            <p class="text-[11px] text-slate-500">Ukur maksimal kedalaman level jaringan binary untuk pembagian bonus. Jika diset "0", artinya tanpa batas kedalaman (unlimited).</p>

            <div class="space-y-4">
              <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">MAKSIMAL LEVEL JARINGAN (0 = TANPA BATAS)</label>
                <input v-model="form.max_level_depth" type="number" min="0" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold" />
              </div>

              <div class="p-4 bg-white border border-slate-200/80 rounded-xl space-y-2 text-xs font-semibold text-slate-700">
                <span class="text-[10px] text-slate-400 font-bold block uppercase mb-1">Pengecualian Batasan Level:</span>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="form.allow_sponsor_exceed" type="checkbox" class="w-4 h-4 text-amber-600 rounded" />
                  <span>Tetap berikan Bonus Operasional</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="form.allow_pairing_exceed" type="checkbox" class="w-4 h-4 text-amber-600 rounded" />
                  <span>Tetap berikan Bonus Pasangan (Pairing)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                  <input v-model="form.allow_titik_exceed" type="checkbox" class="w-4 h-4 text-amber-600 rounded" />
                  <span>Tetap berikan Bonus Titik</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" @click="resetDefaults" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors cursor-pointer flex items-center gap-1.5">
              <RotateCcw class="w-3.5 h-3.5" />
              <span>Reset ke Default</span>
            </button>

            <button type="submit" :disabled="form.processing" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-black rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer disabled:opacity-50">
              <Check class="w-4 h-4 stroke-[3]" />
              <span>Simpan Pengaturan</span>
            </button>
          </div>

        </form>

      </div>

    </div>
  </AdminLayout>
</template>
