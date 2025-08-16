@extends('adminlte::page')

@section('title', 'Participantes')

@section('content_header')
    <h1>Lista de Participantes</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.participants.create') }}" class="btn btn-success mb-3">Adicionar Participante</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Corrida</th>
                <th>Motorista</th>
                <th>Número do Caminhão</th>
                <th>Equipe</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($participants as $participant)
                <tr>
                    <td>{{ $participant->race->nome ?? '---' }}</td>
                    <td>{{ $participant->driver->nome ?? $participant->driver->name ?? '---' }}</td>
                    <td>{{ $participant->truck_number }}</td>
                    <td>{{ $participant->team }}</td>
                    <td>
                        <a href="{{ route('admin.participants.edit', $participant) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.participants.destroy', $participant) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
