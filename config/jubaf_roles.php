<?php

/**
 * Papéis e metadados JUBAF — alinhado ao PLANOJUBAF (Spatie, guard web).
 *
 * - Painel /admin: apenas super-admin.
 * - Painel /diretoria: diretoria (Estatuto Art. 7º — 1º e 2º).
 * - Painel /lideres: líderes de igrejas locais (Unijovem).
 * - Painel /jovens: jovens cadastrados pela igreja local (Unijovem).
 */
return [

    'guard' => 'web',

    /** Acesso exclusivo ao painel administrativo completo */
    'super_admin' => [
        'super-admin',
    ],

    /** Diretoria — painel /diretoria (sem acesso ao /admin) */
    'directorate' => [
        'presidente',
        'vice-presidente-1',
        'vice-presidente-2',
        'secretario-1',
        'secretario-2',
        'tesoureiro-1',
        'tesoureiro-2',
    ],

    /** Presidente e vices — gestão alargada no painel (utilizadores, funções, módulos, Bíblia, carousel) */
    'directorate_executive' => [
        'presidente',
        'vice-presidente-1',
        'vice-presidente-2',
    ],

    /** Papéis operacionais (outros painéis) */
    'operational' => [
        'lider',
        'jovens',
        'pastor',
    ],

    /** Compatibilidade: migrar utilizadores e depois remover da base */
    'legacy' => [
        'co_admin' => 'co-admin',
        'admin_panel' => 'admin',
    ],

    /**
     * Não podem ser excluídos; nome técnico (slug) não pode ser alterado.
     */
    'system_roles' => [
        'super-admin',
        'presidente',
        'vice-presidente-1',
        'vice-presidente-2',
        'secretario-1',
        'secretario-2',
        'tesoureiro-1',
        'tesoureiro-2',
        'lider',
        'jovens',
        'pastor',
        'co-admin',
        'admin',
    ],

    /** Ordem de exibição em /admin/roles (menor = primeiro) */
    'sort_order' => [
        'super-admin' => 1,
        'presidente' => 10,
        'vice-presidente-1' => 20,
        'vice-presidente-2' => 21,
        'secretario-1' => 30,
        'secretario-2' => 31,
        'tesoureiro-1' => 40,
        'tesoureiro-2' => 41,
        'pastor' => 55,
        'lider' => 100,
        'jovens' => 110,
        'co-admin' => 200,
        'admin' => 200,
    ],

    /** Rótulos para UI (nome técnico => nome exibido) */
    'labels' => [
        'super-admin' => 'Super Administrador',
        'presidente' => 'Presidente',
        'vice-presidente-1' => 'Vice-Presidente (1º)',
        'vice-presidente-2' => 'Vice-Presidente (2º)',
        'secretario-1' => 'Secretário(a) (1º)',
        'secretario-2' => 'Secretário(a) (2º)',
        'tesoureiro-1' => 'Tesoureiro(a) (1º)',
        'tesoureiro-2' => 'Tesoureiro(a) (2º)',
        'pastor' => 'Pastor (supervisão local)',
        'lider' => 'Líder (igreja local)',
        'jovens' => 'Jovem JUBAF (Unijovem)',
        'co-admin' => 'Diretoria (legado)',
        'admin' => 'Administrador (legado)',
    ],

    /** Descrição curta para cards / ajuda */
    'descriptions' => [
        'super-admin' => 'Acesso total ao painel administrativo: usuários, funções, módulos, backup e auditoria.',
        'presidente' => 'Liderança da diretoria — visão ampla no painel da diretoria.',
        'vice-presidente-1' => 'Apoio à presidência (1º vice) com permissões alinhadas à vice-liderança.',
        'vice-presidente-2' => 'Apoio à presidência (2º vice) com permissões alinhadas à vice-liderança.',
        'secretario-1' => 'Comunicação institucional e registros (1º secretário).',
        'secretario-2' => 'Comunicação institucional e registros (2º secretário).',
        'tesoureiro-1' => 'Transparência e finanças (1º tesoureiro); leitura ampliada no painel da diretoria.',
        'tesoureiro-2' => 'Transparência e finanças (2º tesoureiro); leitura ampliada no painel da diretoria.',
        'pastor' => 'Supervisão pastoral da unidade local — painel dedicado (fase de expansão).',
        'lider' => 'Gestão da unidade local: perfil, comunicação com a diretoria e recursos JUBAF da sua igreja.',
        'jovens' => 'Área do jovem cadastrado pelo líder local: perfil, avisos, chat e recursos JUBAF.',
    ],

    /** Grupo visual: super_admin | directorate | operational | custom */
    'tiers' => [
        'super-admin' => 'super_admin',
        'presidente' => 'directorate',
        'vice-presidente-1' => 'directorate',
        'vice-presidente-2' => 'directorate',
        'secretario-1' => 'directorate',
        'secretario-2' => 'directorate',
        'tesoureiro-1' => 'directorate',
        'tesoureiro-2' => 'directorate',
        'pastor' => 'operational',
        'lider' => 'operational',
        'jovens' => 'operational',
        'co-admin' => 'directorate',
        'admin' => 'super_admin',
    ],
];
