<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReliefTeam;
use App\Models\Region;
use Illuminate\Http\Request;

class ReliefTeamController extends Controller
{
    // LİSTELEME
    public function index()
    {
        $teams = ReliefTeam::with('region')->get();
        return view('admin.relief-teams.index', compact('teams'));
    }

    // EKLEME SAYFASI
    public function create()
    {
        $regions = Region::all();
        return view('admin.relief-teams.create', compact('regions'));
    }

    // EKLEME İŞLEMİ
    public function store(Request $request)
    {
        $request->validate([
            'ekip_adi' => 'required|string',
            'lider_ad' => 'required|string',
            'bolge_id' => 'required|integer',
            'iletisim' => 'required|string',
            'durum' => 'required|string',
        ]);

        ReliefTeam::create($request->all());

        return redirect()
            ->route('admin.relief-teams.index')
            ->with('success', 'Ekip başarıyla eklendi!');
    }

    // DÜZENLEME SAYFASI
    public function edit($id)
    {
        $team = ReliefTeam::findOrFail($id);
        $regions = Region::all();
        return view('admin.relief-teams.edit', compact('team', 'regions'));
    }

    // GÜNCELLEME
    public function update(Request $request, $id)
    {
        $request->validate([
            'ekip_adi' => 'required|string',
            'lider_ad' => 'required|string',
            'bolge_id' => 'required|integer',
            'iletisim' => 'required|string',
            'durum' => 'required|string',
        ]);

        $team = ReliefTeam::findOrFail($id);
        $team->update($request->all());

        return redirect()
            ->route('admin.relief-teams.index')
            ->with('success', 'Ekip başarıyla güncellendi!');
    }

    // SİLME
    public function destroy($id)
    {
        $team = ReliefTeam::findOrFail($id);
        $team->delete();

        return redirect()
            ->route('admin.relief-teams.index')
            ->with('success', 'Ekip silindi!');
    }
}
