@extends('admin.layouts.admin')

@section('title', 'Mağdur Düzenle')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Mağdur Düzenle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.victims.update', $victim->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Ad Soyad</label>
                <input type="text" name="ad_soyad" class="form-control"
                       value="{{ $victim->ad_soyad }}">
            </div>

            <div class="mb-3">
                <label>Yaş</label>
                <input type="number" name="yas" class="form-control"
                       value="{{ $victim->yas }}">
            </div>

            <div class="mb-3">
                <label>Cinsiyet</label>
                <select name="cinsiyet" class="form-control">
                    <option {{ $victim->cinsiyet == 'Erkek' ? 'selected' : '' }}>Erkek</option>
                    <option {{ $victim->cinsiyet == 'Kadın' ? 'selected' : '' }}>Kadın</option>
                    <option {{ $victim->cinsiyet == 'Diğer' ? 'selected' : '' }}>Diğer</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Telefon</label>
                <input type="text" name="telefon" class="form-control"
                       value="{{ $victim->telefon }}">
            </div>

            <div class="mb-3">
                <label>Adres</label>
                <input type="text" name="adres" class="form-control"
                       value="{{ $victim->adres }}">
            </div>

            <div class="mb-3">
                <label>Bölge</label>
                <select name="bolge_id" class="form-control">
                    @foreach ($regions as $r)
                        <option value="{{ $r->id }}"
                            {{ $victim->bolge_id == $r->id ? 'selected' : '' }}>
                            {{ $r->ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kayıt Tarihi</label>
                <input type="datetime-local" name="kayit_tarihi"
                       class="form-control"
                       value="{{ date('Y-m-d\TH:i', strtotime($victim->kayit_tarihi)) }}">
            </div>

            <button class="btn btn-primary">Güncelle</button>
        </form>
    </div>
</div>

@endsection
