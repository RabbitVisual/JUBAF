# System Architecture & Development Standards

## 1. Local-First & Internal Resource Philosophy
The application is designed to be **entirely self-contained** and prioritized towards internal data.
*   **No External CDNs**: All assets (CSS, JS, Fonts, Icons) must be served from the local `public/` directory.
*   **No External API Dependencies**: Avoid external cloud services for core functionality.
*   **Internal Source of Truth**: Before using an external service (Google, Bible APIs, etc.), check if the resource exists locally.
    *   **Bible Data**: Use `Modules/Bible`. It contains complete offline versions.
*   **Local Data Power**: We maintain rich local databases to ensure offline capability and maximum speed.

## 2. Real-Time & Live Features (Low-Cost)
*   **Avoid High-Cost Infrastructure**: Do not implement features that require continuous terminal processes (like Reverb/WebSockets).
*   **JS-Centric Live**: Use **Alpine.js**, **Livewire Polling**, or simple **Vanilla JS** for real-time effects.

## 3. Core Technology Stack
*   **Backend**: Laravel 12.x (PHP 8.2+)
*   **Frontend**: Alpine.js v3 & Blade Templates
*   **Styling**: **Tailwind CSS v4.1** (Compiled locally via Vite)
*   **Architecture**: Modular (`nwidart/laravel-modules`)

## 4. Iconografia e Fontes (100% Locais)
O sistema opera em modo **Offline-Ready**, sem dependência de CDNs externos.

### Fontes (Self-Hosted)
Utilizamos as famílias **Inter** e **Poppins**, servidas localmente.
*   **Sans-serif**: Inter (UI Geral)
*   **Display**: Poppins (Títulos)

### Ícones (Font Awesome 7.1 Pro)
Utilizamos o **Font Awesome 7.1 Pro** via componente Blade:
*   `<x-icon name="icon-name" style="duotone" class="optional-classes" />`.

## 5. Styling & Layout (Tailwind CSS v4.1)
Tailwind CSS v4.1 is integrated locally. All styles must be compiled through Vite into `app.css`.

## 6. Global Input Masking & Logic
*   **iMask.js**: Utilizado para máscaras de CPF, Telefone, etc.

## 7. Ecossistema de Módulos (Arquitetura JUBAF)
A separação de responsabilidades é mantida através de módulos especializados:

| Módulo             | Descrição                                                                                  |
| :----------------- | :----------------------------------------------------------------------------------------- |
| **Admin**          | Gestão central. Controle de cargos da Diretoria, Conselho Coordenador e Auditoria.          |
| **Federation**      | **Coração da ASBAF.** Gestão das 70 igrejas, seus pastores e líderes de Unijovem locais.   |
| **Bible**          | **Núcleo Espiritual.** Base bíblica offline completa.                                      |
| **Events**         | Logística e Inscrições. Calendário da JUBAF, Congressos e check-in.                        |
| **HomePage**       | Presença Pública. CMS institucional da JUBAF.                                              |
| **MemberPanel**    | Portal do Jovem. Perfil pessoal e acesso aos recursos de integração.                      |
| **Notifications**  | Alertas Oficiais. Centraliza comunicações da Secretaria.                                   |
| **PaymentGateway** | Pagamentos Unificados. Doações e inscrições via Mercado Pago, Stripe e PIX.                |
| **Treasury**       | Finanças JUBAF. Relatórios para a Tesouraria Executiva.                                    |

## 8. Fluxo de Desenvolvimento
*   **Vite**: Mantenha `npm run dev` ativo para compilação do Tailwind.
*   **Offline-Ready**: Priorize sempre recursos locais.
*   **Governança**: Novos recursos estatutários devem ser implementados em `Admin` ou `Federation`.