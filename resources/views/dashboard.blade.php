@extends('layouts.app')

@section('title', 'Tổng quan')

@section('content')
    <h1 class="text-2xl font-bold mb-2">Xin chào, {{ auth()->user()->name }}</h1>
    <p class="text-slate-500">Vai trò hiện tại: <b>{{ auth()->user()->roleLabel() }}</b></p>
@endsection