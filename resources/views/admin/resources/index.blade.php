@extends('admin.layouts.admin')

@section('title', 'Mevcut Kaynaklar')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Mevcut Kaynaklar</h5>
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
            Yeni Kaynak Ekle
        </a>
    </div>

    {{-- 🔥 Silme / güncelleme sonrası alert mesajı --}}
    @if (session('success'))
        <div class="alert alert-success m-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Malzeme Adı</th>
                    <th>Miktar</th>
                    <th>Birim</th>
                    <th>Son Güncelleme</th>
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($resources as $resource)
                    <tr>
                        <td>{{ $resource->id }}</td>
                        <td>{{ $resource->malzeme_adi }}</td>
                        <td>{{ $resource->miktar }}</td>
                        <td>{{ $resource->birim }}</td>
                        <td>{{ $resource->son_guncelleme }}</td>

                        <td>
                            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn btn-sm btn-warning">
                                Düzenle
                            </a>

                            <form action="{{ route('admin.resources.destroy', $resource->id) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Bu kaynağı silmek istediğinizden emin misiniz?')">
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
