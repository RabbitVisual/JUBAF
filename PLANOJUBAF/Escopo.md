# 📘 Documentação de Escopo: Sistema JUBAF 2026

## 1. O que é o Sistema?
O sistema JUBAF é uma plataforma **ERP (Enterprise Resource Planning)** e **CRM (Customer Relationship Management)** customizada para a **Juventude Batista Feirense**. Ele foi projetado para digitalizar a gestão administrativa, financeira e ministerial de uma organização que coordena mais de **70 igrejas** em Feira de Santana e região.

O software abandona o modelo de "planilhas e papéis" para centralizar o histórico institucional (atas), a saúde financeira (tesouraria) e o engajamento da juventude em um ambiente modular.

## 2. Finalidade e Objetivos
* **Gestão Estatutária:** Garantir que os prazos e processos definidos no estatuto (convocações, mandatos, assembleias) sejam cumpridos sistemicamente.
* **Transparência Financeira:** Controle rigoroso de verbas e ofertas, com relatórios prontos para assembleias.
* **Censo e Engajamento:** Mapear quantos jovens a JUBAF possui, onde estão (igrejas/setores) e facilitar a comunicação direta com eles.
* **Continuidade Institucional:** Garantir que, ao trocar a diretoria, todos os dados, atas e históricos permaneçam salvos e organizados para a próxima gestão.

---

## 3. Matriz de Roles (Papéis) e Permissões
O sistema utiliza uma hierarquia rígida via **Spatie Laravel-Permission**, onde cada papel reflete a responsabilidade real do oficial dentro da JUBAF.

| Role (Papel) | Quem é? | Finalidade Técnica / Acesso |
| :--- | :--- | :--- |
| **SuperAdmin** | TI / Desenvolvedor | Acesso total ao código, ativação de módulos, gestão de backups e logs do sistema. |
| **Presidente** | Chefe do Executivo | **Visão Geral.** Dashboard com métricas de todos os módulos. Pode convocar reuniões e assembleias. |
| **Secretário** | 1º e 2º Secretários | **Gestor de Documentos.** Único com permissão para criar, editar e arquivar Atas e gerir o censo de igrejas. |
| **Tesoureiro** | 1º e 2º Tesoureiros | **Gestor Financeiro.** Único com acesso ao módulo de finanças, lançamentos de ofertas e balancetes. |
| **Líder Local** | Diretor da Unijovem | **Gestor de Unidade.** Gerencia apenas a sua igreja (`church_id`). Cadastra jovens e vê avisos da JUBAF. |
| **Pastor Local** | Supervisor da Igreja | **Auditor Local.** Acesso de leitura aos dados da sua igreja para supervisão pastoral, sem poder de edição administrativa. |
| **Jovem** | Membro da Igreja | **Consumidor de Conteúdo.** Acessa materiais de estudo, check-in em eventos e mural de avisos. |

---

## 4. Arquitetura Modular (O "Porquê" de cada parte)
O sistema é **Modular (nwidart/laravel-modules)** para que o código seja limpo e escalável.

* **Módulos de Gestão (Board, Secretariat, Finance):** Isolam as funções da diretoria. Um erro no código de Finanças, por exemplo, não derruba o sistema de Atas.
* **Módulos de Campo (Churches, LocalChurch):** Tratam a relação com as 70+ igrejas. Aqui o filtro por `church_id` é a regra de ouro: segurança total para que um líder não veja os dados de outra igreja.
* **Módulo de Eventos (Events):** Essencial para o calendário 2026 (CONJUBAF, Start JUBAF). Integra inscrições com o financeiro e gera presença (Check-in).
* **Módulo Core:** O "coração". Contém a estilização (Tailwind v4.2/Flowbite) e as funções que todos os outros módulos usam.

## 5. Fluxo de Dados Principal
1.  A **Secretaria** cadastra uma **Igreja**.
2.  Um **Usuário** é criado e vinculado a essa igreja como **Líder Local**.
3.  O **Líder Local** cadastra os **Jovens** de sua congregação.
4.  O **Tesoureiro** registra as ofertas dessa igreja no módulo financeiro.
5.  O **Presidente** visualiza o gráfico de crescimento de jovens e a saúde financeira no Dashboard principal.

**Implementação (passo 3):** no sistema ERP, o líder com papel `lider`, igreja principal (`church_id`) e permissão `igrejas.jovens.provision` acede a **Painel de líderes → Congregação → Adicionar jovem** (`/lideres/congregacao/jovens/create`). São criadas contas com papel `jovens` na mesma igreja; por defeito envia-se e-mail de definição de palavra-passe (alternativa: definir palavra-passe no momento, com confirmação). Edição e reenvio de link ficam na lista da congregação. Isto substitui a dependência exclusiva do painel `/admin` para criar jovens no dia a dia do campo.

---

**Resumo para o Dev:** Você está construindo uma infraestrutura de governo para uma instituição religiosa. Segurança de dados (quem pode ver o quê) e integridade de documentos (atas que não podem ser alteradas após fechadas) são os pilares deste projeto.
