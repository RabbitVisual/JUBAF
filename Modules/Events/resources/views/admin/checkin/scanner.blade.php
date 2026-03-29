@extends('admin::components.layouts.master')

@section('content')
    @include('events::admin.checkin.partials.scanner-inner', ['variant' => 'classic'])
@endsection

@push('scripts')
    @include('events::admin.checkin.partials.scanner-scripts')
@endpush
