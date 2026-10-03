@extends('admin.layouts.admin')

@section('title', 'Yeni Bildirim Ekle')

@section('content')
<div class="card">
    <div class="card-header"><h5>Yeni Bildirim Ekle</h5></div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.notifications.store') }}" method="POST">
            @csrf

            <label>Başlık</label>
            <input type="text" name="baslik" class="form-control mb-3" required>

            <label>İçerik</label>
            <textarea name="icerik" class="form-control mb-3" required></textarea>

            <label>Hedef Kullanıcı</label>
            <select name="hedef_kullanici_id" class="form-control mb-3" required>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                @endforeach
            </select>

            <label>Öncelik</label>
            <select name="oncelik" class="form-control mb-3" required>
                <option>Düşük</option>
                <option>Orta</option>
                <option>Yüksek</option>
            </select>

            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Geri</a>
        </form>

    </div>
</div>
@endsection
