@extends('adminlte::page')

@section('title', 'Detalhes da Corrida')

@section('content_header')
    <h1>Corrida: {{ $race->nome }}</h1>
@stop

@section('content')

    <x-adminlte-callout theme="info" title="Informações da Corrida">
        <strong>Data/Hora:</strong> {{ \Carbon\Carbon::parse($race->data_hora)->format('d/m/Y H:i') }}<br>
        <strong>Status:</strong> {{ ucfirst($race->status) }}<br>
        <strong>Local:</strong> {{ $race->cidade }} - {{ $race->estado }}
    </x-adminlte-callout>

    <h4>Pilotos Participantes</h4>

    @if($race->participants->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Cidade</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($race->participants as $participant)
    <tr>
        <td>{{ $participant->driver->nome }}</td>
        <td>{{ $participant->driver->categoria }}</td>
        <td>{{ $participant->driver->cidade }}</td>
        <td>{{ ucfirst($participant->driver->status) }}</td>
        <td>
            <form action="{{ route('admin.races.participants.destroy', [$race->id, $participant->id]) }}" method="POST" onsubmit="return confirm('Remover este piloto da corrida?')">
                @csrf
                @method('DELETE')
                @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
                <button class="btn btn-danger btn-sm">Remover</button>
            </form>
        </td>
    </tr>
@endforeach
            </tbody>
        </table>
    @else
        <p>Nenhum piloto adicionado a essa corrida.</p>
    @endif

@stop
