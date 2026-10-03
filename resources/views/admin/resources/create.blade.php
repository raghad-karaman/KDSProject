@extends('admin.layouts.admin')

@section('title', 'Yeni Kaynak Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Yeni Kaynak Ekle</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.resources.store') }}" method="POST">
            @csrf

            <!-- Malzeme Adı -->
            <div class="mb-3">
                <label class="form-label">Malzeme Adı</label>
                <input type="text" name="malzeme_adi" class="form-control" required>
            </div>

            <!-- Miktar -->
            <div class="mb-3">
                <label class="form-label">Miktar</label>
                <input type="number" name="miktar" class="form-control" required>
            </div>

            <!-- Birim -->
            <div class="mb-3">
                <label class="form-label">Birim</label>
                <input type="text" name="birim" class="form-control" required placeholder="kg, adet, litre...">
            </div>

            <!-- Son Güncelleme -->
            <div class="mb-3">
                <label class="form-label">Son Güncelleme</label>
                <input type="datetime-local" name="son_guncelleme" class="form-control" required>
            </div>

            <!-- Kaydet Butonu -->
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">Geri</a>

        </form>
    </div>
</div>
@endsection
