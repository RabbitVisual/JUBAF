@extends('admin::components.layouts.master')

@section('title', 'Nova reunião')

@section('content')
    <div class="max-w-xl space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova reunião</h1>
        <form method="post" action="{{ route('admin.council.meetings.store') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
            @csrf
            @include('coordinationcouncil::admin.meetings._form', ['meeting' => null, 'members' => $members])
            <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-medium">Criar</button>
            <a href="{{ route('admin.council.meetings.index') }}" class="ml-2 text-sm">Cancelar</a>
        </form>
    </div>
@endsection
