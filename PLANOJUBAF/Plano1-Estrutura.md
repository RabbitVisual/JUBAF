## 🏛️ Arquitetura de Módulos (Laravel Modules v13)

Com base no **Estatuto**, a estrutura modular recomendada para o ecossistema JUBAF é:

### 1. Módulo Core & Admin (`Modules/Admin`)
* **Gestão de Usuários:** Controle de acesso para os 7 membros da diretoria e secretário geral.
* **Controle de Acesso (Spatie):** Níveis de permissão específicos (Presidente, Tesoureiro, Secretário).
* **Logs do Sistema:** Auditoria de todas as alterações feitas nos registros das igrejas.

### 2. Módulo de Igrejas & Setores (`Modules/Churches`)
* **Cadastro de Igrejas:** Gerenciamento das 70+ igrejas arroladas à ASBAF.
* **Unijovens:** Vínculo de cada igreja com sua respectiva liderança de jovens (Unijovem).
* **Setores:** Organização geográfica das igrejas para o "Encontro dos Setores".

### 3. Módulo de Secretaria (`Modules/Secretariat`)
* **Gestão de Atas:** Ferramenta para o 1º Secretário redigir e arquivar atas de assembleias e reuniões do conselho (Art. 12 e 26).
* **Mensageiros:** Controle de credenciamento de mensageiros para as Assembleias Ordinárias (Art. 6).
* **Arquivo Digital:** Documentação oficial, resoluções e histórico do Estatuto.

### 4. Módulo Financeiro (`Modules/Finance`)
* **Tesouraria:** Controle das verbas destinadas pela ASBAF e ofertas das igrejas (Art. 27).
* **Relatórios Orçamentários:** Prestação de contas anual para a Assembleia (Art. 14).
* **Reembolsos:** Gestão de despesas a serviço da JUBAF (Art. 23).

### 5. Módulo de Eventos (`Modules/Events`)
* **CONJUBAF:** Sistema de inscrição e gestão para o congresso anual (Art. 5).
* **Calendário 2026:** Gestão de datas para o Congresso de Líderes, Start JUBAF e JUBAF na Estrada.
* **Check-in:** Controle de presença via QR Code para eventos locais e estaduais.

---

## 🎨 Interface Profissional (Flowbite v4.0 + Tailwind v4.2)

A interface deve ser limpa e funcional para facilitar o uso diário pelos diretores.

| Componente Flowbite      | Utilidade no Sistema JUBAF                                                   |
| :----------------------- | :--------------------------------------------------------------------------- |
| **Sidebar Navigation**   | Acesso rápido aos módulos de Igrejas, Finanças e Atas.                       |
| **Data Tables**          | Listagem das 70 igrejas com filtros por setor e status de cooperação.        |
| **Steppers**             | Processo de eleição da diretoria e renovação do terço do conselho (Art. 16). |
| **Modais de Documentos** | Visualização rápida de Atas e Relatórios Financeiros sem sair da página.     |
| **Charts (ApexCharts)**  | Visualização do crescimento de jovens e saúde financeira da JUBAF.           |

---

## 📝 Roadmap de Implementação

### Passo 1: Configuração do Banco de Dados
Mapear as entidades conforme o estatuto:
* `igrejas` (id, nome, pastor, representante_unijovem).
* `membros_conselho` (id, nome, cargo, data_eleicao, mandato_fim).
* `atas` (id, titulo, conteudo, data_assembleia, assinada_por).

### Passo 2: Integração de Comunicação
Criar um sistema de **Comunicados Oficiais** dentro do módulo Admin:
* Envio automático de convocações para Assembleias Extraordinárias (prazo de 30 dias conforme Art. 6).
* Notificações push para os líderes locais via sistema.

### Passo 3: Segurança e Hierarquia
Implementar o Trait de permissões que instalamos anteriormente:
* **Financeiro:** Apenas 1º e 2º Tesoureiros editam dados de verbas.
* **Secretaria:** Apenas Secretários editam Atas.
* **Presidente:** Visão geral e convocação de reuniões.

Este sistema transformará a gestão da **JUBAF** em algo totalmente digital, profissional e transparente, garantindo que a transição de diretorias ocorra sem perda de histórico ou dados importantes.