@extends('memberpanel::components.layouts.master')

@section('title', 'Editar Igreja')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-1">Editar: {{ $church->name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atualize as informações da congregação e sua liderança.</p>
            </div>
            <a href="{{ route('memberpanel.churches.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                <x-icon name="arrow-left" class="w-4 h-4 mr-2" />
                Voltar à Lista
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-2xl">
                <div class="flex items-center gap-3 mb-2">
                    <x-icon name="triangle-exclamation" class="w-5 h-5 text-red-500" />
                    <h3 class="font-bold text-red-800 dark:text-red-400">Verifique os erros abaixo:</h3>
                </div>
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-300 space-y-1 ml-8">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('memberpanel.churches.update', $church) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Card: Informações Principais -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="church" style="duotone" class="w-5 h-5 text-purple-500" />
                        Identidade da Igreja
                    </h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome da Igreja <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $church->name) }}" required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug (Identificador na URL) <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $church->slug) }}" required readonly
                            class="block w-full px-4 py-3 bg-gray-100 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-500 dark:text-gray-500 cursor-not-allowed transition-all">
                        <p class="text-[10px] text-gray-400 mt-1">O Slug não pode ser alterado após a criação.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logo/Imagem (Opcional)</label>
                        <div class="flex items-center gap-4">
                            @if ($church->logo_path)
                                <div class="w-16 h-16 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex-shrink-0 bg-gray-50 dark:bg-gray-900 p-1">
                                    <img src="{{ Storage::url($church->logo_path) }}" class="w-full h-full object-contain">
                                </div>
                            @endif
                            <input type="file" name="logo" accept="image/*"
                                class="block w-full px-4 py-2 border border-purple-100 dark:border-purple-900/50 border-dashed rounded-xl bg-purple-50 dark:bg-purple-900/10 text-gray-900 dark:text-white
                                file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 transition-all cursor-pointer">
                        </div>
                    </div>

                    <div class="space-y-4 col-span-1 md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $church->is_active) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                            </div>
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white transition-colors">Igreja Ativa no Sistema</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Card: Liderança e Unijovem -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="users-crown" style="duotone" class="w-5 h-5 text-indigo-500" />
                        Jovens e Liderança
                    </h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome da Unijovem (Grupo de Jovens)</label>
                        <input type="text" name="unijovem_name" value="{{ old('unijovem_name', $church->unijovem_name) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor Associacional</label>
                        <input type="text" name="sector" value="{{ old('sector', $church->sector) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Líder de Jovens Principal</label>
                        <input type="text" name="leader_name" value="{{ old('leader_name', $church->leader_name) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white flex-1 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contato (WhatsApp do Líder)</label>
                        <input type="text" name="leader_phone" value="{{ old('leader_phone', $church->leader_phone) }}" id="phone_celular"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <!-- Card: Localização -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="map-location-dot" style="duotone" class="w-5 h-5 text-emerald-500" />
                        Localização Geográfica
                    </h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="space-y-2 lg:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cidade</label>
                        <input type="text" name="city" value="{{ old('city', $church->city) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2 lg:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bairro</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood', $church->neighborhood) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                    
                    <div class="space-y-2 lg:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Endereço Completo</label>
                        <input type="text" name="address" value="{{ old('address', $church->address) }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 pb-12">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 hover:scale-[1.02] hover:shadow-purple-500/40 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        Salvar Alterações
                    </span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Mask Módulo Local
        if (typeof IMask !== 'undefined') {
            IMask(document.getElementById('phone_celular'), { mask: '(00) 00000-0000' });
        }
    </script>
    @endpush
@endsection
