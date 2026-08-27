<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display the system settings page matching Image 2 mockup.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return Inertia::render('Admin/Settings', [
            'config' => [
                'pin_price'              => (float) ($settings['pin_price'] ?? 200000),
                'voucher_price'          => (float) ($settings['voucher_price'] ?? 500000),
                'payment_bank_name'      => $settings['payment_bank_name'] ?? 'BCA',
                'payment_account_no'     => $settings['payment_account_no'] ?? '',
                'payment_account_name'   => $settings['payment_account_name'] ?? 'Mitra Syiar Baitullah',
                'sponsor_percent'        => (float) ($settings['sponsor_percent'] ?? 100),
                'pairing_percent'        => (float) ($settings['pairing_percent'] ?? 50),
                'titik_percent'          => (float) ($settings['titik_percent'] ?? 1),
                'business_mode'          => ($settings['business_mode'] ?? 'pin') === 'pin',
                'min_withdrawal'         => (float) ($settings['min_withdrawal'] ?? 50000),
                'max_level_depth'        => (int) ($settings['max_level_depth'] ?? 0),
                'allow_sponsor_exceed'   => ($settings['allow_sponsor_exceed'] ?? '1') === '1',
                'allow_pairing_exceed'   => ($settings['allow_pairing_exceed'] ?? '1') === '1',
                'allow_titik_exceed'     => ($settings['allow_titik_exceed'] ?? '1') === '1',
            ],
        ]);
    }

    /**
     * Update global system binary settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'pin_price'            => 'required|numeric|min:0',
            'voucher_price'        => 'required|numeric|min:0',
            'payment_bank_name'    => 'required|string|max:100',
            'payment_account_no'   => 'required|string|max:50',
            'payment_account_name' => 'required|string|max:100',
            'sponsor_percent'      => 'required|numeric|min:0',
            'pairing_percent'      => 'required|numeric|min:0',
            'titik_percent'        => 'required|numeric|min:0',
            'business_mode'        => 'required|boolean',
            'min_withdrawal'       => 'required|numeric|min:0',
            'max_level_depth'      => 'required|integer|min:0',
            'allow_sponsor_exceed' => 'nullable|boolean',
            'allow_pairing_exceed' => 'nullable|boolean',
            'allow_titik_exceed'   => 'nullable|boolean',
        ]);

        Setting::setValue('pin_price', $validated['pin_price'], 'text');
        Setting::setValue('voucher_price', $validated['voucher_price'], 'text');
        Setting::setValue('payment_bank_name', $validated['payment_bank_name'], 'text');
        Setting::setValue('payment_account_no', $validated['payment_account_no'], 'text');
        Setting::setValue('payment_account_name', $validated['payment_account_name'], 'text');
        Setting::setValue('sponsor_percent', $validated['sponsor_percent'], 'text');
        Setting::setValue('pairing_percent', $validated['pairing_percent'], 'text');
        Setting::setValue('titik_percent', $validated['titik_percent'], 'text');
        Setting::setValue('business_mode', $validated['business_mode'] ? 'pin' : 'product', 'text');
        Setting::setValue('min_withdrawal', $validated['min_withdrawal'], 'text');
        Setting::setValue('max_level_depth', $validated['max_level_depth'], 'text');
        Setting::setValue('allow_sponsor_exceed', ($request->input('allow_sponsor_exceed') ? '1' : '0'), 'text');
        Setting::setValue('allow_pairing_exceed', ($request->input('allow_pairing_exceed') ? '1' : '0'), 'text');
        Setting::setValue('allow_titik_exceed', ($request->input('allow_titik_exceed') ? '1' : '0'), 'text');

        return redirect()->back()->with('success', 'Konfigurasi Sistem Binary & Pengaturan Pembayaran berhasil disimpan.');
    }
}
