@extends('admin.layouts.admin')

@section('title', 'Yeni Ekip Ekle')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Yeni Ekip Ekle</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.relief-teams.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Ekip Adı</label>
                <input type="text" name="ekip_adi" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Lider Adı</label>
                <input type="text" name="lider_ad" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Ekip Turu</label>
                <input type="text" name="ekip_turu" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Bölge</label>
                <select name="bolge_id" class="form-control" required>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->ad }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>İletişim</label>
                <input type="text" name="iletisim" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Durum</label>
                <select name="durum" class="form-control">
                    <option value="Hazır">Hazır</option>
                    <option value="Görevde">Görevde</option>
                    <option value="Dinleniyor">Dinleniyor</option>
                </select>
            </div>

            <button class="btn btn-success">Kaydet</button>
        </form>

    </div>
</div>

@endsection
