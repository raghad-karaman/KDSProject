@extends('admin.layouts.admin')

@section('title', 'Yeni Dağıtım Ekle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Yeni Dağıtım Ekle</h5>
    </div>

    <div class="card-body">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


        <form action="{{ route('admin.distributions.store') }}" method="POST">
            @csrf

            <label>Kaynak</label>
            <select name="resource_id" class="form-control mb-3" required>
                @foreach ($resources as $r)
                    <option value="{{ $r->id }}">{{ $r->malzeme_adi }}</option>
                @endforeach
            </select>

            <label>Bölge</label>
            <select name="bolge_id" class="form-control mb-3" required>
                @foreach ($regions as $b)
                    <option value="{{ $b->id }}">{{ $b->ad }} ({{ $b->ilce }})</option>
                @endforeach
            </select>

            <label>Miktar</label>
            <input type="number" name="miktar" class="form-control mb-3" required>

            <label>Tarih</label>
            <input type="date" name="tarih" class="form-control mb-3" required>

            <label>Sorumlu Ekip</label>
            <select name="sorumlu_ekip_id" class="form-control mb-3" required>
                @foreach ($teams as $t)
                    <option value="{{ $t->id }}">{{ $t->ekip_adi }}</option>
                @endforeach
            </select>

            <button class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.distributions.index') }}" class="btn btn-secondary">Geri</a>
        </form>

    </div>
</div>
@endsection
