<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Victim;
use App\Models\Need;
use App\Models\Region;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NeedReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ad_soyad' => 'required|string|max:255',
            'yas' => 'nullable|integer',
            'cinsiyet' => 'nullable|string',
            'telefon' => 'nullable|string',
            'adres' => 'nullable|string',
            'bolge_id' => 'required|exists:regions,id',

            'su_litre' => 'required|integer|min:0',
            'gida_paketi' => 'required|integer|min:0',
            'cadir' => 'required|integer|min:0',
            'ilac_adet' => 'required|integer|min:0',
        ]);

        // 1️⃣ Victim oluştur
        $victim = Victim::create([
            'ad_soyad' => $validated['ad_soyad'],
            'yas' => $validated['yas'],
            'cinsiyet' => $validated['cinsiyet'],
            'telefon' => $validated['telefon'],
            'adres' => $validated['adres'],
            'bolge_id' => $validated['bolge_id'],
            'kayit_tarihi' => now(),
        ]);

        // 2️⃣ Need oluştur
        Need::create([
            'victim_id' => $victim->id,
            'bolge_id' => $validated['bolge_id'],
            'su_litre' => $validated['su_litre'],
            'gida_paketi' => $validated['gida_paketi'],
            'cadir' => $validated['cadir'],
            'ilac_adet' => $validated['ilac_adet'],
            'kayit_tarihi' => now(),
        ]);

        return redirect()->back()->with('success', 'İhtiyaç bildirimi başarıyla alındı.');
    }
}