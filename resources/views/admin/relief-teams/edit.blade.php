@extends('admin.layouts.admin')

@section('title', 'Ekip Düzenle')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Ekip Düzenle</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.relief-teams.update', $team->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Ekip Adı</label>
                <input type="text" name="ekip_adi" class="form-control"
                       value="{{ $team->ekip_adi }}" required>
            </div>

            <div class="mb-3">
                <label>Lider Adı</label>
                <input type="text" name="lider_ad" class="form-control"
                       value="{{ $team->lider_ad }}" required>
            </div>
            <div class="mb-3">
                <label>Ekip Turu</label>
                <input type="text" name="ekip_turu" class="form-control"
                       value="{{ $team->ekip_turu }}" required>
            </div>
            <div class="mb-3">
                <label>Bölge</label>
                <select name="bolge_id" class="form-control" required>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}"
                                {{ $team->bolge_id == $region->id ? 'selected' : '' }}>
                            {{ $region->ad }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>İletişim</label>
                <input type="text" name="iletisim" class="form-control"
                       value="{{ $team->iletisim }}" required>
            </div>

            <div class="mb-3">
                <label>Durum</label>
                <select name="durum" class="form-control">
                    <option value="Hazır" {{ $team->durum == 'Hazır' ? 'selected' : '' }}>Hazır</option>
                    <option value="Görevde" {{ $team->durum == 'Görevde' ? 'selected' : '' }}>Görevde</option>
                    <option value="Dinleniyor" {{ $team->durum == 'Dinleniyor' ? 'selected' : '' }}>Dinleniyor</option>
                </select>
            </div>

            <button class="btn btn-success">Güncelle</button>
        </form>

    </div>
</div>

@endsection
