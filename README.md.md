# FinanceUni - Seu Gerenciador de Finanças Pessoais

[![Licença](https://img.shields.io/badge/Licença-GoFDD-blue.svg)](/LICENSE)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-brightgreen.svg)](https://vuejs.org/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-yellow.svg)](https://www.mysql.com/)

## Visão Geral do Projeto

FinanceUni é uma aplicação web projetada para ajudar indivíduos a gerenciar suas finanças de forma eficaz. Ela fornece ferramentas para rastrear receitas e despesas, visualizar dados financeiros através de dashboards e manter-se motivado com recursos de gamificação.

**Público-Alvo:**

*   Indivíduos que buscam ter melhor controle sobre suas finanças pessoais.
*   Estudantes e jovens profissionais que desejam desenvolver hábitos financeiros saudáveis.
*   Qualquer pessoa que busque uma plataforma amigável e envolvente para gerenciamento financeiro.

**Principais Recursos:**

*   **Rastreamento de Receitas e Despesas:** Registre e categorize facilmente suas transações financeiras de forma manual ou automática.
*   **Gestão Financeira Híbrida:** Sistema dual que permite gerenciar transações manuais e sincronizadas separadamente, mantendo a integridade dos dados.
*   **Dashboards Financeiros:** Visualize seus padrões de gastos e saúde financeira geral com gráficos interativos.
*   **Gamificação:** Ganhe XP, suba de nível e alcance metas financeiras para manter-se motivado.
*   **Sincronização de Dados Bancários:** Conecte suas contas bancárias via Pluggy para importar transações automaticamente (modo demo disponível).
*   **Categorias Personalizáveis:** Crie e gerencie categorias para atender às suas necessidades específicas.
*   **Ferramentas de Orçamento:** Defina orçamentos para diferentes categorias e acompanhe seu progresso.
*   **Relatórios Avançados:** Gere relatórios detalhados para analisar seus dados financeiros ao longo do tempo.
*   **Preparado para IA:** Arquitetura preparada para futura integração com modelos de Machine Learning para análise preditiva e recomendações personalizadas.

## Arquitetura

FinanceUni adota uma arquitetura em camadas para garantir manutenibilidade, escalabilidade e uma clara separação de responsabilidades.

### Frontend (Vue 3 + Vite)

*   **Framework:** Vue 3 com Composition API para construção de interfaces de usuário reativas.
*   **Ferramenta de Build:** Vite para desenvolvimento rápido e builds de produção otimizados.
*   **Arquitetura:** Padrão MVC adaptado com design baseado em componentes.
    *   **Model:** Serviços para busca e manipulação de dados, schemas para validação.
    *   **View:** Componentes reutilizáveis para elementos de UI e layouts responsivos.
    *   **Controller:** Lógica dentro de `<script setup>` para lidar com interações do usuário e atualizar a view.
*   **Principais Bibliotecas:**
    *   Vue Router: Navegação entre diferentes seções da aplicação.
    *   Axios: Requisições HTTP para a API backend.
    *   TailwindCSS 4: Framework CSS utility-first para estilização.
    *   DaisyUI: Biblioteca de componentes construída sobre o Tailwind CSS.
    *   Lucide Icons, HeroIcons, FontAwesome: Bibliotecas de ícones para apelo visual.
    *   Chart.js + Vue-ChartJS: Visualização de dados usando gráficos.
    *   Confetti: Adição de efeitos de celebração para gamificação.
    *   v-mask / vue-the-mask / vue-currency-input: Máscaras e formatação de entrada.

### Backend (Laravel 11)

*   **Framework:** Laravel 11 para construção de uma API robusta e escalável.
*   **Arquitetura:** Padrão MVC tradicional com camada de serviços e DTOs.
    *   **Model:** Modelos Eloquent representando tabelas do banco de dados (ex: `User`, `Transaction`, `Category`).
    *   **View:** Não aplicável no backend (apenas API).
    *   **Controller:** Manipula requisições HTTP e orquestra interações entre models e services.
*   **Services:** Classes dedicadas para manipular lógica de negócio (ex: `PluggyService`, `GamificationService`, `AuthService`).
*   **DTOs (Data Transfer Objects):** Usados para encapsular dados ao transferir entre camadas, garantindo segurança de tipo e integridade de dados.
*   **Principais Recursos:**
    *   Eloquent ORM: Interação com banco de dados com sintaxe expressiva.
    *   Migrations: Controle de versão para mudanças no schema do banco de dados.
    *   Seeds: População do banco de dados com dados iniciais.
    *   Laravel Sanctum: Autenticação de API usando tokens SPA.

### Comunicação (REST API)

O frontend e backend se comunicam via REST API usando JSON como formato de dados. Laravel Sanctum é usado para autenticação de API, fornecendo acesso seguro a recursos protegidos.

### Banco de Dados (MySQL)

*   **Banco de Dados:** MySQL para armazenamento de dados da aplicação.
*   **Schema:** Schema bem definido com tabelas normalizadas e relacionamentos.
*   **Migrations:** Usadas para gerenciar mudanças no schema do banco de dados de forma versionada.
*   **Seeds:** Usadas para popular o banco de dados com dados iniciais (ex: categorias padrão, usuário admin).

## Configuração e Instalação

Siga estes passos para configurar o ambiente de desenvolvimento do FinanceUni:

### Configuração do Backend (Laravel)

1.  **Clone o repositório:**

    *   Crie um banco de dados MySQL para o FinanceUni.
    *   Copie `.env.example` para `.env` e atualize as credenciais do banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=financeuni
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

2.  **Instale as dependências:**

```bash
cd financeuni-backend
composer install
```

3.  **Gere a chave da aplicação:**

```bash
php artisan key:generate
```

4.  **Execute as migrations:**

```bash
php artisan migrate
```

5.  **Execute os seeders:**

```bash
php artisan db:seed
```

6.  **Inicie o servidor de desenvolvimento:**

```bash
php artisan serve
```

### Configuração do Frontend (Vue 3)

1.  **Navegue até o diretório do frontend:**

```bash
cd financeuni-frontend
```

2.  **Crie o arquivo `.env`** na raiz do diretório frontend

3.  **Atualize a URL da API no arquivo `.env`:**

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

4.  **Instale as dependências:**

```bash
npm install
```

5.  **Inicie o servidor de desenvolvimento:**

```bash
npm run dev
```

> **Nota:** Substitua `seu_usuario`, `sua_senha`, `seu_client_id_pluggy`, e `seu_client_secret_pluggy` pelos seus valores reais. Gere uma nova APP_KEY usando `php artisan key:generate`.

## Uso

### Adicionando uma Transação

1.  Faça login na sua conta FinanceUni.
2.  Navegue até a página "Transações".
3.  Clique no botão "Adicionar Transação".
4.  Preencha os detalhes da transação (valor, categoria, data, descrição).
5.  Salve a transação.

### Visualizando o Dashboard

1.  Faça login na sua conta FinanceUni.
2.  Navegue até a página "Dashboard".
3.  Visualize seu resumo financeiro, gráficos de gastos e progresso em direção às suas metas financeiras.

## Segurança

FinanceUni incorpora as seguintes medidas de segurança:

*   **Autenticação:** Laravel Sanctum é usado para autenticação de API, fornecendo tokens SPA seguros.
*   **Validação:** Regras de validação fortes são aplicadas a todas as requisições recebidas para prevenir processamento de dados maliciosos.
*   **CORS:** Cross-Origin Resource Sharing (CORS) é configurado para permitir requisições apenas do domínio frontend.
*   **Escopos fromUser():** Escopos Eloquent são usados para prevenir que usuários acessem dados pertencentes a outros usuários.

**Melhorias de Segurança Planejadas:**

*   HTTPS e HSTS: Impor conexões seguras usando HTTPS e HTTP Strict Transport Security (HSTS).
*   Cookies HttpOnly e Secure: Definir cookies com flags HttpOnly e Secure para prevenir acesso via script client-side e garantir transmissão sobre HTTPS.
*   Rate Limiting: Implementar limitação de taxa para proteger contra ataques de força bruta e ataques DoS.
*   2FA/MFA: Adicionar autenticação de dois fatores (2FA) ou autenticação multi-fator (MFA) para segurança aprimorada.
*   Hashids/UUID: Usar Hashids ou UUIDs para evitar enumeração de dados sensíveis.
*   Monitoramento Sentry: Integrar Sentry para rastreamento e monitoramento de erros em tempo real.
*   Casts Criptografados: Criptografar colunas sensíveis do banco de dados usando casts criptografados do Laravel.

## Integração Pluggy

FinanceUni se integra com a API Pluggy para permitir que usuários sincronizem seus dados bancários automaticamente.

*   **PluggyService:** Um serviço dedicado manipula a integração com a API Pluggy.
*   **Sincronização de Contas:** Usuários podem conectar suas contas bancárias para importar transações automaticamente.
*   **Importação de Transações:** Transações são normalizadas e armazenadas no banco de dados com `source = 'pluggy'`.
*   **Gestão Dual de Dados:** O sistema mantém separação entre transações manuais e transações sincronizadas via Pluggy, permitindo análise independente de cada fonte.
*   **Modo Demo:** Um modo demo totalmente funcional está disponível, permitindo que usuários testem a integração Pluggy sem conectar suas contas bancárias reais.

**Nota:** Para usar a integração Pluggy, você precisa configurar as variáveis de ambiente `PLUGGY_CLIENT_ID` e `PLUGGY_CLIENT_SECRET`.

## Preparação para Machine Learning e IA

FinanceUni foi arquitetado pensando em evolução futura com inteligência artificial:

*   **Separação de Dados:** Transações manuais e dados bancários reais (via Pluggy) são armazenados separadamente, facilitando o treinamento de modelos de ML com dados reais.
*   **Estrutura de Dados Otimizada:** O schema do banco de dados foi projetado para análise e extração de padrões financeiros.
*   **Roadmap de IA:** 
    *   Análise preditiva de gastos baseada em histórico bancário real
    *   Recomendações personalizadas de economia e investimento
    *   Detecção de padrões anormais de gastos
    *   Assistente virtual para dicas de organização financeira
    *   Insights automáticos baseados em comportamento financeiro

A arquitetura atual já suporta a futura integração de modelos de Machine Learning que analisarão os dados da Pluggy e fornecerão insights inteligentes através de uma camada de IA.

## Gamificação

FinanceUni incorpora um motor de gamificação para aumentar o engajamento do usuário e motivar usuários a alcançarem suas metas financeiras.

*   **XP (Pontos de Experiência):** Usuários ganham XP por completar ações, como adicionar transações ou definir metas financeiras.
*   **Níveis:** Usuários sobem de nível conforme acumulam XP, desbloqueando novos recursos e recompensas.
*   **Metas:** Usuários podem definir metas financeiras (ex: economizar uma determinada quantia) e acompanhar seu progresso.
*   **Conquistas:** Usuários ganham conquistas por alcançar marcos ou completar tarefas específicas.
*   **Feedback Visual:** O frontend fornece feedback visual, como barras de progresso, efeitos de confete e transições animadas, para manter os usuários engajados.

O motor de gamificação é implementado no `GamificationService` e usa DTOs para gerenciar dados de conquistas, metas e histórico de XP.

## Planos e Modelos de Negócio

FinanceUni oferece três planos distintos para atender diferentes perfis de usuários:

### 💎 Plano Individual - R$ 39,90/mês
Ideal para controle de finanças pessoais:
*   Metas ilimitadas
*   Dashboard completo
*   Relatórios detalhados
*   Conquistas + gamificação
*   Exportação de dados
*   Conexão com até 2 bancos

### 🏢 Plano Empresas - R$ 150/mês (Mais Popular)
Para micro e médias empresas com foco em crescimento:
*   Todos os recursos do plano Individual
*   Múltiplos usuários (até 5)
*   Painel administrativo avançado
*   Relatórios corporativos completos
*   Dashboard por setor/área
*   Conexão com até 8 bancos
*   Suporte prioritário

### 🎓 Plano Enterprise - Sob Consulta
Para universidades e grandes empresas:
*   Todos os recursos do plano Empresas
*   Usuários ilimitados
*   Ranking gamificado para equipes
*   Módulo completo de Educação Financeira
*   API corporativa completa
*   Integrações personalizadas
*   Suporte dedicado com SLA
*   Onboarding assistido para equipes

## Contribuindo

Damos boas-vindas a contribuições para o FinanceUni! Por favor, siga estas diretrizes:

1.  **Faça um fork do repositório.**
2.  **Crie uma nova branch para sua funcionalidade ou correção de bug.**
3.  **Siga os padrões de código:**
    *   Use PSR-12 para código PHP.
    *   Use ESLint e Prettier para código JavaScript.
    *   Escreva código claro e conciso com comentários significativos.
4.  **Escreva testes para seu código.**
5.  **Envie um pull request com uma descrição clara de suas mudanças.**

**Informações de Contato:**

> Para quaisquer dúvidas ou sugestões, por favor entre em contato conosco em <seu_email@exemplo.com>.

## Licença

Este projeto está licenciado sob a Licença GoFDD - veja o arquivo [LICENSE](/LICENSE) para detalhes.