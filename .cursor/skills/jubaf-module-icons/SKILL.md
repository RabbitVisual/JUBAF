---
name: jubaf-module-icons
description: Use when adding or changing UI that represents a Laravel module by icon (menus, heroes, cards, SuperAdmin links). Ensures official PNGs from public/modules/icons are used instead of generic Font Awesome marks.
---

# Ícones oficiais dos módulos JUBAF

## Regra

- Se o elemento diz “isto é o módulo X” (Bíblia, SuperAdmin, Eventos, …), usar **`public/modules/icons/{Nome}.png`** (URL `/modules/icons/...`) via **`<x-module-icon module="slug" />`** ou **`App\Support\ModuleIcon::url()`**.
- **Não** usar só `<x-icon name="…">` para essa identidade — FA fica para ações genéricas (setas, fechar, pesquisa, etc.).

## Slugs

Registo único: `config/module_icons.php` (`bible`, `superadmin`, `homepage`, `localchurch`, …).

## Blade

```blade
<x-module-icon module="bible" class="h-8 w-8" :alt="__('Bíblia')" />
<x-module-icon module="bible" alt="" class="h-4 w-4" /> {{-- decorativo junto de texto visível --}}
```

Se o PNG não existir no disco, o componente usa `fallbackName` / `config('module_icons.fallback_icon')`.

## Documentação

- `docs/module-icons.md`
- Guidelines: `.ai/guidelines/jubaf-project-standards.md` (tabela Stack + boas práticas)

## Novo módulo

1. PNG em `public/modules/icons/` (preferido; não depende de `storage:link`).
2. Entrada em `config/module_icons.php` (`files` + `labels`).
3. Usar `<x-module-icon>` nas vistas.
