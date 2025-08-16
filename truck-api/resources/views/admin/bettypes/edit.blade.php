@extends('adminlte::page')

@section('title', 'Editar Tipo de Aposta')

@section('content_header')
    <h1>Editar Tipo de Aposta</h1>
@stop

@section('content')
    <form action="{{ route('admin.bettypes.update', $bettype->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" name="nome" value="{{ $bettype->nome }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" class="form-control">{{ $bettype->descricao }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('admin.bettypes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
@stop