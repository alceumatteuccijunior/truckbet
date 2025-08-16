@extends('adminlte::page')

@section('title', 'Editar Corrida')

@section('content_header')
    <h1>Editar Corrida</h1>
@stop

@section('content')
    <x-adminlte-card title="Atualizar Corrida" theme="primary" icon="fas fa-flag-checkered">
        <form action="{{ route('admin.races.update', $race->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-adminlte-input name="nome" label="Nome" value="{{ $race->nome }}" required />
            <x-adminlte-input name="circuito" label="Circuito" value="{{ $race->circuito }}" required />
            <x-adminlte-input name="cidade" label="Cidade" value="{{ $race->cidade }}" required />
            <x-adminlte-input name="estado" label="Estado" value="{{ $race->estado }}" required />
            <x-adminlte-input name="data_hora" label="Data e Hora" type="datetime-local"
                value="{{ \Carbon\Carbon::parse($race->data_hora)->format('Y-m-d\TH:i') }}" required />

            <x-adminlte-select name="status" label="Status" required>
                <option value="aberta" {{ $race->status === 'aberta' ? 'selected' : '' }}>Aberta</option>
                <option value="fechada" {{ $race->status === 'fechada' ? 'selected' : '' }}>Fechada</option>
            </x-adminlte-select>

            <x-adminlte-button class="mt-3" label="Salvar Alterações" theme="primary" icon="fas fa-save" type="submit" />
        </form>
    </x-adminlte-card>
@stop
