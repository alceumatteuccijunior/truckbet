@extends('adminlte::page')

@section('content_header')
    <h1>Odds - {{ $race->nome }}</h1>
@stop

@section('content')

{{-- Lista de odds já cadastradas --}}
<h3>Odds Existentes</h3>
@php
    $oddsExistem = false;
@endphp
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Piloto</th>
            <th>Tipo de Aposta</th>
            <th>Odd</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($participants as $participant)
            @foreach ($participant->odds ?? [] as $odd)
                @php $oddsExistem = true; @endphp
                <tr>
                    <td>{{ $participant->driver->nome }}</td>
                    <td>{{ $odd->betType->nome ?? '-' }}</td>
                    <td>{{ $odd->valor_odd }}</td>
                </tr>
            @endforeach
        @endforeach
        @if (!$oddsExistem)
            <tr><td colspan="3" class="text-muted">Nenhuma odd cadastrada ainda.</td></tr>
        @endif
    </tbody>
</table>

<hr>

{{-- Formulário para adicionar novas odds --}}
<h3>Adicionar Novas Odds</h3>

<form action="{{ route('admin.odds.store', $race->id) }}" method="POST">
    @csrf

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Piloto</th>
                <th>Tipo de Aposta</th>
                <th>Nova Odd</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participants as $participant)
                @foreach ($bettypes as $type)
                    @php
                        $jaCadastrada = $participant->odds->contains(fn($o) => $o->bet_type_id == $type->id);
                    @endphp

                    @if (!$jaCadastrada)
                        <tr>
                            <td>{{ $participant->driver->nome }}</td>
                            <td>{{ $type->nome }}</td>
                            <td>
                                <input type="number"
       name="odds[{{ $participant->id }}][{{ $type->id }}][valor_odd]"
       step="0.01"
       class="form-control"
       placeholder="Ex: 2.50"
       required>

<input type="hidden"
       name="odds[{{ $participant->id }}][{{ $type->id }}][bet_type_id]"
       value="{{ $type->id }}">
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn btn-success">Salvar Odds</button>
    <a href="{{ route('admin.odds.selectRace') }}" class="btn btn-secondary">Cancelar</a>
</form>

@stop