@extends('events::admin.checkin.layout-fullscreen')

@section('title', 'Scanner de check-in (ecrã inteiro)')

@section('content')
    @include('events::admin.checkin.partials.scanner-inner', ['variant' => 'fullscreen'])
@endsection

@push('scripts')
    @include('events::admin.checkin.partials.scanner-scripts')
@endpush
