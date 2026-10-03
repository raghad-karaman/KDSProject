<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Resource;
use App\Models\Region;
use App\Models\ReliefTeam;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    // LISTE
    public function index()
    {
        $distributions = Distribution::with(['resource', 'region', 'team'])->get();
        return view('admin.distributions.index', compact('distributions'));
    }

    // CREATE SAYFASI
    public function create()
    {
        $resources = Resource::all();
        $regions = Region::all();
        $teams = ReliefTeam::all();

        return view('admin.distributions.create', compact('resources', 'regions', 'teams'));
    }

    // STORE — DAĞITIM EKLEME
   public function store(Request $request)
{
    // 1) Doğrulama
    $validated = $request->validate([
        'resource_id' => 'required|exists:resources,id',
        'bolge_id' => 'required|exists:regions,id',
        'sorumlu_ekip_id' => 'required|exists:relief_teams,id',
        'miktar' => 'required|integer|min:1',
        'tarih' => 'required|date',
    ]);

    // 2) Kaynak miktarını kontrol et
    $resource = Resource::findOrFail($request->resource_id);

    if ($request->miktar > $resource->miktar) {
        return back()->withErrors([
            'miktar' => 'Göndermek istediğiniz miktar depodaki mevcut miktardan büyük olamaz!'
        ]);
    }

    // 3) Dağıtım kaydı oluştur
    Distribution::create($validated);

    // 4) Kaynak miktarını düşür
    $resource->miktar -= $request->miktar;
    $resource->save();

    return redirect()
        ->route('admin.distributions.index')
        ->with('success', 'Dağıtım başarıyla kaydedildi ve stok güncellendi.');
}


    // EDIT SAYFASI
    public function edit($id)
    {
        $distribution = Distribution::findOrFail($id);

        $resources = Resource::all();
        $regions = Region::all();
        $teams = ReliefTeam::all();

        return view('admin.distributions.edit', compact('distribution', 'resources', 'regions', 'teams'));
    }

    // UPDATE — DAĞITIM GÜNCELLEME
    public function update(Request $request, $id)
    {
        $request->validate([
            'resource_id' => 'required',
            'bolge_id' => 'required',
            'miktar' => 'required|numeric|min:1',
            'tarih' => 'required|date',
            'sorumlu_ekip_id' => 'required',
        ]);

        $distribution = Distribution::findOrFail($id);
        $resource = Resource::findOrFail($request->resource_id);

        // 🔥 ESKİ MİKTARI STOKA GERİ EKLE
        // (Dağıtım güncellenirken eski kayıt silinmiş gibi davranıyoruz)
        $resource->miktar += $distribution->miktar;

        // 🔥 YENİ MİKTAR KONTROLÜ
        if ($request->miktar > $resource->miktar) {
            return back()->withErrors([
                'miktar' => "Yeni miktar stoktan fazla! (Stok: {$resource->miktar})"
            ])->withInput();
        }

        // 🔥 STOKTAN DÜŞ
        $resource->miktar -= $request->miktar;
        $resource->save();

        // GÜNCELLE
        $distribution->update($request->all());

        return redirect()
            ->route('admin.distributions.index')
            ->with('success', 'Dağıtım başarıyla güncellendi!');
    }

    // DELETE — DAĞITIM SİLME
    public function destroy($id)
    {
        $distribution = Distribution::findOrFail($id);
        $resource = Resource::findOrFail($distribution->resource_id);

        // 🔥 SİLİNEN DAĞITIMIN MİKTARINI STOKA GERİ EKLE
        $resource->miktar += $distribution->miktar;
        $resource->save();

        $distribution->delete();

        return redirect()
            ->route('admin.distributions.index')
            ->with('success', 'Dağıtım silindi ve stok iade edildi.');
    }
}
