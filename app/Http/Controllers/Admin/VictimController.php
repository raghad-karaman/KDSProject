<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Victim;
use App\Models\Region;
use Illuminate\Http\Request;

class VictimController extends Controller
{
    // 📌 Mağdurlar Listesi
    public function index()
    {
        $victims = Victim::with('region')->get();
        return view('admin.victims.index', compact('victims'));
    }

    // 📌 Yeni Mağdur Ekleme Sayfası
    public function create()
    {
        $regions = Region::all();
        return view('admin.victims.create', compact('regions'));
    }

    // 📌 Mağdur Kaydet
    public function store(Request $request)
    {
        $request->validate([
            'ad_soyad' => 'required|string|max:255',
            'yas' => 'required|numeric',
            'cinsiyet' => 'required',
            'telefon' => 'required',
            'adres' => 'required',
            'bolge_id' => 'required|exists:regions,id',
            'kayit_tarihi' => 'required',
        ]);

        Victim::create($request->all());

        return redirect()->route('admin.victims.index')
               ->with('success', 'Mağdur başarıyla eklendi!');
    }

    // 📌 Düzenleme Sayfası
    public function edit($id)
    {
        $victim = Victim::findOrFail($id);
        $regions = Region::all();

        return view('admin.victims.edit', compact('victim', 'regions'));
    }

    // 📌 Güncelle
    public function update(Request $request, $id)
    {
        $request->validate([
            'ad_soyad' => 'required|string|max:255',
            'yas' => 'required|numeric',
            'cinsiyet' => 'required',
            'telefon' => 'required',
            'adres' => 'required',
            'bolge_id' => 'required|exists:regions,id',
            'kayit_tarihi' => 'required',
        ]);

        $victim = Victim::findOrFail($id);
        $victim->update($request->all());

        return redirect()->route('admin.victims.index')
               ->with('success', 'Mağdur başarıyla güncellendi!');
    }

    // 📌 Sil
    public function destroy($id)
    {
        $victim = Victim::findOrFail($id);
        $victim->delete();

        return redirect()->route('admin.victims.index')
               ->with('success', 'Mağdur başarıyla silindi!');
    }
}
