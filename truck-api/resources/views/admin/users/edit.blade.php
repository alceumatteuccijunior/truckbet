@extends('adminlte::page')

@section('title', 'Editar Usuário')

@section('content_header')
    <h1>Editar Usuário</h1>
@stop

@section('content')
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nome</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" value="{{ $user->cpf }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Saldo</label>
            <input type="number" step="0.01" name="saldo" value="{{ $user->saldo }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="1" {{ $user->status ? 'selected' : '' }}>Ativo</option>
                <option value="0" {{ !$user->status ? 'selected' : '' }}>Inativo</option>
            </select>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>user</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>admin</option>
            </select>
        </div>

        <button class="btn btn-primary mt-3">Salvar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
    </form>
@stop
