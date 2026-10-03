@extends('admin.layouts.admin')

@section('title', 'Dağıtımlar')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Dağıtımlar</h5>
        <a href="{{ route('admin.distributions.create') }}" class="btn btn-primary">
            Yeni Dağıtım Ekle
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kaynak</th>
                    <th>Bölge</th>
                    <th>Miktar</th>
                    <th>Tarih</th>
                    <th>Sorumlu Ekip</th>
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($distributions as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->resource->malzeme_adi }}</td>
                        <td>{{ $d->region->ad }}</td>
                        <td>{{ $d->miktar }}</td>
                        <td>{{ $d->tarih }}</td>
                        <td>{{ $d->team->ekip_adi }}</td>

                        <td>
                            <a href="{{ route('admin.distributions.edit', $d->id) }}" class="btn btn-warning btn-sm">Düzenle</a>

                            <form action="{{ route('admin.distributions.destroy', $d->id) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('Silmek istediğinize emin misiniz?')">
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
