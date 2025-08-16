@extends('adminlte::page')

@section('title', 'Criar Corrida')

@section('content_header')
    <h1>Nova Corrida</h1>
@stop

@section('content')
    <x-adminlte-card title="Cadastro de Corrida" theme="primary" icon="fas fa-flag-checkered">

        <form action="{{ route('admin.races.store') }}" method="POST">
            @csrf

            <x-adminlte-input name="nome" label="Nome" required />
            <x-adminlte-input name="circuito" label="Circuito" required />
            <x-adminlte-input name="cidade" label="Cidade" required />
            <x-adminlte-input name="estado" label="Estado" required />
            <x-adminlte-input name="data_hora" label="Data e Hora" type="datetime-local" required />

            <x-adminlte-select name="status" label="Status" required>
                <option value="aberta">Aberta</option>
                <option value="fechada">Fechada</option>
            </x-adminlte-select>

            <x-adminlte-button class="mt-3" label="Salvar Corrida" theme="success" icon="fas fa-save" type="submit" />
        </form>

    </x-adminlte-card>
@stop
