@extends('admin.layouts.admin')

@section('title', 'Bölge Düzenle')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Bölge Düzenle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.regions.update', $region->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Bölge Adı</label>
                <input type="text" name="ad" class="form-control" value="{{ $region->ad }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">İl</label>
                <input type="text" name="il" class="form-control" value="{{ $region->il }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">İlçe</label>
                <input type="text" name="ilce" class="form-control" value="{{ $region->ilce }}" required>
            </div>

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>

@endsection
