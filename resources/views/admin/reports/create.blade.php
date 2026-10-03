@extends('admin.layouts.admin')

@section('title', 'Yeni Rapor Ekle')

@section('content')
<div class="card">
    <div class="card-header"><h5>Yeni Rapor Ekle</h5></div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label>Rapor Türü</label>
            <select name="rapor_turu" class="form-control mb-3" required>
                <option>Günlük</option>
                <option>Haftalık</option>
                <option>Aylık</option>
            </select>

            <label>Bölge</label>
            <select name="bolge_id" class="form-control mb-3" required>
                @foreach ($regions as $b)
                    <option value="{{ $b->id }}">{{ $b->ad }} ({{ $b->ilce }})</option>
                @endforeach
            </select>

            <label>Rapor Dosyası (PDF)</label>
            <input type="file" name="dosya" class="form-control mb-3" required>

            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>
@endsection
