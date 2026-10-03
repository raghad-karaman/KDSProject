@extends('admin.layouts.admin')

@section('title', 'Rapor Düzenle')

@section('content')
<div class="card">
    <div class="card-header"><h5>Rapor Düzenle</h5></div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label>Rapor Türü</label>
            <select name="rapor_turu" class="form-control mb-3">
                <option {{ $report->rapor_turu == 'Günlük' ? 'selected':'' }}>Günlük</option>
                <option {{ $report->rapor_turu == 'Haftalık' ? 'selected':'' }}>Haftalık</option>
                <option {{ $report->rapor_turu == 'Aylık' ? 'selected':'' }}>Aylık</option>
            </select>

            <label>Bölge</label>
            <select name="bolge_id" class="form-control mb-3">
                @foreach ($regions as $b)
                    <option value="{{ $b->id }}" {{ $report->bolge_id == $b->id ? 'selected':'' }}>
                        {{ $b->ad }}
                    </option>
                @endforeach
            </select>

            <label>Yeni Dosya (PDF) — Opsiyonel</label>
            <input type="file" name="dosya" class="form-control mb-3">

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">Geri</a>
        </form>
    </div>
</div>
@endsection
