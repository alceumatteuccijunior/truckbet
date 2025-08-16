@extends('adminlte::page')

@section('title', 'Selecionar Corrida')

@section('content_header')
    <h1>Escolha a corrida para adicionar participantes</h1>
@stop

@section('content')
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Data</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach($races as $race)
                <tr>
                    <td>{{ $race->nome }}</td>
                    <td>{{ $race->data_hora }}</td>
                    <td>{{ $race->status }}</td>
                    <td>
                        <a href="{{ route('admin.races.participants.create', ['race' => $race->id]) }}" class="btn btn-primary btn-sm">
                            Selecionar Participantes
                        </a>

                    </td>
                </tr>
                @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
            @endforeach
        </tbody>
    </table>
@stop
