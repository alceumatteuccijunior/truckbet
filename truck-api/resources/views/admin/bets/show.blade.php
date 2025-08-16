@extends('adminlte::page')
@section('title', 'Detalhes da Corrida')
@section('content_header')
    <h1>{{ $race->nome }} - {{ \Carbon\Carbon::parse($race->data_hora)->format('d/m/Y H:i') }}</h1>
@stop
@section('content')

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Piloto</th>
            <th>Odd</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bets as $bet)
            <tr>
                <td>{{ $bet->raceParticipant->driver->nome }}</td>
                <td>{{ $bet->odd->valor_odd }}</td>
                <td>{{ $bet->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('admin.bets.index') }}" class="btn btn-secondary mt-3">Voltar</a>
@stop
