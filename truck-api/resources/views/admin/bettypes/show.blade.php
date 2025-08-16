@extends('adminlte::page')

@section('title', 'Detalhes do Tipo de Aposta')

@section('content_header')
    <h1>Detalhes do Tipo de Aposta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <h5><strong>Nome:</strong> {{ $bettype->nome }}</h5>
            <p><strong>Descrição:</strong> {{ $bettype->descricao }}</p>
            <a href="{{ route('admin.bettypes.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
@stop