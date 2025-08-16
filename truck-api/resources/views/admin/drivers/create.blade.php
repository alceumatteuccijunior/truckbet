@extends('adminlte::page')

@section('title', 'Novo Motorista')

@section('content_header')
    <h1>Adicionar Motorista</h1>
@stop

@section('content')
    <x-adminlte-card title="Novo Motorista" theme="info">
        <form action="{{ route('admin.drivers.store') }}" method="POST">
            @csrf

            <x-adminlte-input name="nome" label="Nome" required />
            <x-adminlte-input name="categoria" label="Categoria" required />
            <x-adminlte-input name="marca" label="Marca" required />
            <x-adminlte-input name="numero_camiao" label="Nº Caminhão" required />
            <x-adminlte-input name="cidade" label="Cidade" required />

            <x-adminlte-select name="status" label="Status" required>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </x-adminlte-select>

            <x-adminlte-button class="mt-3" label="Salvar" theme="success" icon="fas fa-save" type="submit" />
        </form>
    </x-adminlte-card>
@stop
