@extends('adminlte::page')

@section('title', 'Motoristas')

@section('content_header')
    <h1>Lista de Motoristas</h1>
    <a href="{{ route('admin.drivers.create') }}" class="btn btn-success">Novo Motorista</a>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Marca</th>
                <th>Nº Caminhão</th>
                <th>Cidade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($drivers as $driver)
                <tr>
                    <td>{{ $driver->nome }}</td>
                    <td>{{ $driver->categoria }}</td>
                    <td>{{ $driver->marca }}</td>
                    <td>{{ $driver->numero_camiao }}</td>
                    <td>{{ $driver->cidade }}</td>
                    <td>
                        <span class="badge {{ $driver->status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($driver->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.drivers.edit', $driver->id) }}" class="btn btn-sm btn-primary">Editar</a>
                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Excluir motorista?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop
