@extends('admin.layouts.admin')

@section('title', 'Bildirimler')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Bildirimler</h5>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">Yeni Bildirim Ekle</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Başlık</th>
                    <th>Hedef Kullanıcı</th>
                    <th>Öncelik</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $n)
                <tr>
                    <td>{{ $n->id }}</td>
                    <td>{{ $n->baslik }}</td>
                    <td>{{ $n->targetUser->name }}</td>
                    <td>{{ $n->oncelik }}</td>
                    <td>{{ $n->durum }}</td>
                    <td>{{ $n->olusturma_tarihi }}</td>
                    <td>
                        <a href="{{ route('admin.notifications.edit', $n->id) }}" class="btn btn-warning btn-sm">
                            Düzenle
                        </a>

                        <form action="{{ route('admin.notifications.destroy', $n->id) }}"
                              method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Bildirim silinsin mi?')">
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
