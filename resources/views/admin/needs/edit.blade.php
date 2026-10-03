@extends('admin.layouts.admin')

@section('title', 'İhtiyaç Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">İhtiyaç Düzenle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.needs.update', $need->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Bölge --}}
            <div class="mb-3">
                <label class="form-label">Bölge Seç</label>
                <select name="bolge_id" class="form-control" required>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}"
                            {{ $need->bolge_id == $region->id ? 'selected' : '' }}>
                            {{ $region->il }} / {{ $region->ilce }} - {{ $region->ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Mağdur --}}
            <div class="mb-3">
                <label class="form-label">Mağdur Seç</label>
                <select name="victim_id" class="form-control" required>
                    @foreach ($victims as $victim)
                        <option value="{{ $victim->id }}"
                            {{ $need->victim_id == $victim->id ? 'selected' : '' }}>
                            {{ $victim->ad }} {{ $victim->soyad }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Su --}}
            <div class="mb-3">
                <label class="form-label">Su (Litre)</label>
                <input type="number" name="su_litre" class="form-control"
                       value="{{ $need->su_litre }}" required>
            </div>

            {{-- Gıda Paketi --}}
            <div class="mb-3">
                <label class="form-label">Gıda Paketi</label>
                <input type="number" name="gida_paketi" class="form-control"
                       value="{{ $need->gida_paketi }}" required>
            </div>

            {{-- Çadır --}}
            <div class="mb-3">
                <label class="form-label">Çadır</label>
                <input type="number" name="cadir" class="form-control"
                       value="{{ $need->cadir }}" required>
            </div>

            {{-- İlaç --}}
            <div class="mb-3">
                <label class="form-label">İlaç (Adet)</label>
                <input type="number" name="ilac_adet" class="form-control"
                       value="{{ $need->ilac_adet }}" required>
            </div>

            {{-- Öncelik --}}
            <div class="mb-3">
                <label class="form-label">Öncelik</label>
                <select name="oncelik" class="form-control" required>
                    <option value="Düşük"  {{ $need->oncelik == 'Düşük' ? 'selected' : '' }}>Düşük</option>
                    <option value="Orta"   {{ $need->oncelik == 'Orta' ? 'selected' : '' }}>Orta</option>
                    <option value="Yüksek" {{ $need->oncelik == 'Yüksek' ? 'selected' : '' }}>Yüksek</option>
                </select>
            </div>

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.needs.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>
@endsection
