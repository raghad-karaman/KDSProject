<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    // LISTE
    public function index()
    {
        $reports = Report::with(['region', 'creator'])->get();
        return view('admin.reports.index', compact('reports'));
    }

    // CREATE SAYFASI
    public function create()
    {
        $regions = Region::all();
        return view('admin.reports.create', compact('regions'));
    }

    // STORE — RAPOR EKLEME
    public function store(Request $request)
    {
        $request->validate([
            'rapor_turu' => 'required|in:Günlük,Haftalık,Aylık',
            'bolge_id' => 'required|exists:regions,id',
            'dosya' => 'required|file|mimes:pdf|max:2048',
        ]);

        // Dosyayı kaydet
        $path = $request->file('dosya')->store('raporlar', 'public');

        Report::create([
            'rapor_turu' => $request->rapor_turu,
            'bolge_id' => $request->bolge_id,
            'olusturan_id' => Auth::id(),
            'dosya_yolu' => $path,
            'olusturma_tarihi' => now(),
        ]);

        return redirect()->route('admin.reports.index')
            ->with('success', 'Rapor başarıyla oluşturuldu!');
    }

    // EDIT SAYFASI
    public function edit($id)
    {
        $report = Report::findOrFail($id);
        $regions = Region::all();

        return view('admin.reports.edit', compact('report', 'regions'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $request->validate([
            'rapor_turu' => 'required|in:Günlük,Haftalık,Aylık',
            'bolge_id' => 'required|exists:regions,id',
            'dosya' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Dosya güncellenecekse
        if ($request->hasFile('dosya')) {
            $path = $request->file('dosya')->store('raporlar', 'public');
            $report->dosya_yolu = $path;
        }

        $report->rapor_turu = $request->rapor_turu;
        $report->bolge_id = $request->bolge_id;
        $report->save();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Rapor güncellendi!');
    }

    // DELETE
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Rapor silindi!');
    }
}
