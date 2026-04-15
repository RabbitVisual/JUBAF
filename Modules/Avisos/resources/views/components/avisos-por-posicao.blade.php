@props([
    'posicao' => 'topo',
    'limit' => null,
    'container' => false,
    'containerClass' => 'container mx-auto px-4 sm:px-6 lg:px-8 py-4',
])

@php
    use Modules\Avisos\App\Services\AvisoService;
    $avisoService = app(AvisoService::class);
    $avisos = $avisoService->obterAvisosPorPosicao($posicao, $limit, auth()->user());
@endphp

@if($avisos->count() > 0)
    @if($container)
        <div class="{{ $containerClass }}">
    @endif
    <div class="avisos-container avisos-{{ $posicao }} {{ $posicao == 'flutuante' ? '' : 'space-y-4' }}">
        @foreach($avisos as $aviso)
            @include('avisos::components.banner', ['aviso' => $aviso])
        @endforeach
    </div>
    @if($container)
        </div>
    @endif
@endif

