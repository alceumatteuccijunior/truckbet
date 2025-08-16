@extends('adminlte::page')

@section('title', 'Odds da Corrida')

@section('content_header')
    <h1>Editar Odds - Corrida: {{ $race->nome }}</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.odds.store', $race->id) }}" method="POST">
        @csrf

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Piloto</th>
                    <th>Tipo de Aposta</th>
                    <th>Odd Atual</th>
                    <th>Nova Odd</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participants as $participant)
                    @foreach ($participant->odds as $odd)
                        <tr>
                            <td>{{ $participant->driver->nome }}</td>
                            <td>{{ $odd->betType->nome ?? '-' }}</td>
                            <td>{{ $odd->valor_odd }}</td>
                            <td>
                                <input type="number"
                                       name="odds[{{ $participant->id }}][{{ $odd->bet_type_id }}][valor_odd]"
                                       class="form-control"
                                       step="0.01"
                                       value="{{ old("odds.{$participant->id}.{$odd->bet_type_id}.valor_odd", $odd->valor_odd) }}">

                                <input type="hidden"
                                       name="odds[{{ $participant->id }}][{{ $odd->bet_type_id }}][bet_type_id]"
                                       value="{{ $odd->bet_type_id }}">
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
@stop