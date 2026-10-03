@extends('admin.layouts.admin')

@section('title', 'Raporlar')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Raporlar</h5>
        <a href="{{ route('admin.reports.create') }}" class="btn btn-primary">Yeni Rapor Ekle</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tür</th>
                    <th>Bölge</th>
                    <th>Oluşturan</th>
                    <th>Tarih</th>
                    <th>Dosya</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->rapor_turu }}</td>
                    <td>{{ $r->region->ad }}</td>
                    <td>{{ $r->creator->name }}</td>
                    <td>{{ $r->olusturma_tarihi }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $r->dosya_yolu) }}" target="_blank" class="btn btn-sm btn-info">
                            Görüntüle
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.reports.edit', $r->id) }}" class="btn btn-warning btn-sm">Düzenle</a>

                        <form action="{{ route('admin.reports.destroy', $r->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                Sil
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
