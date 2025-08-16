@extends('adminlte::page')

@section('title', 'Tipos de Aposta')

@section('content_header')
    <h1>Tipos de Aposta</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.bettypes.create') }}" class="btn btn-primary mb-3">Novo Tipo</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bettypes as $bettype)
                <tr>
                    <td>{{ $bettype->nome }}</td>
                    <td>{{ $bettype->descricao }}</td>
                    <td>
                        <a href="{{ route('admin.bettypes.edit', $bettype->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('admin.bettypes.destroy', $bettype->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Excluir esse tipo?')" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhum tipo de aposta cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop