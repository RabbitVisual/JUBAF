@extends('layouts.app')

@section('title', 'Editar Aviso — Diretoria')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('avisos::paineldiretoria.partials.subnav', ['active' => 'form'])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('diretoria.avisos.show', $aviso) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
                <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
                Voltar ao detalhe
            </a>
            <p class="mt-3 text-xs font-bold uppercase tracking-[0.18em] text-cyan-800 dark:text-cyan-400">Diretoria · Comunicação JUBAF</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/25">
                    <x-module-icon module="Avisos" class="h-7 w-7" />
                </span>
                Editar aviso
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($aviso->titulo, 80) }}</p>
        </div>
        <a href="{{ route('diretoria.avisos.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700">
            <x-icon name="list" class="h-4 w-4" style="duotone" />
            Lista
        </a>
    </div>

    <!-- Flash Messages -->
    @if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/30 dark:text-red-200" role="alert">
        <div class="flex items-start">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3 mt-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <div>
                <p class="font-medium mb-2">Por favor, corrija os seguintes erros:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('diretoria.avisos.update', $aviso) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Formulário Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informações Básicas -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações básicas</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo', $aviso->titulo) }}"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('titulo') border-red-500 @enderror">
                            @error('titulo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição (Resumo)
                            </label>
                            <textarea id="descricao" 
                                      name="descricao" 
                                      rows="3"
                                      maxlength="500"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('descricao') border-red-500 @enderror">{{ old('descricao', $aviso->descricao) }}</textarea>
                            @error('descricao')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Conteúdo Completo (HTML permitido)
                            </label>
                            <textarea id="conteudo" 
                                      name="conteudo" 
                                      rows="6"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('conteudo') border-red-500 @enderror">{{ old('conteudo', $aviso->conteudo) }}</textarea>
                            @error('conteudo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configurações de Exibição -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configurações de Exibição</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <select id="tipo" 
                                    name="tipo" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('tipo') border-red-500 @enderror">
                                <option value="info" {{ old('tipo', $aviso->tipo) == 'info' ? 'selected' : '' }}>Informação</option>
                                <option value="success" {{ old('tipo', $aviso->tipo) == 'success' ? 'selected' : '' }}>Sucesso</option>
                                <option value="warning" {{ old('tipo', $aviso->tipo) == 'warning' ? 'selected' : '' }}>Aviso</option>
                                <option value="danger" {{ old('tipo', $aviso->tipo) == 'danger' ? 'selected' : '' }}>Urgente</option>
                                <option value="promocao" {{ old('tipo', $aviso->tipo) == 'promocao' ? 'selected' : '' }}>Promoção</option>
                                <option value="novidade" {{ old('tipo', $aviso->tipo) == 'novidade' ? 'selected' : '' }}>Novidade</option>
                                <option value="anuncio" {{ old('tipo', $aviso->tipo) == 'anuncio' ? 'selected' : '' }}>Anúncio</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="posicao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Posição <span class="text-red-500">*</span>
                            </label>
                            <select id="posicao" 
                                    name="posicao" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('posicao') border-red-500 @enderror">
                                <option value="topo" {{ old('posicao', $aviso->posicao) == 'topo' ? 'selected' : '' }}>Topo da Página</option>
                                <option value="meio" {{ old('posicao', $aviso->posicao) == 'meio' ? 'selected' : '' }}>Meio da Página</option>
                                <option value="rodape" {{ old('posicao', $aviso->posicao) == 'rodape' ? 'selected' : '' }}>Rodapé</option>
                                <option value="flutuante" {{ old('posicao', $aviso->posicao) == 'flutuante' ? 'selected' : '' }}>Flutuante</option>
                            </select>
                            @error('posicao')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="estilo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Estilo <span class="text-red-500">*</span>
                            </label>
                            <select id="estilo" 
                                    name="estilo" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('estilo') border-red-500 @enderror">
                                <option value="banner" {{ old('estilo', $aviso->estilo) == 'banner' ? 'selected' : '' }}>Banner</option>
                                <option value="announcement" {{ old('estilo', $aviso->estilo) == 'announcement' ? 'selected' : '' }}>Anúncio</option>
                                <option value="cta" {{ old('estilo', $aviso->estilo) == 'cta' ? 'selected' : '' }}>Call to Action</option>
                                <option value="modal" {{ old('estilo', $aviso->estilo) == 'modal' ? 'selected' : '' }}>Modal</option>
                                <option value="toast" {{ old('estilo', $aviso->estilo) == 'toast' ? 'selected' : '' }}>Toast</option>
                            </select>
                            @error('estilo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="ordem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ordem de Exibição
                            </label>
                            <input type="number" 
                                   id="ordem" 
                                   name="ordem" 
                                   value="{{ old('ordem', $aviso->ordem) }}"
                                   min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('ordem') border-red-500 @enderror">
                            @error('ordem')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Cores e Visual -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cores e Visual</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="cor_primaria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cor Primária (Tailwind)
                            </label>
                            <input type="text" 
                                   id="cor_primaria" 
                                   name="cor_primaria" 
                                   value="{{ old('cor_primaria', $aviso->cor_primaria) }}"
                                   placeholder="Ex: cyan, emerald, amber"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('cor_primaria') border-red-500 @enderror">
                            @error('cor_primaria')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cor_secundaria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Cor Secundária (Tailwind)
                            </label>
                            <input type="text" 
                                   id="cor_secundaria" 
                                   name="cor_secundaria" 
                                   value="{{ old('cor_secundaria', $aviso->cor_secundaria) }}"
                                   placeholder="Ex: blue, green, yellow"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('cor_secundaria') border-red-500 @enderror">
                            @error('cor_secundaria')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="imagem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Imagem
                            </label>
                            @if($aviso->imagem)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $aviso->imagem) }}" 
                                     alt="{{ $aviso->titulo }}" 
                                     class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-slate-600">
                                <div class="mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" 
                                               name="remover_imagem" 
                                               value="1"
                                               class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 dark:bg-slate-700 dark:border-slate-600">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Remover imagem atual</span>
                                    </label>
                                </div>
                            </div>
                            @endif
                            <input type="file" 
                                   id="imagem" 
                                   name="imagem" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('imagem') border-red-500 @enderror">
                            @error('imagem')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call to Action</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="url_acao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                URL de Ação
                            </label>
                            <input type="url" 
                                   id="url_acao" 
                                   name="url_acao" 
                                   value="{{ old('url_acao', $aviso->url_acao) }}"
                                   placeholder="https://exemplo.com/acao"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('url_acao') border-red-500 @enderror">
                            @error('url_acao')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="texto_botao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Texto do Botão
                            </label>
                            <input type="text" 
                                   id="texto_botao" 
                                   name="texto_botao" 
                                   value="{{ old('texto_botao', $aviso->texto_botao) }}"
                                   maxlength="100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('texto_botao') border-red-500 @enderror">
                            @error('texto_botao')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" 
                                       name="botao_exibir" 
                                       value="1"
                                       {{ old('botao_exibir', $aviso->botao_exibir) ? 'checked' : '' }}
                                       class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 dark:bg-slate-700 dark:border-slate-600">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Exibir botão de ação</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status e Configurações -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status e Configurações</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   id="ativo" 
                                   name="ativo" 
                                   value="1"
                                   {{ old('ativo', $aviso->ativo) ? 'checked' : '' }}
                                   class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 dark:bg-slate-700 dark:border-slate-600">
                            <label for="ativo" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Aviso Ativo
                            </label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   id="destacar" 
                                   name="destacar" 
                                   value="1"
                                   {{ old('destacar', $aviso->destacar) ? 'checked' : '' }}
                                   class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 dark:bg-slate-700 dark:border-slate-600">
                            <label for="destacar" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Destacar
                            </label>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" 
                                   id="dismissivel" 
                                   name="dismissivel" 
                                   value="1"
                                   {{ old('dismissivel', $aviso->dismissivel) ? 'checked' : '' }}
                                   class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 dark:bg-slate-700 dark:border-slate-600">
                            <label for="dismissivel" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Permitir fechar (dismissível)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Datas -->
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Período de Exibição</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="data_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data de Início
                            </label>
                            <input type="datetime-local" 
                                   id="data_inicio" 
                                   name="data_inicio" 
                                   value="{{ old('data_inicio', $aviso->data_inicio ? $aviso->data_inicio->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('data_inicio') border-red-500 @enderror">
                            @error('data_inicio')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_fim" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data de Fim
                            </label>
                            <input type="datetime-local" 
                                   id="data_fim" 
                                   name="data_fim" 
                                   value="{{ old('data_fim', $aviso->data_fim ? $aviso->data_fim->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white @error('data_fim') border-red-500 @enderror">
                            @error('data_fim')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @include('avisos::partials.church-audience-fields', ['selectedChurchIds' => $aviso->church_ids ?? []])
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 border-t border-gray-200 pt-6 dark:border-slate-700 sm:flex-row sm:justify-end">
            <a href="{{ route('diretoria.avisos.index') }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">Cancelar</a>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                <x-icon name="check" class="h-4 w-4" style="solid" />
                Atualizar aviso
            </button>
        </div>
    </form>
</div>
@endsection

