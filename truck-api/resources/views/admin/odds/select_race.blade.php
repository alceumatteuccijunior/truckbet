@extends('adminlte::page')

@section('title', 'Selecionar Corrida')

@section('content_header')
    <h1>Selecionar Corrida para Definir Odds</h1>
@stop

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="Sucesso">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data/Hora</th>
                        <th>Status</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($races as $race)
                        <tr>
                            <td>{{ $race->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($race->data_hora)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-{{ $race->status === 'aberta' ? 'success' : 'danger' }}">
                                    {{ ucfirst($race->status) }}
                                </span>
                            </td>
                            <td>
                               <a href="{{ route('admin.odds.create', ['race' => $race->id]) }}" class="btn btn-primary btn-sm">Definir Odds</a>

<a href="{{ route('admin.odds.show', ['race' => $race->id]) }}" class="btn btn-info btn-sm">Ver Odds</a>

                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
