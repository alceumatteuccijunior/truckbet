@extends('adminlte::page')

@section('title', 'Editar Motorista')

@section('content_header')
    <h1>Editar Motorista</h1>
@stop

@section('content')
    <x-adminlte-card title="Atualizar Motorista" theme="primary">
        <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-adminlte-input name="nome" label="Nome" value="{{ $driver->nome }}" required />
            <x-adminlte-input name="categoria" label="Categoria" value="{{ $driver->categoria }}" required />
            <x-adminlte-input name="marca" label="Marca" value="{{ $driver->marca }}" required />
            <x-adminlte-input name="numero_camiao" label="Nº Caminhão" value="{{ $driver->numero_camiao }}" required />
            <x-adminlte-input name="cidade" label="Cidade" value="{{ $driver->cidade }}" required />

            <x-adminlte-select name="status" label="Status" required>
                <option value="ativo" {{ $driver->status === 'ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="inativo" {{ $driver->status === 'inativo' ? 'selected' : '' }}>Inativo</option>
            </x-adminlte-select>

            <x-adminlte-button class="mt-3" label="Salvar Alterações" theme="primary" icon="fas fa-save" type="submit" />
        </form>
    </x-adminlte-card>
@stop
