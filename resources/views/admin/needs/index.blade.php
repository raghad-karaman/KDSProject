@extends('admin.layouts.admin')

@section('title', 'İhtiyaç Listesi')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">İhtiyaç Listesi</h5>
        <a href="{{ route('admin.needs.create') }}" class="btn btn-primary">
            Yeni İhtiyaç Ekle
        </a>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bölge</th>
                    <th>Mağdur</th>
                    <th>Su (Litre)</th>
                    <th>Gıda Paketi</th>
                    <th>Çadır</th>
                    <th>İlaç (Adet)</th>
                    
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($needs as $need)
                    <tr>
                        <td>{{ $need->id }}</td>
                        <td>{{ $need->region->ad }}</td>
                        <td>{{ $need->victim->ad_soyad ?? '—' }}</td>
                        <td>{{ $need->su_litre }}</td>
                        <td>{{ $need->gida_paketi }}</td>
                        <td>{{ $need->cadir }}</td>
                        <td>{{ $need->ilac_adet }}</td>
                        

                        <td>
                            <a href="{{ route('admin.needs.edit', $need->id) }}" class="btn btn-sm btn-warning">
                                Düzenle
                            </a>

                            <form action="{{ route('admin.needs.destroy', $need->id) }}"
                                  method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğine emin misin?')">
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
