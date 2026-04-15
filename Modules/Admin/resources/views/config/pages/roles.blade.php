@php
    /** @var array $configsGrouped */
    /** @var array<int, string> $assignableRoles */
    use App\Support\JubafRoleRegistry;

    $rolesItems = collect($configsGrouped['roles'] ?? [])->keyBy('key');

    $chatCfg = $rolesItems->get('chat.agent_extra_roles');
    $avisosCfg = $rolesItems->get('avisos.publish_extra_roles');
    $selectedChat = $chatCfg ? array_values(array_filter(array_map('trim', explode(',', (string) $chatCfg->value)))) : [];
    $selectedAvisos = $avisosCfg ? array_values(array_filter(array_map('trim', explode(',', (string) $avisosCfg->value)))) : [];
@endphp
<div class="mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Papéis, rótulos e agentes</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-3xl">
        Os slugs dos papéis do sistema (coluna «nome técnico») não podem ser alterados aqui — apenas o nome amigável e o texto de ajuda mostrados nos painéis.
        Escolha ainda que papéis Spatie adicionais podem actuar no chat de suporte e publicar avisos institucionais.
    </p>
</div>

<div class="space-y-8">
    <section aria-labelledby="heading-role-labels">
        <h3 id="heading-role-labels" class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Nomes e ajuda por papel</h3>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach (JubafRoleRegistry::roleSlugsForPanelLabels() as $slug)
                @php
                    $displayRow = $rolesItems->get('role.display.'.$slug);
                    $helpRow = $rolesItems->get('role.help.'.$slug);
                @endphp
                <div class="rounded-xl border border-gray-200 dark:border-slate-600 p-4 space-y-3 bg-gray-50/50 dark:bg-slate-900/30">
                    <p class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $slug }}</p>
                    @if ($displayRow)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1" for="role_display_{{ $slug }}">Nome exibido</label>
                            <input id="role_display_{{ $slug }}"
                                   type="text"
                                   name="configs[{{ $displayRow->key }}]"
                                   value="{{ $displayRow->value }}"
                                   class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        </div>
                    @endif
                    @if ($helpRow)
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-300 mb-1" for="role_help_{{ $slug }}">Ajuda (opcional)</label>
                            <textarea id="role_help_{{ $slug }}"
                                      name="configs[{{ $helpRow->key }}]"
                                      rows="2"
                                      class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white">{{ $helpRow->value }}</textarea>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    <section aria-labelledby="heading-agents" class="border-t border-gray-100 dark:border-slate-700 pt-6">
        <h3 id="heading-agents" class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">Chat e avisos</h3>
        <div class="grid gap-6 md:grid-cols-2">
            @if ($chatCfg)
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="chat_agent_roles">{{ $chatCfg->description ?? $chatCfg->key }}</label>
                    <select id="chat_agent_roles"
                            name="configs[chat.agent_extra_roles][]"
                            multiple
                            size="8"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        @foreach ($assignableRoles as $name)
                            <option value="{{ $name }}" @selected(in_array($name, $selectedChat, true))>{{ $name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ctrl+clique (Windows) ou ⌘+clique para várias opções.</p>
                </div>
            @endif
            @if ($avisosCfg)
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="avisos_publish_roles">{{ $avisosCfg->description ?? $avisosCfg->key }}</label>
                    <select id="avisos_publish_roles"
                            name="configs[avisos.publish_extra_roles][]"
                            multiple
                            size="8"
                            class="w-full rounded-lg border border-gray-300 bg-gray-50 p-2 text-sm dark:bg-slate-800 dark:border-slate-600 dark:text-white">
                        @foreach ($assignableRoles as $name)
                            <option value="{{ $name }}" @selected(in_array($name, $selectedAvisos, true))>{{ $name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Papéis Spatie existentes no guard <code class="text-xs">web</code>.</p>
                </div>
            @endif
        </div>
    </section>
</div>
