<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Need;
use App\Models\Region;
use App\Models\Victim;
use Illuminate\Http\Request;

class NeedController extends Controller
{
    // TÜM İHTİYAÇLARI LİSTELE
    public function index()
{
    $needs = Need::with('victim','region')->latest()->get();
    return view('admin.needs.index', compact('needs'));
}



    // EKLEME SAYFASI
    public function create()
    {
        $regions = Region::all();
        $victims = Victim::all();

        return view('admin.needs.create', compact('regions', 'victims'));
    }

    // EKLEME İŞLEMİ
    public function store(Request $request)
    {
        $request->validate([
            'victim_id'   => 'required|exists:victims,id',
            'bolge_id'    => 'required|exists:regions,id',
            'su_litre'    => 'required|numeric|min:0',
            'gida_paketi' => 'required|numeric|min:0',
            'cadir'       => 'required|numeric|min:0',
            'ilac_adet'   => 'required|numeric|min:0',
            'oncelik'     => 'required|in:Düşük,Orta,Yüksek',
        ]);

        Need::create([
            'victim_id'     => $request->victim_id,
            'bolge_id'      => $request->bolge_id,
            'su_litre'      => $request->su_litre,
            'gida_paketi'   => $request->gida_paketi,
            'cadir'         => $request->cadir,
            'ilac_adet'     => $request->ilac_adet,
            'oncelik'       => $request->oncelik,
            'kayit_tarihi'  => now(),
        ]);

        return redirect()->route('admin.needs.index')->with('success', 'İhtiyaç başarıyla eklendi!');
    }

    // DÜZENLEME SAYFASI
    public function edit($id)
    {
        $need = Need::findOrFail($id);
        $regions = Region::all();
        $victims = Victim::all();

        return view('admin.needs.edit', compact('need', 'regions', 'victims'));
    }

    // GÜNCELLEME
    public function update(Request $request, $id)
    {
        $request->validate([
            'victim_id'   => 'required|exists:victims,id',
            'bolge_id'    => 'required|exists:regions,id',
            'su_litre'    => 'required|numeric|min:0',
            'gida_paketi' => 'required|numeric|min:0',
            'cadir'       => 'required|numeric|min:0',
            'ilac_adet'   => 'required|numeric|min:0',
            'oncelik'     => 'required|in:Düşük,Orta,Yüksek',
        ]);

        $need = Need::findOrFail($id);

        $need->update([
            'victim_id'     => $request->victim_id,
            'bolge_id'      => $request->bolge_id,
            'su_litre'      => $request->su_litre,
            'gida_paketi'   => $request->gida_paketi,
            'cadir'         => $request->cadir,
            'ilac_adet'     => $request->ilac_adet,
            'oncelik'       => $request->oncelik,
        ]);

        return redirect()->route('needs.index')->with('success', 'İhtiyaç başarıyla güncellendi!');
    }

    // SİLME
    public function destroy($id)
    {
        $need = Need::findOrFail($id);
        $need->delete();

        return redirect()->route('needs.index')->with('success', 'İhtiyaç silindi!');
    }
}
