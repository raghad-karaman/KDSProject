@extends('admin.layouts.admin')

@section('title', 'Yeni Bölge Ekle')

@section('content')

<div class="card">
    <div class="card-header">
        <h5>Yeni Bölge Ekle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.regions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Bölge Adı</label>
                <input type="text" name="ad" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">İl</label>
                <input type="text" name="il" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">İlçe</label>
                <input type="text" name="ilce" class="form-control" required>
            </div>

            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>

@endsection
