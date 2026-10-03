@extends('admin.layouts.admin')

@section('title', 'Dağıtımı Düzenle')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Dağıtım Düzenle</h5>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.distributions.update', $distribution->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Kaynak</label>
            <select name="resource_id" class="form-control mb-3" required>
                @foreach ($resources as $r)
                    <option value="{{ $r->id }}" {{ $distribution->resource_id == $r->id ? 'selected' : '' }}>
                        {{ $r->malzeme_adi }}
                    </option>
                @endforeach
            </select>

            <label>Bölge</label>
            <select name="bolge_id" class="form-control mb-3" required>
                @foreach ($regions as $b)
                    <option value="{{ $b->id }}" {{ $distribution->bolge_id == $b->id ? 'selected' : '' }}>
                        {{ $b->ad }}
                    </option>
                @endforeach
            </select>

            <label>Miktar</label>
            <input type="number" name="miktar" class="form-control mb-3" value="{{ $distribution->miktar }}" required>

            <label>Tarih</label>
            <input type="date" name="tarih" class="form-control mb-3"
                   value="{{ $distribution->tarih }}" required>

            <label>Sorumlu Ekip</label>
            <select name="sorumlu_ekip_id" class="form-control mb-3" required>
                @foreach ($teams as $t)
                    <option value="{{ $t->id }}" {{ $distribution->sorumlu_ekip_id == $t->id ? 'selected' : '' }}>
                        {{ $t->ekip_adi }}
                    </option>
                @endforeach
            </select>

            <button class="btn btn-primary">Güncelle</button>
            <a href="{{ route('admin.distributions.index') }}" class="btn btn-secondary">Geri</a>
        </form>

    </div>
</div>
@endsection
