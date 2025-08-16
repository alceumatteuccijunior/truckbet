@extends('adminlte::page')

@section('title', 'Corridas')

@section('content_header')
    <h1>Lista de Corridas</h1>
    <a href="{{ route('admin.races.create') }}" class="btn btn-success">Nova Corrida</a>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data</th>
                <th>Local</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($races as $race)
                <tr>
                    <td>{{ $race->nome }}</td>
                    <td>{{ \Carbon\Carbon::parse($race->data_hora)->format('d/m/Y H:i') }}</td>
                    <td>{{ $race->cidade }}</td>
                    <td>
                        <span class="badge {{ $race->status == 'aberta' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($race->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.races.edit', $race->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.races.destroy', $race->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')">Excluir</button>
                            <a href="{{ route('admin.races.show', $race->id) }}" class="btn btn-info btn-sm">Visualizar</a>

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
