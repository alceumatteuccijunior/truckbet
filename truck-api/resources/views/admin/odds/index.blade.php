@extends('adminlte::page')
@section('title', 'Odds')
@section('content_header')
    <h1>Odds Registradas</h1>
    <a href="{{ route('admin.odds.create') }}" class="btn btn-success">Nova Odd</a>
@stop
@section('content')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Corrida</th>
            <th>Piloto</th>
            <th>Odd</th>
            <th>Tipo de Aposta</th>
            <th>Atualizado em</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
    @foreach($odds as $odd)
        <tr>
            <td>{{ $odd->raceParticipant->race->nome ?? '-' }}</td>
            <td>{{ $odd->raceParticipant->driver->nome ?? '-' }}</td>
            <td>{{ $odd->valor_odd }}</td>
            <td>{{ $odd->betType->nome ?? '-' }}</td>
            <td>{{ $odd->updated_at->format('d/m/Y H:i') }}</td>
            <td>
                <a href="{{ route('admin.odds.edit', $odd->id) }}" class="btn btn-primary btn-sm">Editar</a>
                <form method="POST" action="{{ route('admin.odds.destroy', $odd->id) }}" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza?')">Excluir</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>
</table>
@stop
