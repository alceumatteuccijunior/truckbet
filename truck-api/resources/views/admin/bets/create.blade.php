@extends('adminlte::page')
@section('title', 'Criar Apostas em Massa')
@section('content_header') <h1>Criar Apostas por Corrida</h1> @stop
@section('content')
<form action="{{ route('admin.bets.store') }}" method="POST"> @csrf
    <div class="form-group">
        <label>Corrida</label>
        <select name="race_id" class="form-control" required>
            @foreach($races as $race)
                <option value="{{ $race->id }}">{{ $race->nome }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Gerar Apostas</button>
</form>
@stop
