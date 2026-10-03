@extends('admin.layouts.admin')

@section('title', 'Ekipler')

@section('content')

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5 class="mb-0">Ekipler</h5>
        <a href="{{ route('admin.relief-teams.create') }}" class="btn btn-primary">Yeni Ekip Ekle</a>
    </div>

    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ekip Adı</th>
                    <th>Lider</th>
                    <th>Ekip Turu</th>
                    <th>Bölge</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($teams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>{{ $team->ekip_adi }}</td>
                    <td>{{ $team->lider_ad }}</td>
                    <td>{{ $team->ekip_turu }}</td>
                    <td>{{ $team->region->ad ?? '—' }}</td>
                    <td>{{ $team->durum }}</td>

                    <td>
                        <a href="{{ route('admin.relief-teams.edit', $team->id) }}"
                           class="btn btn-sm btn-warning">Düzenle</a>

                        <form action="{{ route('admin.relief-teams.destroy', $team->id) }}"
                              method="POST"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Silinsin mi?')">Sil</button>
                        </form>
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection
