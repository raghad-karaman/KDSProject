@extends('admin.layouts.admin')

@section('title', 'Kaynağı Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Kaynağı Düzenle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.resources.update', $resource->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Malzeme Adı -->
            <div class="mb-3">
                <label class="form-label">Malzeme Adı</label>
                <input type="text" name="malzeme_adi" class="form-control" value="{{ $resource->malzeme_adi }}" required>
            </div>

            <!-- Miktar -->
            <div class="mb-3">
                <label class="form-label">Miktar</label>
                <input type="number" name="miktar" class="form-control" value="{{ $resource->miktar }}" required>
            </div>

            <!-- Birim -->
            <div class="mb-3">
                <label class="form-label">Birim</label>
                <input type="text" name="birim" class="form-control" value="{{ $resource->birim }}" required>
            </div>

            <!-- Son Güncelleme -->
            <div class="mb-3">
                <label class="form-label">Son Güncelleme</label>
                <input type="datetime-local" name="son_guncelleme"
                       class="form-control"
                       value="{{ \Carbon\Carbon::parse($resource->son_guncelleme)->format('Y-m-d\TH:i') }}"
                       required>
            </div>

            <!-- Kaydet Butonu -->
            <button type="submit" class="btn btn-success">Güncelle</button>
            <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">Geri</a>

        </form>
    </div>
</div>
@endsection
