@extends('adminlte::page')

@section('title', 'Participantes da Corrida')

@section('content_header')
    <h1>Participantes da Corrida: {{ $race->nome }}</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('admin.races.participants.create', $race->id) }}" class="btn btn-success mb-3">
        Adicionar Participante
    </a>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Piloto</th>
                <th>Email</th>
                <th>Nacionalidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($participants as $participant)
                <tr>
                    <td>{{ $participant->driver->nome }}</td>
                    <td>{{ $participant->driver->email }}</td>
                    <td>{{ $participant->driver->nacionalidade }}</td>
                    <td>
                        <form action="{{ route('admin.races.participants.destroy', [$race->id, $participant->id]) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja remover esse participante?')">Remover</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhum participante adicionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@stop
