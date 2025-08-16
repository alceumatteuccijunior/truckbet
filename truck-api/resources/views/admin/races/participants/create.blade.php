@extends('adminlte::page')

@section('title', 'Adicionar Participante')

@section('content_header')
    <h1>Adicionar Participante à Corrida: {{ $race->nome }}</h1>
@stop

@section('content')
   <form action="{{ route('admin.races.participants.store', ['race' => $race->id]) }}" method="POST">

        @csrf

        <div class="form-group">
            <label for="driver_id">Piloto</label>
            <select name="driver_id" id="driver_id" class="form-control" required>
                <option value="">Selecione o piloto</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">{{ $driver->nome }}</option>
                @endforeach
            </select>
        </div>
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
        <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
        
    </form>
@stop
