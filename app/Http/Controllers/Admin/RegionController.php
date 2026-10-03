<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegionController extends Controller
{
    /**
     * Bölge listesi
     */
    public function index()
    {
        $regions = Region::orderBy('created_at', 'desc')->get();
        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Yeni bölge ekleme formu
     */
    public function create()
    {
        return view('admin.regions.create');
    }

    /**
     * Yeni bölge kaydet
     */
    public function store(Request $request)
    {
        $request->validate([
            'ad'   => 'required|string|max:255',
            'il'   => 'required|string|max:255',
            'ilce' => 'required|string|max:255',
        ]);

        // 📍 Adres birleştir
        $address = "{$request->il} {$request->ilce} {$request->ad}";

        // 🌍 Koordinatları al
        $coords = $this->geocodeAddress($address);

Region::create([
    'ad'        => $request->ad,
    'il'        => $request->il,
    'ilce'      => $request->ilce,
    'latitude'  => $coords['latitude'] ?? null,
    'longitude' => $coords['longitude'] ?? null,
]);



        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge başarıyla eklendi.');
    }

    /**
     * Düzenleme formu
     */
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        return view('admin.regions.edit', compact('region'));
    }

    /**
     * Bölge güncelle
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'ad'   => 'required|string|max:255',
            'il'   => 'required|string|max:255',
            'ilce' => 'required|string|max:255',
        ]);

        $region = Region::findOrFail($id);

        // 📍 Adres değişmiş olabilir → tekrar hesapla
        $address = "{$request->il} {$request->ilce} {$request->ad}";
        $coords = $this->geocodeAddress($address);

        $region->update([
            'ad'        => $request->ad,
            'il'        => $request->il,
            'ilce'      => $request->ilce,
            'latitude'  => $coords['latitude'],
            'longitude' => $coords['longitude'],
        ]);

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge başarıyla güncellendi.');
    }

    /**
     * Bölge sil
     */
    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Bölge silindi.');
    }

    /**
     * 📌 Adres → Enlem / Boylam (Nominatim)
     */
    private function geocodeAddress(string $address): ?array
{
    $response = Http::withHeaders([
        'User-Agent' => 'KDSProject/1.0'
    ])->timeout(10)->get(
        'https://nominatim.openstreetmap.org/search',
        [
            'q'      => $address,
            'format' => 'json',
            'limit'  => 1,
        ]
    );

    if ($response->failed() || empty($response->json())) {
        return null; // ❗ abort YOK
    }

    return [
        'latitude'  => (float) $response->json()[0]['lat'],
        'longitude' => (float) $response->json()[0]['lon'],
    ];
}
} 