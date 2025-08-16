@extends('adminlte::page')

@section('title', 'Painel Admin')

@section('content_header')
    <h1>Dashboard Administrativo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Usuários" text="Total cadastrados" number="42" icon="fas fa-users" theme="info"/>
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-info-box title="Saldo Geral" text="R$ 12.340,00" icon="fas fa-wallet" number="" theme="success"/>
        </div>
    </div>
@stop
