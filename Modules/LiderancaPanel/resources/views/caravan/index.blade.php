@extends('memberpanel::components.layouts.master')

@section('title', 'Caravana — Liderança local')
@section('page-title', 'Caravana')

@section('content')
    <div class="max-w-7xl mx-auto">
        @livewire('lideranca.caravan-dashboard')
    </div>
@endsection
