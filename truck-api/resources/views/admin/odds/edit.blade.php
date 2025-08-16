@extends('adminlte::page')
@section('title', 'Editar Odd')
@section('content_header')
    <h1>Editar Odd</h1>
@stop
@section('content')
<form action="{{ route('admin.odds.update', $odd) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Corrida</label>
        <select name="race_id" class="form-control">
            @foreach($races as $race)
                <option value="{{ $race->id }}" {{ $race->id == $odd->race_id ? 'selected' : '' }}>{{ $race->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Piloto</label>
        <select name="driver_id" class="form-control">
            @foreach($drivers as $driver)
                <option value="{{ $driver->id }}" {{ $driver->id == $odd->driver_id ? 'selected' : '' }}>{{ $driver->nome }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Odd</label>
        <input type="number" name="odd" class="form-control" step="0.01" value="{{ $odd->odd }}" required>
    </div>
    <button class="btn btn-primary">Atualizar</button>
</form>
@stop
