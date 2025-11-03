# 🗺️ Roadmap de Implementação - SAFI NFE

> **Data**: 2025-11-03
> **Total de Issues**: 12
> **Duração Estimada**: 6-8 sprints (3-4 meses)

---

## 📊 Visão Geral

Este roadmap organiza as **12 issues** de melhoria em uma sequência lógica de implementação, considerando:
- **Dependências técnicas** entre issues
- **Impacto no negócio** (valor entregue)
- **Risco de implementação**
- **Esforço necessário**

### Issues por Prioridade

| Prioridade | Quantidade | Issues |
|-----------|------------|---------|
| 🔴 CRÍTICA | 1 | #19 |
| 🟠 ALTA | 4 | #12, #13, #14, #15, #17 |
| 🟡 MÉDIA | 6 | #16, #18, #20, #21, #22 |
| 🟢 BAIXA | 1 | #23 |

---

## 🎯 Fase 1: PROTEÇÃO E FUNDAÇÃO (Sprint 1-2)

**Objetivo**: Proteger dados críticos e corrigir problemas de segurança/qualidade urgentes.

### Sprint 1: Proteção de Dados (2 semanas)

#### 🔴 [#19 - Estratégia de Backup e Disaster Recovery](https://github.com/themegazord/safinfe-monolito/issues/19)
**Prioridade**: CRÍTICA | **Estimativa**: 5 pontos | **Responsável**: DevOps + Backend

**Por que primeiro?**
- XMLs fiscais são obrigação legal (não podem ser perdidos)
- Implementação independente (não depende de outras issues)
- Proteção imediata dos dados

**Entregáveis**:
- [ ] Spatie Laravel Backup instalado
- [ ] Backup diário automático para S3
- [ ] Política de retenção configurada (7d → 30d → 90d → 7 anos)
- [ ] Teste de restore executado e documentado
- [ ] Monitoramento de backups (Slack/Email)
- [ ] Documentação de disaster recovery

**Tempo estimado**: 8-10 dias úteis

**Critério de sucesso**: Backup rodando diariamente sem falhas por 1 semana

---

### Sprint 2: Segurança e Qualidade (2 semanas)

#### 🟠 [#12 - Validação Completa de CNPJ](https://github.com/themegazord/safinfe-monolito/issues/12)
**Prioridade**: ALTA | **Estimativa**: 3 pontos | **Responsável**: Backend

**Por que agora?**
- Dados inválidos podem causar problemas fiscais
- Implementação rápida e focada
- Não depende de outras issues

**Entregáveis**:
- [ ] Helper `DocumentValidator` criado
- [ ] Validação de dígitos verificadores implementada
- [ ] XMLService atualizado
- [ ] Forms (EmpresaForm, ContabilidadeForm) atualizados
- [ ] Script de auditoria de dados existentes executado
- [ ] CNPJs inválidos corrigidos ou reportados

**Tempo estimado**: 4-6 dias úteis

**Critério de sucesso**: 0% de CNPJs inválidos no banco de dados

---

#### 🟠 [#15 - Remover Código Comentado e Código Morto](https://github.com/themegazord/safinfe-monolito/issues/15)
**Prioridade**: ALTA | **Estimativa**: 3 pontos | **Responsável**: Time completo

**Por que agora?**
- Reduz débito técnico antes de refatorações maiores
- Melhora legibilidade para próximas sprints
- Implementação distribuída entre o time

**Entregáveis**:
- [ ] Dashboard.php limpo (remover linhas 109-206)
- [ ] XMLController.php corrigido (unlink após return)
- [ ] PHPStan configurado e executando
- [ ] Laravel Pint configurado
- [ ] Auditoria completa de código comentado
- [ ] Documentação de boas práticas

**Tempo estimado**: 4-5 dias úteis

**Critério de sucesso**: 0 warnings do PHPStan, 0 blocos grandes de código comentado

---

## 🚀 Fase 2: PERFORMANCE E SEGURANÇA (Sprint 3-4)

**Objetivo**: Otimizar performance crítica e implementar controle de acesso granular.

### Sprint 3: Performance Crítica (2 semanas)

#### 🟠 [#14 - Adicionar Índices de Banco de Dados](https://github.com/themegazord/safinfe-monolito/issues/14)
**Prioridade**: ALTA | **Estimativa**: 3 pontos | **Responsável**: Backend/DBA

**Por que agora?**
- Impacto imediato na performance (queries 40x mais rápidas)
- Necessário antes de escalar o sistema
- Preparação para #13 (storage de XMLs)

**Entregáveis**:
- [ ] Script de validação de duplicatas executado
- [ ] Migration de índices criada
- [ ] Índices UNIQUE em CNPJs (empresas, contabilidades)
- [ ] Índices em FKs (clientes, contadores)
- [ ] Índice composto em empresa_contabilidade
- [ ] EXPLAIN antes/depois documentado
- [ ] Laravel Telescope instalado (monitoramento)

**Tempo estimado**: 5-7 dias úteis

**Critério de sucesso**: Queries principais <100ms, sem table scans

---

#### 🟡 [#21 - Implementar Cache Estratégico](https://github.com/themegazord/safinfe-monolito/issues/21)
**Prioridade**: MÉDIA | **Estimativa**: 3 pontos | **Responsável**: Backend

**Por que agora?**
- Complementa #14 (índices) para performance máxima
- Redis pode ser configurado junto com índices
- Reduz carga no banco antes de #13

**Entregáveis**:
- [ ] Redis configurado (produção)
- [ ] Cache implementado em repositórios principais
- [ ] Cache tags configurado
- [ ] Invalidação automática funcionando
- [ ] Dashboard com cache (30min TTL)
- [ ] Monitoramento de hit rate

**Tempo estimado**: 4-6 dias úteis

**Critério de sucesso**: 70%+ de cache hit rate em queries frequentes

---

### Sprint 4: Segurança de Acesso (2 semanas)

#### 🟠 [#17 - Implementar Laravel Policies](https://github.com/themegazord/safinfe-monolito/issues/17)
**Prioridade**: ALTA | **Estimativa**: 5 pontos | **Responsável**: Backend

**Por que agora?**
- Segurança crítica (evitar acesso indevido)
- Base sólida já estabelecida (fases 1 e 2)
- Não depende de outras issues

**Entregáveis**:
- [ ] EmpresaPolicy criada e registrada
- [ ] XMLPolicy criada e registrada
- [ ] ClientePolicy criada e registrada
- [ ] ContadorPolicy criada e registrada
- [ ] ContabilidadePolicy criada e registrada
- [ ] Gates para importação e versionamento
- [ ] Authorization em todos Controllers/Livewire
- [ ] Queries filtradas por permissão
- [ ] Blade directives aplicadas (@can)

**Tempo estimado**: 8-10 dias úteis

**Critério de sucesso**: 100% das rotas protegidas, matriz de permissões implementada

---

## 🏗️ Fase 3: ESCALABILIDADE (Sprint 5-6)

**Objetivo**: Preparar sistema para crescimento e otimizar armazenamento.

### Sprint 5: Armazenamento de XMLs (2-3 semanas)

#### 🟠 [#13 - Otimizar Armazenamento de XMLs](https://github.com/themegazord/safinfe-monolito/issues/13)
**Prioridade**: ALTA | **Estimativa**: 8 pontos | **Responsável**: Backend

**Por que agora?**
- Backup já implementado (#19) - segurança garantida
- Performance otimizada (#14, #21) - migração mais rápida
- Issue mais complexa, precisa de tempo

**Entregáveis - Fase 1** (Filesystem):
- [ ] Disco dedicado configurado
- [ ] Model XML atualizado (getters/setters)
- [ ] Compressão GZIP implementada
- [ ] Estrutura de diretórios (YYYY/MM/empresa_id/)
- [ ] Migration de dados existentes (background job)
- [ ] Validação de integridade

**Entregáveis - Fase 2** (S3 - Opcional):
- [ ] S3 bucket configurado
- [ ] Lifecycle policy (Glacier)
- [ ] Storage facade atualizado
- [ ] Testes de leitura/escrita

**Tempo estimado**: 10-15 dias úteis

**Critério de sucesso**:
- Banco 80% menor
- Queries de XML 5x mais rápidas
- 100% dos XMLs migrados com sucesso

---

### Sprint 6: Organização e Refactor (1-2 semanas)

#### 🟡 [#16 - Consolidar Diretórios Trait e Traits](https://github.com/themegazord/safinfe-monolito/issues/16)
**Prioridade**: MÉDIA | **Estimativa**: 2 pontos | **Responsável**: Backend

**Por que agora?**
- Aproveitar que código está limpo (#15)
- Preparação para documentação futura
- Baixo risco após refatorações grandes

**Entregáveis**:
- [ ] Nova estrutura de diretórios criada
- [ ] Arquivos movidos com `git mv`
- [ ] Namespaces atualizados
- [ ] Imports atualizados em todo projeto
- [ ] Diretório antigo removido
- [ ] PSR-4 autoload atualizado

**Tempo estimado**: 3-4 dias úteis

**Critério de sucesso**: 1 único diretório `app/Traits/`, código funcionando

---

#### 🟢 [#23 - Padronizar Nomenclatura de BD](https://github.com/themegazord/safinfe-monolito/issues/23)
**Prioridade**: BAIXA | **Estimativa**: 2 pontos | **Responsável**: Backend

**Por que agora?**
- Aproveitar que está refatorando (#16)
- Preparação para documentação
- Baixo impacto, rápida implementação

**Entregáveis**:
- [ ] Migration de rename criada
- [ ] `contadors` → `contadores`
- [ ] `empcont` → `empresa_contabilidade`
- [ ] Models atualizados
- [ ] Repositories atualizados
- [ ] Todas queries atualizadas

**Tempo estimado**: 3-4 dias úteis

**Critério de sucesso**: Nomenclatura consistente em português

---

## 🎨 Fase 4: EXPERIÊNCIA E AUTOMAÇÃO (Sprint 7-8)

**Objetivo**: Melhorar UX e automatizar processos de desenvolvimento.

### Sprint 7: Melhorias de Frontend (2 semanas)

#### 🟡 [#18 - Migrar de jQuery para Solução Moderna](https://github.com/themegazord/safinfe-monolito/issues/18)
**Prioridade**: MÉDIA | **Estimativa**: 3 pontos | **Responsável**: Frontend

**Por que agora?**
- Sistema estável (após refatorações)
- Preparação para #22 (exportação)
- Melhora experiência do usuário

**Entregáveis**:
- [ ] IMask.js instalado via npm
- [ ] Arquivo `resources/js/masks.js` criado
- [ ] Todas máscaras migradas (data, CEP, CNPJ, dinheiro, etc)
- [ ] jQuery removido do package.json
- [ ] jQuery Mask removido
- [ ] Scripts inline removidos do layout
- [ ] Bundle size comparado

**Tempo estimado**: 4-6 dias úteis

**Critério de sucesso**: Bundle 60% menor, todas máscaras funcionando

---

#### 🟡 [#22 - Implementar Exportação de Relatórios](https://github.com/themegazord/safinfe-monolito/issues/22)
**Prioridade**: MÉDIA | **Estimativa**: 5 pontos | **Responsável**: Backend/Frontend

**Por que agora?**
- PhpSpreadsheet já instalado
- Performance otimizada (#14, #21, #13)
- Funcionalidade muito solicitada

**Entregáveis - PDF**:
- [ ] DomPDF instalado
- [ ] Template PDF para Movimento
- [ ] Template PDF para Consulta XML
- [ ] Exportação funcionando

**Entregáveis - Excel**:
- [ ] Exportação Excel para Movimento
- [ ] Exportação Excel para Consulta XML
- [ ] Formatação (cabeçalhos, autosize)
- [ ] Fórmulas básicas

**Entregáveis - UI**:
- [ ] Botões de exportação adicionados
- [ ] Loading states implementados
- [ ] Download automático funcionando

**Tempo estimado**: 8-10 dias úteis

**Critério de sucesso**: Todos relatórios exportáveis em PDF e Excel

---

### Sprint 8: Automação e DevOps (2 semanas)

#### 🟡 [#20 - Implementar CI/CD Pipeline](https://github.com/themegazord/safinfe-monolito/issues/20)
**Prioridade**: MÉDIA | **Estimativa**: 5 pontos | **Responsável**: DevOps

**Por que agora?**
- Última issue (todas melhorias implementadas)
- Automatiza deploy das próximas features
- Garante qualidade futura

**Entregáveis**:
- [ ] GitHub Actions configurado
- [ ] Workflow de Lint (Pint, PHPStan)
- [ ] Workflow de Deploy para staging
- [ ] Workflow de Deploy para produção
- [ ] Workflow de Security Audit (semanal)
- [ ] Branch protection configurado
- [ ] Secrets configurados
- [ ] Documentação de CI/CD

**Tempo estimado**: 8-10 dias úteis

**Critério de sucesso**: Deploy automático funcionando, 0 deploys manuais

---

#### 🟡 [#DOCS - Expandir Documentação](https://github.com/themegazord/safinfe-monolito/issues/20)
**Prioridade**: MÉDIA | **Estimativa**: 3 pontos | **Responsável**: Time completo

**Por que agora?**
- Documentar tudo que foi implementado
- Facilitar onboarding futuro
- Base de conhecimento

**Entregáveis**:
- [ ] README.md expandido
- [ ] ARCHITECTURE.md criado
- [ ] CONTRIBUTING.md criado
- [ ] docs/DEVELOPMENT.md criado
- [ ] docs/API.md expandido
- [ ] docs/DEPLOYMENT.md criado
- [ ] docs/TROUBLESHOOTING.md criado
- [ ] PHPDoc em classes principais

**Tempo estimado**: 5-7 dias úteis (distribuído)

**Critério de sucesso**: Documentação completa e atualizada

---

## 📅 Cronograma Consolidado

### Resumo por Sprint

| Sprint | Duração | Issues | Foco | Responsável |
|--------|---------|--------|------|-------------|
| **1** | 2 sem | #19 | Proteção de Dados | DevOps |
| **2** | 2 sem | #12, #15 | Segurança e Qualidade | Backend |
| **3** | 2 sem | #14, #21 | Performance | Backend |
| **4** | 2 sem | #17 | Controle de Acesso | Backend |
| **5** | 3 sem | #13 | Armazenamento XMLs | Backend |
| **6** | 1-2 sem | #16, #23 | Refatoração | Backend |
| **7** | 2 sem | #18, #22 | Frontend e Features | Frontend/Backend |
| **8** | 2 sem | #20, DOCS | Automação e Docs | DevOps/Time |

### Timeline Visual

```
Mês 1: ████████ Sprint 1-2 (Proteção e Fundação)
Mês 2: ████████ Sprint 3-4 (Performance e Segurança)
Mês 3: ████████ Sprint 5-6 (Escalabilidade e Refactor)
Mês 4: ████████ Sprint 7-8 (UX e Automação)
```

**Duração Total**: 15-17 semanas (~4 meses)

---

## 🎯 Marcos (Milestones)

### Milestone 1: Sistema Protegido ✅
**Conclusão**: Final do Sprint 2

- [x] Backup automático rodando
- [x] Dados validados (CNPJ correto)
- [x] Código limpo (sem débito técnico)

**Valor entregue**: Segurança e qualidade de dados

---

### Milestone 2: Performance Otimizada 🚀
**Conclusão**: Final do Sprint 4

- [x] Queries 40x mais rápidas (índices)
- [x] Cache implementado (70%+ hit rate)
- [x] Controle de acesso granular

**Valor entregue**: Sistema rápido e seguro

---

### Milestone 3: Escalabilidade Garantida 📈
**Conclusão**: Final do Sprint 6

- [x] XMLs em storage otimizado (banco 80% menor)
- [x] Código organizado e padronizado
- [x] Nomenclatura consistente

**Valor entregue**: Sistema preparado para crescimento

---

### Milestone 4: Produto Completo 🎉
**Conclusão**: Final do Sprint 8

- [x] Frontend moderno (sem jQuery)
- [x] Exportação de relatórios (PDF/Excel)
- [x] CI/CD automático
- [x] Documentação completa

**Valor entregue**: Sistema maduro e automatizado

---

## 📊 Métricas de Acompanhamento

### Métricas por Sprint

#### Sprint 1
- Backups executados com sucesso: **7/7 dias**
- RPO (Recovery Point Objective): **< 6 horas**
- RTO (Recovery Time Objective): **< 2 horas**

#### Sprint 2
- CNPJs inválidos: **0%**
- Linhas de código comentado: **0**
- PHPStan level: **5+**

#### Sprint 3
- Tempo médio de queries: **< 100ms**
- Cache hit rate: **> 70%**
- Table scans: **0**

#### Sprint 4
- Rotas sem authorization: **0%**
- Policies implementadas: **5/5**
- Testes de autorização: **100%**

#### Sprint 5
- Redução tamanho do banco: **> 80%**
- XMLs migrados: **100%**
- Tempo de query XML: **< 50ms**

#### Sprint 6
- Diretórios de traits: **1** (consolidado)
- Tabelas com nomenclatura padrão: **100%**
- Consistência de código: **AAA**

#### Sprint 7
- Bundle size redução: **> 60%**
- Relatórios exportáveis: **100%**
- Formatos suportados: **PDF + Excel**

#### Sprint 8
- Deploys manuais: **0**
- Pipeline success rate: **> 95%**
- Documentação coverage: **> 80%**

---

## ⚠️ Riscos e Mitigações

### Risco 1: Migração de XMLs (#13)
**Probabilidade**: MÉDIA | **Impacto**: ALTO

**Risco**: Perda ou corrupção de dados durante migração.

**Mitigação**:
- ✅ Backup completo antes (#19 já implementado)
- ✅ Migração em background (job com retry)
- ✅ Validação de integridade após migração
- ✅ Rollback documentado

---

### Risco 2: Performance após Refatorações
**Probabilidade**: BAIXA | **Impacto**: MÉDIO

**Risco**: Refatorações podem degradar performance.

**Mitigação**:
- ✅ Laravel Telescope instalado (#14)
- ✅ Benchmarks antes/depois documentados
- ✅ Cache estratégico (#21)

---

### Risco 3: Breaking Changes em Produção
**Probabilidade**: MÉDIA | **Impacto**: ALTO

**Risco**: Issues podem quebrar funcionalidades existentes.

**Mitigação**:
- ✅ Ambiente de staging obrigatório
- ✅ Testes manuais em staging
- ✅ Deploy gradual (canary/blue-green)
- ✅ Rollback rápido documentado

---

### Risco 4: Atraso no Cronograma
**Probabilidade**: MÉDIA | **Impacto**: MÉDIO

**Risco**: Estimativas podem estar otimistas.

**Mitigação**:
- ✅ Buffer de 20% no cronograma
- ✅ Issues priorizadas (pode pular baixa prioridade)
- ✅ Sprints independentes (pode pausar entre elas)

---

## ✅ Checklist de Início de Sprint

Antes de iniciar cada sprint:

- [ ] Revisar issue(s) da sprint
- [ ] Tirar dúvidas técnicas
- [ ] Estimar esforço (story points)
- [ ] Definir responsáveis
- [ ] Criar branch de desenvolvimento
- [ ] Atualizar status no GitHub
- [ ] Comunicar ao time

---

## 📝 Checklist de Fim de Sprint

Ao finalizar cada sprint:

- [ ] Code review completo
- [ ] Testes em staging executados
- [ ] Documentação atualizada
- [ ] Issue fechada no GitHub
- [ ] Deploy em produção
- [ ] Métricas coletadas
- [ ] Retrospectiva da sprint
- [ ] Atualizar este roadmap

---

## 🔄 Processo de Implementação

### Para cada Issue:

1. **Planejamento** (1 dia)
   - Ler issue completa
   - Discutir abordagem com time
   - Estimar esforço
   - Criar branch

2. **Desenvolvimento** (60% do tempo)
   - Implementar solução
   - Seguir critérios de aceitação
   - Commits frequentes

3. **Revisão** (20% do tempo)
   - Code review
   - Ajustes necessários
   - Validação técnica

4. **Testes** (10% do tempo)
   - Testes em staging
   - Validação de negócio
   - Performance check

5. **Deploy** (10% do tempo)
   - Deploy em produção
   - Monitoramento
   - Documentação

---

## 📞 Contatos e Responsabilidades

### Tech Lead
- **Responsável**: [Nome]
- **Tarefas**: Priorização, code review, decisões arquiteturais

### Backend
- **Responsável**: [Nome]
- **Tarefas**: #12, #13, #14, #15, #16, #17, #21, #22, #23

### Frontend
- **Responsável**: [Nome]
- **Tarefas**: #18, #22 (UI)

### DevOps
- **Responsável**: [Nome]
- **Tarefas**: #19, #20

---

## 🎓 Lições Aprendidas

**Atualizar ao final de cada sprint**:

### Sprint 1
- ...

### Sprint 2
- ...

---

## 🔗 Links Importantes

- [Todas as Issues](https://github.com/themegazord/safinfe-monolito/issues)
- [Board do Projeto](https://github.com/themegazord/safinfe-monolito/projects)
- [Análise Completa](./ANALISE-MELHORIAS.md)
- [Documentação Original](./documentacao/)

---

## 📌 Notas Finais

Este roadmap é um **guia flexível**, não uma prisão. Ajuste conforme necessário baseado em:
- Feedback do time
- Descobertas durante implementação
- Prioridades de negócio
- Recursos disponíveis

**Princípio**: Entregar valor incrementalmente. Cada sprint deve deixar o sistema melhor que antes.

**Revisão**: Atualizar este documento a cada sprint completada.

---

**Última atualização**: 2025-11-03
**Próxima revisão**: Ao final do Sprint 1
**Status**: 🟢 Pronto para execução
