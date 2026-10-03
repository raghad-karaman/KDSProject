@extends('admin.layouts.admin')

@section('title', 'Yeni Mağdur')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Yeni Mağdur Ekle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.victims.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Ad Soyad</label>
                <input type="text" name="ad_soyad" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Yaş</label>
                <input type="number" name="yas" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Cinsiyet</label>
                <select name="cinsiyet" class="form-control">
                    <option value="Erkek">Erkek</option>
                    <option value="Kadın">Kadın</option>
                    <option value="Diğer">Diğer</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Telefon</label>
                <input type="text" name="telefon" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Adres</label>
                <input type="text" name="adres" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Bölge</label>
                <select name="bolge_id" class="form-control" required>
                    @foreach ($regions as $r)
                        <option value="{{ $r->id }}">{{ $r->ad }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kayıt Tarihi</label>
                <input type="datetime-local" name="kayit_tarihi" class="form-control" required>
            </div>

            <button class="btn btn-primary">Kaydet</button>
        </form>
    </div>
</div>

@endsection
