@extends('admin.layouts.admin')

@section('title', 'Bölgeler')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Bölgeler</h5>
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary">Yeni Bölge Ekle</a>
    </div>

    {{-- 🔥 Silme / Güncelleme Sonrası Alert --}}
    @if (session('success'))
        <div class="alert alert-success m-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bölge Adı</th>
                    <th>İl</th>
                    <th>İlçe</th>
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($regions as $region)
                    <tr>
                        <td>{{ $region->id }}</td>
                        <td>{{ $region->ad }}</td>
                        <td>{{ $region->il }}</td>
                        <td>{{ $region->ilce }}</td>
                        <td>
                            <a href="{{ route('admin.regions.edit', $region->id) }}"
                               class="btn btn-warning btn-sm">Düzenle</a>

                            <form action="{{ route('admin.regions.destroy', $region->id) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Bu bölgeyi silmek istediğinizden emin misiniz?')">
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
