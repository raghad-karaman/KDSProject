@extends('admin.layouts.admin')

@section('title', 'Bildirim Düzenle')

@section('content')
<div class="card">
    <div class="card-header"><h5>Bildirim Düzenle</h5></div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.notifications.update', $notification->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Başlık</label>
            <input type="text" name="baslik" class="form-control mb-3" value="{{ $notification->baslik }}" required>

            <label>İçerik</label>
            <textarea name="icerik" class="form-control mb-3" required>{{ $notification->icerik }}</textarea>

            <label>Hedef Kullanıcı</label>
            <select name="hedef_kullanici_id" class="form-control mb-3" required>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ $notification->hedef_kullanici_id == $u->id ? 'selected':'' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>

            <label>Öncelik</label>
            <select name="oncelik" class="form-control mb-3" required>
                <option {{ $notification->oncelik == 'Düşük' ? 'selected':'' }}>Düşük</option>
                <option {{ $notification->oncelik == 'Orta' ? 'selected':'' }}>Orta</option>
                <option {{ $notification->oncelik == 'Yüksek' ? 'selected':'' }}>Yüksek</option>
            </select>

            <label>Durum</label>
            <select name="durum" class="form-control mb-3" required>
                <option {{ $notification->durum == 'Okunmadı' ? 'selected':'' }}>Okunmadı</option>
                <option {{ $notification->durum == 'Okundu' ? 'selected':'' }}>Okundu</option>
            </select>

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Geri</a>
        </form>

    </div>
</div>
@endsection
