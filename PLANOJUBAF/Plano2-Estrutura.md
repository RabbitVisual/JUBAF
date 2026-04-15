## 🏗️ Módulos Core (Infraestrutura do Sistema)

### 1. `Core`
* **Por que é Core:** Centraliza lógicas compartilhadas, como a integração do **Tailwind CSS v4.2** e **Flowbite v4.0**, além de Traits globais.
* **Função:** Garante que todos os outros módulos herdem o mesmo design system e funções auxiliares.

### 2. `SuperAdmin`
* **Por que é Core:** É a central de controle técnico do software.
* **Função:** Gerencia configurações globais, ativação ou bloqueio de funcionalidades por módulo e monitoramento de logs de sistema.

### 3. `Auth` (Segurança e Hierarquia)
* **Por que é Core:** Implementa o **Trait de permissões** do Spatie necessário para a hierarquia da JUBAF.
* **Função:** Controla o acesso baseado em **Roles** (Presidente, Tesoureiro, Secretário, Líder Local, Pastor, Jovem) e vincula cada usuário à sua respectiva igreja através do `church_id`.

---

## 🏛️ Módulos da Diretoria (Painel Administrativo)

### 4. `Board` (Painel da Presidência)
* **Explicação:** Espaço exclusivo para os 7 membros da diretoria eleitos em assembleia.
* **Integração:** Oferece a **Visão Geral** para o Presidente e ferramentas para convocação de reuniões e assembleias extraordinárias (Art. 9 e 21).

### 5. `Secretariat` (Módulo de Secretaria)
* **Explicação:** Ferramenta de trabalho para o 1º e 2º Secretários.
* **Integração:** Permite redigir atas (Art. 12), gerir o arquivo de documentos oficiais e o credenciamento de mensageiros das igrejas para o **CONJUBAF**.

### 6. `Finance` (Módulo de Tesouraria)
* **Explicação:** Acesso restrito ao 1º e 2º Tesoureiros para gestão de verbas e ofertas (Art. 27).
* **Integração:** Gera relatórios financeiros para a Assembleia Ordinária e controla reembolsos de despesas a serviço da JUBAF (Art. 23).

### 7. `Churches` (Gestão de Igrejas e Setores)
* **Explicação:** Gerencia as 70+ igrejas arroladas à ASBAF e seus respectivos pastores.
* **Integração:** Organiza as igrejas por setores para facilitar os "Encontros de Setores" e "JUBAF na Estrada" previstos no calendário 2026.

---

## 👥 Módulos de Liderança e Membresia

### 8. `LocalChurch` (Painel de Líderes e Pastores)
* **Explicação:** Interface onde o **Líder de Jovem** gerencia sua comunidade local e o **Pastor** exerce supervisão.
* **Integração:** Permite ao líder cadastrar jovens, atualizar dados da Unijovem local e visualizar comunicados da diretoria estadual/feirense.

### 9. `Youth` (Portal do Jovem)
* **Explicação:** Espaço dedicado aos jovens cadastrados pelos líderes locais.
* **Integração:** Disponibiliza materiais de estudo (Escola de Pastores e Líderes), comunicados, murais de eventos e recursos digitais para 2026.

---

## 📅 Módulos de Operação e Engajamento

### 10. `Events` (Congresso e Calendário)
* **Explicação:** Gere o **CONJUBAF**, **Start JUBAF** e **Congresso de Líderes**.
* **Integração:** Sistema de inscrição, check-in por QR Code e emissão de certificados, interligado ao módulo de Tesouraria para controle de pagamentos.

### 11. `Communication` (Notificações e Mídia)
* **Explicação:** Centraliza o envio de e-mails, SMS ou notificações push.
* **Integração:** Fundamental para cumprir os prazos de 30 dias de convocação previstos no Estatuto (Art. 6).

---

## 📊 Matriz de Acesso e Interligação

| Módulo | Role Principal | Funcionalidade Chave | Restrição de Dados |
| :--- | :--- | :--- | :--- |
| **Finance** | Tesoureiro | Gestão de Ofertas/Verbas | Apenas dados financeiros globais |
| **Secretariat** | Secretário | Redação de Atas | Apenas documentos oficiais |
| **Board** | Presidente | Visão Geral/Dashboard | Acesso a relatórios de todos os módulos |
| **LocalChurch** | Líder Local | Cadastro de Jovens | Apenas jovens da sua `church_id` |
| **Youth** | Jovem | Materiais/Eventos | Apenas conteúdo público ou para sua igreja |

Esta estrutura modular garante que a **JUBAF** tenha um sistema escalável, onde cada oficial da diretoria atue exatamente dentro de suas competências estatutárias.
