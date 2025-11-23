# **1. Arquitetura Geral da Solução**

O FinanceUni foi projetado com foco em organização, escalabilidade e clareza arquitetural.

### **🧱 Estrutura em duas camadas principais**

- **Frontend (Vue 3 + Vite)**
    
    MVC adaptado + componentização
    
- **Backend (Laravel 11)**
    
    MVC tradicional + Services + DTOs
    

### **💡 Comunicação**

REST API JSON autenticada com **Laravel Sanctum**

Integração bancária via **Pluggy API**

### **📦 Banco de Dados**

MySQL com migrações, seeds e relacionamentos normalizados.

---

# **2. Frontend — MVC Adaptado + Componentização**

Embora o Vue opere como MVVM, nossa estrutura assume uma organização clara inspirada em MVC.

## **Model (Front)**

- Services (TransactionService, CategoryService, DashboardService etc.)
- Schemas de validação (Vee-Validate)
- Máscaras e formatadores (Vue-The-Mask, Currency-Input)

## **View**

- Telas (Receitas.vue, Despesas.vue, Dashboard.vue)
- Componentes reutilizáveis (modais, tabelas, cards)
- Templates reativos e responsivos

## **Controller**

- Lógicas dentro do `<script setup>`
- Eventos de UI → Services → Backend
- Estados locais controlados por refs/computed

## **Stack Frontend**

- Vue 3 + Composition API
- Vue Router
- Axios
- TailwindCSS 4
- DaisyUI (componentes de UI prontos e padronizados)
- Lucide Icons, HeroIcons, FontAwesome
- Chart.js + Vue-ChartJS
- Confetti (gamificação)
- v-mask / vue-the-mask / vue-currency-input

💡 *Resultado:* UI leve, responsiva, consistente e de alta produtividade.

---

# **3. Backend — Laravel 11 (MVC Puro) + Services + DTOs**

### **Models Principais**

- User, Transaction, Category
- Achievement, Goal, XpHistory
- PluggyItem, BankAccount
- PendingUser (confirmação por e-mail)

### **Scopes Importantes**

```php
income()
expense()
fromUser()

```

### **Controllers**

- AuthController
- TransactionController
- CategoryController
- DashboardController
- GamificationController
- PluggyController

### **Camada de Serviços**

- **PluggyService** – integração bancária
- **GamificationService** – engine de XP/Níveis/Metas
- **AuthService** – autenticação e fluxo de login

### **DTOs (padrão aplicado)**

- AchievementDTO
- GoalDTO
- XpHistoryDTO

Justificativa: **evitam vazamento de lógica e padronizam transporte entre camadas.**

---

# **4. Segurança — Implementado + Melhorias Futuras**

### 🔐 **O que já existe**

- Autenticação via **Sanctum** (SPA tokens seguros)
- Validação forte nas requests
- Scopes `fromUser()` prevenindo acesso indevido
- Middlewares de CORS
- Sanitização automática (Trim, ConvertEmptyStringsToNull)

### 🛡 Melhorias recomendadas

- **HTTPS + HSTS**
- **Cookies HttpOnly + Secure**
- Rate limiting por rota
- 2FA / MFA
- Hashids/UUID para evitar enumeration
- Monitoramento Sentry
- Criptografia de colunas sensíveis (Encrypted Casts)

---

# **5. Integração Pluggy — Modo Real + Demo**

Implementação isolada no **PluggyService**:

- Autenticação com Pluggy API
- Sincronização de contas
- Importação de transações
- Normalização → Gravação no BD (`source = pluggy`)
- Modo **DEMO** totalmente funcional
- Garantia de que o frontend continue funcionando mesmo sem integração bancária

---

# **6. Gamificação — Completa e Integrada**

Engine própria de gamificação:

- XP acumulado por ações
- Cálculo automático de nível (progressão linear)
- DTOs exclusivos para conquistas/metas
- Histórico de XP
- Frontend com progress bar animada + efeitos visuais
- Trigger automático de conquistas/eventos

💡 Torna o sistema mais engajador e educacional.

---

# **7. Funcionalidades Técnicas que Devem Ser Demonstradas**

### **Receitas & Despesas**

- CRUD completo
- Categorias por tipo (`income` ou `expense`)
- Modal único e reutilizável
- Maior receita / maior despesa
- Economia vs mês anterior (cálculo no backend)

### **Dashboard**

- Resumo financeiro
- Chart.js (gastos por categoria)
- XP + nível + progresso
- Dados bancários (Pluggy) opcionais

### **Experiência do Usuário**

- Máscaras, validações e UX limpa
- Resultados instantâneos
- Feedback visual (loading, confetti, cores, modais)

---

# **8. Conclusão Técnica**

O FinanceUni demonstra:

- Arquitetura sólida (MVC → front e back)
- Clareza entre camadas (controllers → services → models)
- Padrões avançados (DTOs, scopes, service layer)
- Gamificação integrada
- Integração real com APIs externas
- Segurança implementada e planejada
- Base pronta para escalar novas funções (microserviços, mobile, BI, etc.)
