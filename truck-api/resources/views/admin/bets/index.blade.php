@extends('adminlte::page')
@section('title', 'Apostas')
@section('content_header') <h1>Lista de Apostas</h1> @stop
@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.bets.create') }}" class="btn btn-success mb-3">Nova Aposta</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Corrida</th>
            <th>Data</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @php
            $agrupadas = $bets->groupBy('race_id');
        @endphp

        @foreach($agrupadas as $raceId => $betsDaCorrida)
            @php $race = $betsDaCorrida->first()->race; @endphp
            <tr>
                <td>{{ $race->nome }}</td>
                <td>{{ \Carbon\Carbon::parse($race->data_hora)->format('d/m/Y H:i') }}</td>
                <td>
                    <span class="badge badge-{{ $race->status === 'aberta' ? 'success' : 'secondary' }}">
                        {{ ucfirst($race->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.bets.show', $raceId) }}" class="btn btn-sm btn-primary">
                        Ver Participantes
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop
