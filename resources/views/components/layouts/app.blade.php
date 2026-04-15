@props([
    'title' => null,
])

@extends('layouts.app')

@section('title')
    {{ $title ?? config('app.name', 'JUBAF') }}
@endsection

@section('content')
    {{ $slot }}
@endsection
