@extends('admin.layouts.admin')

@section('title', 'Yeni İhtiyaç Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni İhtiyaç Ekle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.needs.store') }}" method="POST">
            @csrf

            {{-- Bölge --}}
            <div class="mb-3">
                <label class="form-label">Bölge Seç</label>
                <select name="bolge_id" class="form-control" required>
                    <option value="">Bölge seçiniz</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->il }} / {{ $region->ilce }} - {{ $region->ad }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Mağdur --}}
            <div class="mb-3">
                <label class="form-label">Mağdur Seç</label>
                <select name="victim_id" class="form-control" required>
                    <option value="">Mağdur seçiniz</option>
                    @foreach ($victims as $victim)
                        <option value="{{ $victim->id }}">{{ $victim->ad }} {{ $victim->soyad }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Su --}}
            <div class="mb-3">
                <label class="form-label">Su (Litre)</label>
                <input type="number" name="su_litre" class="form-control" required>
            </div>

            {{-- Gıda Paketi --}}
            <div class="mb-3">
                <label class="form-label">Gıda Paketi</label>
                <input type="number" name="gida_paketi" class="form-control" required>
            </div>

            {{-- Çadır --}}
            <div class="mb-3">
                <label class="form-label">Çadır</label>
                <input type="number" name="cadir" class="form-control" required>
            </div>

            {{-- İlaç --}}
            <div class="mb-3">
                <label class="form-label">İlaç (Adet)</label>
                <input type="number" name="ilac_adet" class="form-control" required>
            </div>

            {{-- Öncelik --}}
            <div class="mb-3">
                <label class="form-label">Öncelik</label>
                <select name="oncelik" class="form-control" required>
                    <option value="Düşük">Düşük</option>
                    <option value="Orta">Orta</option>
                    <option value="Yüksek">Yüksek</option>
                </select>
            </div>

            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.needs.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>
@endsection
