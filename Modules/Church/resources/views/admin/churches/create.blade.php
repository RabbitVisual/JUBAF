@extends('admin::components.layouts.master')

@section('title', 'Cadastrar Igreja')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white mb-1">Nova Igreja / Congregação</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Preencha os dados institucionais e lideranças.</p>
            </div>
            <a href="{{ route('admin.churches.index') }}"
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

        <form action="{{ route('admin.churches.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Card: Informações Principais -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-icon name="church" style="duotone" class="w-5 h-5 text-blue-500" />
                        Identidade da Igreja
                    </h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome da Igreja <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ex: Igreja Batista em Feira de Santana">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug (Identificador na URL) <span class="text-red-500">*</span></label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white pb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ex: ib-feira-de-santana">
                        <p class="text-[10px] text-gray-400 mt-1">Gerado automaticamente, mas pode ser editado. Não pode conter espaços.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Logo/Imagem (Opcional)</label>
                        <input type="file" name="logo" accept="image/*"
                            class="block w-full px-4 py-2 border border-blue-100 dark:border-blue-900/50 border-dashed rounded-xl bg-blue-50 dark:bg-blue-900/10 text-gray-900 dark:text-white
                            file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                        <p class="text-[10px] text-gray-400 mt-1">Resolucão recomendada: Quadrado 500x500px, Máx 2MB.</p>
                    </div>

                    <div class="space-y-4 col-span-1 md:col-span-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
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
                        <input type="text" name="unijovem_name" value="{{ old('unijovem_name') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ex: Jovens IBF">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Setor Associacional</label>
                        <input type="text" name="sector" value="{{ old('sector') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ex: Setor 1">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Líder de Jovens Principal</label>
                        <input type="text" name="leader_name" value="{{ old('leader_name') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white flex-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Nome completo do(a) líder">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contato (WhatsApp do Líder)</label>
                        <input type="text" name="leader_phone" value="{{ old('leader_phone') }}" id="phone_celular"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="(75) 90000-0000">
                    </div>

                    <div class="space-y-2 col-span-1 md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Representante UNIJOVEM no sistema (utilizador)</label>
                        <select name="unijovem_representative_user_id"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                            <option value="">— Nenhum —</option>
                            @foreach ($users as $u)
                                <option value="{{ $u->id }}" @selected(old('unijovem_representative_user_id') == $u->id)>{{ $u->name }}</option>
                            @endforeach
                        </select>
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
                        <input type="text" name="city" value="{{ old('city', 'Feira de Santana') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>

                    <div class="space-y-2 lg:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bairro</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Ex: Centro">
                    </div>
                    
                    <div class="space-y-2 lg:col-span-3">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Endereço Completo</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            placeholder="Rua, Número, Referência">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 pb-12">
                <button type="submit"
                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 hover:scale-[1.02] hover:shadow-blue-500/40 transition-all duration-300">
                    <span class="flex items-center gap-2">
                        <x-icon name="floppy-disk" class="w-5 h-5" />
                        Salvar Igreja Associada
                    </span>
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // JS para gerar o Slug automaticamente
        document.getElementById('name').addEventListener('input', function() {
            let title = this.value;
            let slug = title.toLowerCase()
                .trim()
                .normalize('NFD') // remove accents
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            let slugInput = document.getElementById('slug');
            // Só sobrescreve se o slug estiver vazio ou parecer gerado
            if(slugInput.value === '' || slugInput.value.length >= 0) {
                slugInput.value = slug;
            }
        });

        // Mask Módulo Local
        if (typeof IMask !== 'undefined') {
            IMask(document.getElementById('phone_celular'), { mask: '(00) 00000-0000' });
        }
    </script>
    @endpush
@endsection
