@extends('adminlte::page')

@section('title', 'Editar Participante')

@section('content_header')
    <h1>Editar Participante</h1>
@stop

@section('content')
    <form action="{{ route('admin.participants.update', $participant) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="race_id">Corrida</label>
            <select name="race_id" class="form-control" required>
                @foreach($races as $race)
                    <option value="{{ $race->id }}" {{ $participant->race_id == $race->id ? 'selected' : '' }}>
                        {{ $race->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="driver_id">Motorista</label>
            <select name="driver_id" class="form-control" required>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}" {{ $participant->driver_id == $driver->id ? 'selected' : '' }}>
                        {{ $driver->nome ?? $driver->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="truck_number">Número do Caminhão</label>
            <input type="text" name="truck_number" value="{{ $participant->truck_number }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="team">Equipe</label>
            <input type="text" name="team" value="{{ $participant->team }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
@stop
