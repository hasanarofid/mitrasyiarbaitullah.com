<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { 
  Users, 
  UserPlus, 
  Search, 
  Award, 
  CheckCircle2, 
  Layers, 
  TrendingUp, 
  Sparkles, 
  Filter,
  RefreshCw,
  RotateCcw,
  Info,
  ChevronRight
} from '@lucide/vue';

const props = defineProps({
  focus_user: Object,
  summary: Object,
  team_levels: Array,
  filtered_members: Array,
  all_users: Array,
  filters: Object,
});

const selectedUserSearch = ref(props.filters?.focus_id || props.focus_user?.id || '');
const selectedLevel = ref(props.filters?.level || 'all');
const searchQuery = ref(props.filters?.search || '');
const selectedMultiplier = ref(props.filters?.multiplier || 5);

const formatRp = (val) => {
  return 'Rp ' + Number(val || 0).toLocaleString('id-ID');
};

const formatNumber = (val) => {
  return Number(val || 0).toLocaleString('id-ID');
};

const handleFilter = () => {
  router.get(
    route('admin.pohon-jaringan'),
    {
      focus_id: selectedUserSearch.value,
      level: selectedLevel.value,
      search: searchQuery.value,
      multiplier: selectedMultiplier.value,
    },
    { preserveState: true, replace: true }
  );
};

const focusUser = (userId) => {
  selectedUserSearch.value = userId;
  handleFilter();
};

const selectLevelFilter = (lvl) => {
  selectedLevel.value = String(lvl);
  handleFilter();
};

const resetFocus = () => {
  selectedUserSearch.value = '';
  selectedLevel.value = 'all';
  searchQuery.value = '';
  selectedMultiplier.value = 5;
  router.get(route('admin.pohon-jaringan'));
};
</script>

<template>
  <Head title="Team Saya (Generasi Multi-Tier) - Mitra Syiar Baitullah" />

  <AdminLayout>
    <div class="space-y-6">

      <!-- Top Controls Bar: Select Focus User & Multiplier -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-4 sm:p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Search/Focus User Select Dropdown -->
        <div class="relative flex-1 max-w-xl flex items-center gap-2">
          <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
              <Search class="w-4 h-4" />
            </span>
            <select
              v-model="selectedUserSearch"
              @change="handleFilter"
              class="w-full bg-[#fffaf2] border border-[#e09d49]/60 rounded-xl pl-10 pr-8 py-2.5 text-xs font-bold text-[#5c2c24] focus:outline-none focus:border-[#e98318] focus:ring-1 focus:ring-[#e98318] transition-colors appearance-none cursor-pointer"
            >
              <option v-for="u in all_users" :key="u.id" :value="u.id">
                Fokus Jaringan: {{ u.label }}
              </option>
            </select>
            <span class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
              ▼
            </span>
          </div>
        </div>

        <!-- Right Quick Action Controls -->
        <div class="flex items-center gap-2 flex-wrap">
          <!-- Multiplier Projection Selector -->
          <div class="flex items-center gap-1.5 bg-[#fffaf2] border border-[#e09d49]/50 rounded-xl px-3 py-1.5">
            <span class="text-[11px] font-bold text-[#5c2c24] shrink-0">Simulasi:</span>
            <select
              v-model="selectedMultiplier"
              @change="handleFilter"
              class="bg-transparent text-xs font-extrabold text-[#e98318] focus:outline-none cursor-pointer"
            >
              <option :value="5">Kelipatan 5 x 5</option>
              <option :value="10">Kelipatan 10 x 10</option>
              <option :value="15">Kelipatan 15 x 15</option>
              <option :value="20">Kelipatan 20 x 20</option>
            </select>
          </div>

          <!-- Reset Button -->
          <button 
            @click="resetFocus"
            class="px-4 py-2.5 bg-white hover:bg-[#fffaf2] border border-[#e09d49]/60 text-[#5c2c24] text-xs font-bold rounded-xl transition-all flex items-center gap-2 shadow-xs shrink-0 cursor-pointer"
          >
            <RotateCcw class="w-3.5 h-3.5 text-[#e98318]" />
            <span>Reset Fokus</span>
          </button>
        </div>
      </div>

      <!-- Main Header Banner (AI Copywriting explaining Team Structure) -->
      <div class="bg-gradient-to-r from-[#2e395d] via-[#5c2c24] to-[#e98318] text-white p-6 sm:p-8 rounded-3xl shadow-lg relative overflow-hidden space-y-4">
        <div class="relative z-10 space-y-3">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1 text-[10px] font-black uppercase tracking-wider bg-white/15 backdrop-blur text-[#e09d49] border border-white/20 rounded-full">
              Sistem Team Generasi (Unilevel Multi-Tier)
            </span>
            <span class="text-xs text-slate-200 font-medium hidden sm:inline">• Bukan Pohon Jaringan Binary</span>
          </div>

          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h1 class="text-2xl sm:text-3xl font-black tracking-tight uppercase">
                👥 TEAM SAYA — {{ focus_user?.name }}
              </h1>
              <p class="text-xs sm:text-sm text-slate-200 font-medium mt-1">
                Daftar & struktur jaringan mitra berdasarkan level generasi sponsor (Team 1 s/d Team 12).
              </p>
            </div>
            
            <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-2xl border border-white/20 text-center shrink-0">
              <span class="text-[10px] font-extrabold uppercase text-[#e09d49] block">Fokus Akun Utama</span>
              <span class="text-sm font-black text-white">{{ focus_user?.username }}</span>
            </div>
          </div>

          <!-- Detailed Rules Bullet Points (AI Copywriting) -->
          <div class="p-4 rounded-2xl bg-black/20 backdrop-blur-md border border-white/10 grid grid-cols-1 md:grid-cols-3 gap-3 text-xs leading-relaxed">
            <div class="space-y-1">
              <div class="font-bold text-[#e09d49] flex items-center gap-1.5">
                <Sparkles class="w-3.5 h-3.5" />
                <span>Mitra Sponsor Tanpa Batas</span>
              </div>
              <p class="text-slate-300 text-[11px]">
                Sponsor direct bebas tanpa batas lebar. Mitra ke-1, ke-5, ke-6, ke-15, dst. tetap berada di <strong>Level 1 (Team 1)</strong> dan tidak turun ke Level 2.
              </p>
            </div>

            <div class="space-y-1">
              <div class="font-bold text-[#e09d49] flex items-center gap-1.5">
                <Layers class="w-3.5 h-3.5" />
                <span>Kedalaman 12 Level Team</span>
              </div>
              <p class="text-slate-300 text-[11px]">
                Seluruh pertumbuhan downline dari Team 1 akan membentuk Team 2, Team 3, hingga Team 12 secara otomatis sesuai kedalaman generasi.
              </p>
            </div>

            <div class="space-y-1">
              <div class="font-bold text-[#e09d49] flex items-center gap-1.5">
                <Award class="w-3.5 h-3.5" />
                <span>Skema Ujroh & Komisi</span>
              </div>
              <p class="text-slate-300 text-[11px]">
                Ujroh Operasional Direct: <strong>Rp 250.000</strong> per mitra (2 Mitra = Balik Modal!). Plus Komisi Team Rp 5.000 s/d Rp 1.000 per mitra.
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Stat Cards Summary Top Row -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Mitra -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">TOTAL MITRA TEAM</span>
            <div class="p-2.5 rounded-xl bg-[#e98318]/15 text-[#e98318]">
              <Users class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#5c2c24]">{{ formatNumber(summary?.total_members) }} <span class="text-xs font-bold text-[#9d7c64]">Mitra</span></p>
          <div class="text-[11px] font-medium text-emerald-600 flex items-center gap-1">
            <CheckCircle2 class="w-3.5 h-3.5" />
            <span>Akumulasi Team 1 s/d Team 12</span>
          </div>
        </div>

        <!-- Direct Operasional (Team 1) -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">DIRECT OPERASIONAL (TEAM 1)</span>
            <div class="p-2.5 rounded-xl bg-[#2e395d]/10 text-[#2e395d]">
              <UserPlus class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#2e395d]">{{ formatNumber(summary?.direct_sponsors) }} <span class="text-xs font-bold text-[#9d7c64]">Orang</span></p>
          <div class="text-[11px] font-semibold text-[#e98318] flex items-center gap-1">
            <Sparkles class="w-3.5 h-3.5" />
            <span>Tanpa Batas Lebar (Unilevel)</span>
          </div>
        </div>

        <!-- Total Komisi Team -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">TOTAL KOMISI TEAM</span>
            <div class="p-2.5 rounded-xl bg-[#e09d49]/20 text-[#e98318]">
              <TrendingUp class="w-5 h-5" />
            </div>
          </div>
          <p class="text-2xl sm:text-3xl font-black text-[#5c2c24]">{{ formatRp(summary?.total_commission) }}</p>
          <div class="text-[11px] font-medium text-[#9d7c64]">
            <span>Total bonus terakumulasi saat ini</span>
          </div>
        </div>

        <!-- Level Aktif -->
        <div class="p-5 rounded-2xl bg-white border border-[#e09d49]/40 shadow-sm space-y-2 relative overflow-hidden">
          <div class="flex items-center justify-between">
            <span class="text-xs font-extrabold text-[#9d7c64] uppercase tracking-wider">LEVEL KEDALAMAN</span>
            <div class="p-2.5 rounded-xl bg-indigo-50 text-indigo-600">
              <Layers class="w-5 h-5" />
            </div>
          </div>
          <p class="text-3xl font-black text-[#5c2c24]">{{ summary?.active_levels }} <span class="text-xs font-bold text-[#9d7c64]">/ 12 Level</span></p>
          <div class="text-[11px] font-semibold text-indigo-600">
            <span>Kedalaman terisi di jaringan</span>
          </div>
        </div>
      </div>

      <!-- Red-Box Highlighted Marketing Plan Matrix Table (Matching User Screenshot) -->
      <div class="bg-white border-2 border-rose-500 rounded-3xl p-5 sm:p-8 shadow-md space-y-4 relative">
        <!-- Red Box Header Indicator -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-rose-100 pb-4">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-rose-50 border border-rose-300 text-rose-600 rounded-xl font-black text-sm">
              KOMISI TEAM
            </div>
            <div>
              <h3 class="text-base font-black text-[#5c2c24] uppercase tracking-tight">
                PROYEKSI KOMISI TEAM (SKEMA {{ selectedMultiplier }} x {{ selectedMultiplier }})
              </h3>
              <p class="text-xs text-[#9d7c64] font-medium">
                Matriks potensi pendapatan jaringan berdasarkan skema duplikasi kelipatan {{ selectedMultiplier }} mitra.
              </p>
            </div>
          </div>

          <div class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1.5 rounded-full border border-rose-200 self-start sm:self-auto">
            Matriks Marketing Plan Resmi
          </div>
        </div>

        <!-- Table displaying Team 1 to Team 12 matching screenshot -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs font-bold border-collapse">
            <thead>
              <tr class="bg-slate-900 text-white uppercase text-[11px]">
                <th class="p-3 border border-slate-700 rounded-tl-xl">TEAM</th>
                <th class="p-3 border border-slate-700 text-right">MITRA / AKUN (PROYEKSI)</th>
                <th class="p-3 border border-slate-700 text-right">KOMISI / MITRA (RP)</th>
                <th class="p-3 border border-slate-700 text-right">TOTAL KOMISI (RP)</th>
                <th class="p-3 border border-slate-700 text-center rounded-tr-xl">MITRA AKTUAL</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr 
                v-for="lvl in team_levels" 
                :key="lvl.level"
                :class="[
                  'hover:bg-[#fffaf2] transition-colors',
                  lvl.count > 0 ? 'bg-amber-50/50' : 'bg-white'
                ]"
              >
                <td class="p-3 border border-gray-200 font-extrabold text-[#5c2c24]">
                  {{ lvl.name }}
                </td>
                <td class="p-3 border border-gray-200 text-right font-mono text-slate-800">
                  {{ formatNumber(lvl.projection_count) }}
                </td>
                <td class="p-3 border border-gray-200 text-right font-mono text-slate-700">
                  {{ formatNumber(lvl.commission_per_member) }}
                </td>
                <td class="p-3 border border-gray-200 text-right font-mono font-extrabold text-emerald-700">
                  {{ formatNumber(lvl.projection_commission) }}
                </td>
                <td class="p-3 border border-gray-200 text-center">
                  <span 
                    :class="[
                      'px-2.5 py-1 rounded-full text-[11px] font-black',
                      lvl.count > 0 ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400'
                    ]"
                  >
                    {{ lvl.count }} Member
                  </span>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr class="bg-[#5c2c24] text-white font-black text-xs">
                <td class="p-3.5 border border-[#5c2c24]">TOTAL TEAM 1 - 12</td>
                <td class="p-3.5 border border-[#5c2c24] text-right font-mono">
                  {{ formatNumber(team_levels?.reduce((sum, l) => sum + l.projection_count, 0)) }}
                </td>
                <td class="p-3.5 border border-[#5c2c24] text-right">-</td>
                <td class="p-3.5 border border-[#5c2c24] text-right font-mono text-[#e09d49]">
                  {{ formatRp(team_levels?.reduce((sum, l) => sum + l.projection_commission, 0)) }}
                </td>
                <td class="p-3.5 border border-[#5c2c24] text-center font-mono text-white">
                  {{ summary?.total_members }} Member
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Fast Level Switcher Grid Buttons (Team 1 s/d Team 12) -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-[#e09d49]/20 pb-3">
          <div>
            <h3 class="text-sm font-black text-[#5c2c24] uppercase">
              🎯 Pilih Level Team (Filter Cepat)
            </h3>
            <p class="text-xs text-[#9d7c64]">Klik tombol level untuk melihat anggota di level tersebut</p>
          </div>
          
          <button 
            @click="selectLevelFilter('all')" 
            :class="[
              'px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer',
              selectedLevel === 'all' ? 'bg-[#5c2c24] text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
            ]"
          >
            Tampilkan Semua Level
          </button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
          <button
            v-for="lvl in team_levels"
            :key="lvl.level"
            @click="selectLevelFilter(lvl.level)"
            :class="[
              'p-3.5 rounded-2xl border text-left transition-all cursor-pointer flex flex-col justify-between space-y-1',
              String(selectedLevel) === String(lvl.level)
                ? 'bg-[#5c2c24] text-white border-[#5c2c24] shadow-md ring-2 ring-[#e98318]/50'
                : (lvl.count > 0 ? 'bg-[#fffaf2] border-[#e09d49]/60 hover:border-[#e98318] text-[#5c2c24]' : 'bg-gray-50 border-gray-200 text-gray-400 hover:border-gray-300')
            ]"
          >
            <div class="flex items-center justify-between">
              <span class="text-xs font-black uppercase">{{ lvl.name }}</span>
              <span 
                :class="[
                  'text-[9px] font-black px-1.5 py-0.5 rounded-full',
                  lvl.count > 0 ? (String(selectedLevel) === String(lvl.level) ? 'bg-[#e98318] text-white' : 'bg-[#e98318]/20 text-[#e98318]') : 'bg-gray-200 text-gray-500'
                ]"
              >
                {{ lvl.count }}
              </span>
            </div>
            <div class="text-[11px] font-mono font-bold mt-1">
              {{ formatRp(lvl.commission_per_member) }}
            </div>
            <div class="text-[10px] opacity-90 truncate font-semibold">
              Total: {{ formatRp(lvl.total_commission) }}
            </div>
          </button>
        </div>
      </div>

      <!-- Member List Table & Mobile Card View -->
      <div class="bg-white border border-[#e09d49]/30 rounded-3xl p-6 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-[#e09d49]/20 pb-4">
          <div class="flex items-center gap-2">
            <Users class="w-4 h-4 text-[#e98318]" />
            <h3 class="text-sm font-black text-[#5c2c24] uppercase">
              Daftar Anggota Team
              <span class="text-[#e98318] ml-1">({{ filtered_members.length }} Anggota)</span>
            </h3>
          </div>

          <!-- Search Filter Box -->
          <div class="relative w-full sm:w-72">
            <Search class="w-4 h-4 text-[#9d7c64] absolute left-3 top-1/2 -translate-y-1/2" />
            <input
              v-model="searchQuery"
              @keyup.enter="handleFilter"
              type="text"
              placeholder="Cari nama, username, sponsor..."
              class="w-full h-10 pl-9 pr-3 bg-white border border-[#e09d49]/60 rounded-xl text-xs font-medium text-[#5c2c24] placeholder-[#9d7c64]/60 focus:outline-none focus:ring-2 focus:ring-[#e98318]/20 focus:border-[#e98318] transition-all"
            />
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filtered_members.length === 0" class="py-12 text-center space-y-3">
          <div class="w-16 h-16 rounded-full bg-[#fffaf2] border border-[#e09d49]/40 text-[#e98318] flex items-center justify-center mx-auto text-2xl">
            👥
          </div>
          <h4 class="text-base font-bold text-[#5c2c24]">Belum Ada Mitra di Level Ini</h4>
          <p class="text-xs text-[#9d7c64] max-w-md mx-auto">
            Ajak calon mitra baru menggunakan link referral Anda untuk menambah anggota di Team 1!
          </p>
        </div>

        <!-- Desktop Table View -->
        <div v-else>
          <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs font-medium text-[#5c2c24]">
              <thead>
                <tr class="bg-[#f8efdf] text-[#5c2c24] font-black uppercase text-[11px]">
                  <th class="p-3.5 rounded-l-xl">Mitra / Username</th>
                  <th class="p-3.5">Level Team</th>
                  <th class="p-3.5">Sponsor Direct</th>
                  <th class="p-3.5">Paket Join</th>
                  <th class="p-3.5">Tgl. Bergabung</th>
                  <th class="p-3.5 rounded-r-xl text-right">Komisi / Mitra</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-[#e09d49]/20">
                <tr v-for="m in filtered_members" :key="m.id" class="hover:bg-[#fffaf2] transition-colors">
                  <td class="p-3.5">
                    <div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5c2c24] to-[#e98318] text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                        {{ m.name ? m.name.charAt(0).toUpperCase() : 'M' }}
                      </div>
                      <div>
                        <div class="font-bold text-[#5c2c24] text-xs">{{ m.name }}</div>
                        <div class="text-[11px] text-[#e98318] font-semibold">@{{ m.username }}</div>
                        <div class="text-[10px] text-[#9d7c64]">{{ m.email }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="p-3.5">
                    <span class="px-2.5 py-1 text-[10px] font-black bg-[#5c2c24] text-white rounded-full">
                      {{ m.level }}
                    </span>
                  </td>
                  <td class="p-3.5">
                    <span class="font-semibold text-slate-700">{{ m.sponsor }}</span>
                  </td>
                  <td class="p-3.5">
                    <span class="px-2 py-0.5 text-[10px] font-bold bg-[#e98318]/15 text-[#e98318] border border-[#e09d49]/30 rounded-md">
                      {{ m.package }}
                    </span>
                  </td>
                  <td class="p-3.5 text-[#9d7c64] font-medium">{{ m.joined_at }}</td>
                  <td class="p-3.5 text-right font-black text-emerald-600 text-sm">
                    {{ formatRp(m.commission) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Mobile Card List View -->
          <div class="block md:hidden space-y-3">
            <div 
              v-for="m in filtered_members" 
              :key="m.id"
              class="p-4 rounded-2xl bg-[#fffaf2] border border-[#e09d49]/40 space-y-2 text-xs"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                  <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-[#5c2c24] to-[#e98318] text-white font-bold flex items-center justify-center text-xs shrink-0">
                    {{ m.name ? m.name.charAt(0).toUpperCase() : 'M' }}
                  </div>
                  <div>
                    <h4 class="font-black text-[#5c2c24] text-xs">{{ m.name }}</h4>
                    <span class="text-[#e98318] font-bold text-[11px]">@{{ m.username }}</span>
                  </div>
                </div>
                <span class="px-2 py-0.5 text-[10px] font-black bg-[#5c2c24] text-white rounded-full">
                  {{ m.level }}
                </span>
              </div>

              <div class="pt-2 border-t border-[#e09d49]/20 grid grid-cols-2 gap-2 text-[11px]">
                <div>
                  <span class="text-[#9d7c64] block text-[10px]">SPONSOR</span>
                  <span class="font-bold text-slate-800">{{ m.sponsor }}</span>
                </div>
                <div>
                  <span class="text-[#9d7c64] block text-[10px]">TGL. JOIN</span>
                  <span class="font-semibold text-slate-700">{{ m.joined_at }}</span>
                </div>
              </div>

              <div class="pt-2 border-t border-[#e09d49]/20 flex items-center justify-between text-xs">
                <span class="text-[11px] font-bold text-[#e98318]">{{ m.package }}</span>
                <span class="font-black text-emerald-600 text-sm">{{ formatRp(m.commission) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </AdminLayout>
</template>
