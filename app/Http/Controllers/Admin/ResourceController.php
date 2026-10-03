<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    // LISTELEME
    public function index()
    {
        $resources = Resource::all(); // region ilişkisi olmadığı için sade liste
        return view('admin.resources.index', compact('resources'));
    }

    // EKLEME SAYFASI
    public function create()
    {
        return view('admin.resources.create');
    }

    // EKLEME İŞLEMİ
    public function store(Request $request)
    {
        $request->validate([
            'malzeme_adi' => 'required|string',
            'miktar' => 'required|numeric',
            'birim' => 'required|string',
            'depo_id' => 'nullable|numeric',
            'son_guncelleme' => 'nullable|date',
        ]);

        Resource::create($request->all());

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Kaynak başarıyla eklendi!');
    }

    // DÜZENLEME SAYFASI
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        return view('admin.resources.edit', compact('resource'));
    }

    // GÜNCELLEME
    public function update(Request $request, $id)
    {
        $request->validate([
            'malzeme_adi' => 'required|string',
            'miktar' => 'required|numeric',
            'birim' => 'required|string',
            'depo_id' => 'nullable|numeric',
            'son_guncelleme' => 'nullable|date',
        ]);

        $resource = Resource::findOrFail($id);
        $resource->update($request->all());

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Kaynak başarıyla güncellendi!');
    }

    // SİLME
    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        return redirect()
            ->route('admin.resources.index')
            ->with('success', 'Kaynak silindi!');
    }
}
