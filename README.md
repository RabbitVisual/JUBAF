<div align="center">
  <img src="assets/images/logo_oficial.png" alt="JUBAF Logo" width="320">

  ### Ecossistema Profissional de Gestão para a Juventude Batista Feirense
  *Unificando 70 Igrejas, Fomentando a Liderança, Glorificando a Cristo.*

  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
  [![Tailwind](https://img.shields.io/badge/Tailwind_CSS-v4.1-38B2AC?style=for-the-badge&logo=tailwind-css)](https://tailwindcss.com)
  [![License](https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge)](LICENSE)
</div>

---

## 🏛️ Visão Institucional

A **JUBAF** (Juventude Batista Feirense) é a organização representativa de todos os jovens das igrejas batistas arroladas na **ASBAF** (Associação Batista Feirense). Este ecossistema digital foi projetado para profissionalizar a gestão da Diretoria e do Conselho Coordenador, servindo como a espinha dorsal tecnológica para a integração de 70 igrejas associadas.

**Objetivo Central:** Integrar o jovem na família, na igreja e na sociedade, promovendo formação de liderança, congressos (CONJUBAF), e a expansão do Reino de Deus através da juventude.

---

## 👥 Gestão da Diretoria Estatutária

O sistema foi arquitetado para apoiar especificamente os 7 cargos da Diretoria Executiva (Art. 10 do Estatuto):

1.  **Presidente:** Visão geral e representação oficial.
2.  **1ª e 2ª Vice-Presidência:** Substituição e auxílio nas comissões.
3.  **1ª e 2ª Secretaria:** Gestão de atas, correspondências e arquivo histórico.
4.  **1ª e 2ª Tesouraria:** Controle rigoroso de fundos, orçamentos e relatórios financeiros.

---

## 🏘️ Gestão das 70 Igrejas (ASBAF)

O sistema permite a gestão centralizada das igrejas associadas, permitindo:
- **Cadastro de Igrejas:** Registro oficial das 70 igrejas da ASBAF.
- **Liderança Local:** Gestão de presidentes de **UNIJOVEM** de cada igreja.
- **Conselho Coordenador:** Acompanhamento dos 12 membros eletivos e representantes locais (Art. 16).
- **Mensageiros:** Gestão de credenciamento para as Assembleias Gerais.

---

## 🛠️ Ecossistema Modular (Arquitetura)

O sistema é composto por módulos especializados que garantem escalabilidade e segurança:

| Módulo | Finalidade para a JUBAF |
| :--- | :--- |
| **Admin** | Painel de controle para a Diretoria, gestão de permissões e conselhos. |
| **Federation** | Gestão de dados das 70 igrejas e líderes de Unijovem. |
| **Treasury** | Gestão financeira para os Tesoureiros (Entradas, Campanhas, Fundos ASBAF). |
| **Events** | Gestão completa do CONJUBAF, acampamentos e torneios esportivos. |
| **Bible** | Ferramenta espiritual com versões offline para estudos e congressos. |
| **Notifications** | Comunicação oficial direta com líderes de igrejas e membros. |
| **PaymentGateway** | Recebimento de ofertas, inscrições e taxas associativas (PIX/Cartão). |
| **MemberPanel** | Área exclusiva para o jovem JUBAF acompanhar seu progresso e eventos. |

---

## 🚀 Stack Tecnológica

- **Backend:** Laravel 12 (Modular Monolith)
- **Frontend:** Alpine.js (Reatividade Ultra-Leve)
- **Estilização:** Tailwind CSS v4.1 (Premium Design)
- **Segurança:** 2FA (Autenticação de Dois Fatores) nativa.
- **Performance:** Offline-Ready com ativos (fontes/ícones) locais.

---

## 📋 Como Começar

### Pré-requisitos
- PHP 8.2+, MySQL 8.0+, Node.js (v20+).

### Instalação
```bash
# 1. Instalação de Dependências
composer install && npm install

# 2. Configuração
cp .env.example .env && php artisan key:generate

# 3. Banco de Dados JUBAF
php artisan migrate --seed

# 4. Build de Ativos
npm run build
```

---

## 📜 Licença e Propriedade

Este projeto é de **uso privativo** da **JUBAF** e está sob licença proprietária.
Desenvolvido por: **Reinan Rodrigues (Vertex Solutions)**.
© 2026 JUBAF. Todos os Direitos Reservados.
