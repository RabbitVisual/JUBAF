<?php

/**
 * Contas de demonstração (apenas desenvolvimento).
 * Ordem alinhada aos papéis em database/migrations/2025_01_01_000001_create_rbac_tables.php
 *
 * Usado por: DemoUsersSeeder, QuickLoginController, view de login (ambiente local/dev).
 */
return [
    'accounts' => [
        ['slug' => 'admin', 'email' => 'admin@demo.com', 'password' => 'admin123', 'label' => 'Administrador'],
        ['slug' => 'presidente', 'email' => 'presidente@demo.com', 'password' => 'presi123', 'label' => 'Presidente'],
        ['slug' => 'vice_presidente_1', 'email' => 'vice1@demo.com', 'password' => 'demo123', 'label' => '1º Vice-presidente'],
        ['slug' => 'vice_presidente_2', 'email' => 'vice2@demo.com', 'password' => 'demo123', 'label' => '2º Vice-presidente'],
        ['slug' => 'secretario_1', 'email' => 'sec1@demo.com', 'password' => 'demo123', 'label' => '1º Secretário'],
        ['slug' => 'secretario_2', 'email' => 'sec2@demo.com', 'password' => 'demo123', 'label' => '2º Secretário'],
        ['slug' => 'tesoureiro_1', 'email' => 'tes1@demo.com', 'password' => 'demo123', 'label' => '1º Tesoureiro'],
        ['slug' => 'tesoureiro_2', 'email' => 'tes2@demo.com', 'password' => 'demo123', 'label' => '2º Tesoureiro'],
        ['slug' => 'secretario_geral', 'email' => 'secgeral@demo.com', 'password' => 'demo123', 'label' => 'Secretário Geral'],
        ['slug' => 'lider_jovens', 'email' => 'liderjovens@demo.com', 'password' => 'demo123', 'label' => 'Líder de jovens'],
        ['slug' => 'conselheiro', 'email' => 'conselheiro@demo.com', 'password' => 'demo123', 'label' => 'Conselheiro'],
        ['slug' => 'membro', 'email' => 'membro@demo.com', 'password' => 'membro123', 'label' => 'Membro'],
    ],
];
