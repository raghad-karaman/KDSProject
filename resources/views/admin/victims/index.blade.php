@extends('admin.layouts.admin')

@section('title', 'Mağdurlar')

@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Mağdurlar</h5>
        <a href="{{ route('admin.victims.create') }}" class="btn btn-primary">Yeni Mağdur Ekle</a>
    </div>

    {{-- Alert --}}
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
                    <th>Ad Soyad</th>
                    <th>Yaş</th>
                    <th>Cinsiyet</th>
                    <th>Telefon</th>
                    <th>Bölge</th>
                    <th>İşlemler</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($victims as $victim)
                    <tr>
                        <td>{{ $victim->id }}</td>
                        <td>{{ $victim->ad_soyad }}</td>
                        <td>{{ $victim->yas }}</td>
                        <td>{{ $victim->cinsiyet }}</td>
                        <td>{{ $victim->telefon }}</td>
                        <td>{{ $victim->region->ad ?? '—' }}</td>

                        <td>
                            <a href="{{ route('admin.victims.edit', $victim->id) }}"
                               class="btn btn-warning btn-sm">Düzenle</a>

                            <form action="{{ route('admin.victims.destroy', $victim->id) }}"
                                  method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Silinsin mi?')">
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
