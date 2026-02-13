# ✅ Checklist de Deploy - Sistema de Inventário

Imprima ou salve este checklist para acompanhar seu deploy passo a passo.

---

## 📋 FASE 1: Preparação do Servidor

### Servidor Debian 13

- [ ] Servidor Debian 13 instalado e acessível via SSH
- [ ] Sistema atualizado (`sudo apt update && sudo apt upgrade -y`)
- [ ] Usuário com permissões sudo criado
- [ ] Firewall básico configurado (portas 22, 80, 443)
- [ ] Domínio DNS apontado para IP do servidor (se aplicável)

**Tempo estimado:** 15 minutos

---

## 🐳 FASE 2: Instalação do Docker

### Docker Engine

- [ ] Docker instalado (`curl -fsSL https://get.docker.com | sh`)
- [ ] Usuário adicionado ao grupo docker (`sudo usermod -aG docker $USER`)
- [ ] Docker Compose instalado (incluído no Docker Engine)
- [ ] Instalação verificada (`docker --version`)
- [ ] Docker iniciado no boot (`sudo systemctl enable docker`)

**Tempo estimado:** 10 minutos

---

## 📁 FASE 3: Preparação do Projeto

### Código Fonte

- [ ] Diretório criado (`/var/www/inventario`)
- [ ] Código copiado/clonado para o servidor
- [ ] Permissões corretas no diretório
- [ ] Arquivo `.env` criado (copiar de `.env.docker`)
- [ ] Scripts executáveis (`chmod +x *.sh`)

### Configuração .env

- [ ] `APP_NAME` definido
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` configurado
- [ ] `DB_DATABASE` definido
- [ ] `DB_USERNAME` definido
- [ ] `DB_PASSWORD` definido (senha forte!)
- [ ] `REDIS_HOST=redis`
- [ ] `CACHE_DRIVER=redis`
- [ ] `SESSION_DRIVER=redis`

**Tempo estimado:** 10 minutos

---

## 🚀 FASE 4: Deploy Inicial

### Build e Inicialização

- [ ] Build das imagens (`docker compose build`)
- [ ] Containers iniciados (`docker compose up -d`)
- [ ] Status verificado (`docker compose ps`)
- [ ] Logs verificados (`docker compose logs -f`)
- [ ] Todos os containers "Up" (app, mysql, redis)

### Configuração da Aplicação

- [ ] APP_KEY gerado (`docker compose exec app php artisan key:generate`)
- [ ] Dependências instaladas (`docker compose exec app composer install --no-dev`)
- [ ] Migrations executadas (`docker compose exec app php artisan migrate --force`)
- [ ] Usuário admin criado (`docker compose exec app php artisan crudbooster:install`)
- [ ] Caches gerados (`docker compose exec app php artisan optimize`)
- [ ] Permissões ajustadas (`chmod -R 775 storage bootstrap/cache`)

### Importação de Dados (se aplicável)

- [ ] Dump do banco importado
- [ ] Triggers executados
- [ ] Dados verificados

**Tempo estimado:** 20 minutos

---

## 🌐 FASE 5: Configuração Web

### Nginx Reverse Proxy (Host)

- [ ] Nginx instalado no host (`sudo apt install nginx`)
- [ ] Arquivo de configuração copiado (`nginx-reverse-proxy.conf`)
- [ ] Domínio editado no arquivo de configuração
- [ ] Site ativado (`ln -s /etc/nginx/sites-available/inventario /etc/nginx/sites-enabled/`)
- [ ] Configuração testada (`sudo nginx -t`)
- [ ] Nginx reiniciado (`sudo systemctl restart nginx`)

### Teste HTTP

- [ ] Aplicação acessível via HTTP
- [ ] Login funcionando
- [ ] Dashboard carregando
- [ ] Módulos principais acessíveis

**Tempo estimado:** 15 minutos

---

## 🔒 FASE 6: SSL/HTTPS

### Certbot

- [ ] Certbot instalado (`sudo apt install certbot python3-certbot-nginx`)
- [ ] Certificado obtido (`sudo certbot --nginx -d seu-dominio.com`)
- [ ] Renovação automática testada (`sudo certbot renew --dry-run`)
- [ ] Redirect HTTP → HTTPS funcionando
- [ ] Certificado válido (verificar no navegador)

### Teste HTTPS

- [ ] Site acessível via HTTPS
- [ ] Certificado válido (cadeado verde)
- [ ] Sem avisos de segurança
- [ ] Todas as páginas carregando via HTTPS

**Tempo estimado:** 10 minutos

---

## 🔐 FASE 7: Segurança

### Firewall (UFW)

- [ ] UFW instalado (`sudo apt install ufw`)
- [ ] Regras configuradas (SSH, HTTP, HTTPS)
- [ ] UFW ativado (`sudo ufw enable`)
- [ ] Status verificado (`sudo ufw status`)

### Fail2Ban

- [ ] Fail2Ban instalado (`sudo apt install fail2ban`)
- [ ] Serviço iniciado (`sudo systemctl start fail2ban`)
- [ ] Serviço habilitado no boot (`sudo systemctl enable fail2ban`)

### Senhas e Chaves

- [ ] Senha do MySQL forte e segura
- [ ] Senha do admin forte
- [ ] APP_KEY único gerado
- [ ] Arquivo .env com permissões 600

**Tempo estimado:** 15 minutos

---

## 💾 FASE 8: Backup

### Configuração de Backup

- [ ] Script de backup testado (`./backup-db.sh`)
- [ ] Diretório de backups criado
- [ ] Backup manual executado com sucesso
- [ ] Backup verificado (arquivo .sql.gz criado)

### Backup Automático

- [ ] Cron job configurado (`crontab -e`)
- [ ] Horário de backup definido (ex: 2h da manhã)
- [ ] Rotação de backups configurada (manter últimos 7)
- [ ] Teste de restore executado (`./restore-db.sh`)

### Backup de Arquivos

- [ ] Storage/uploads com backup configurado
- [ ] Backup do .env em local seguro
- [ ] Documentação de restore criada

**Tempo estimado:** 15 minutos

---

## 📊 FASE 9: Monitoramento

### Logs

- [ ] Logs da aplicação acessíveis
- [ ] Logrotate configurado
- [ ] Logs do Docker funcionando
- [ ] Logs do Nginx funcionando

### Health Checks

- [ ] Endpoint de health check testado
- [ ] Monitoramento de recursos (`docker stats`)
- [ ] Alertas configurados (opcional)

**Tempo estimado:** 10 minutos

---

## 🧪 FASE 10: Testes Finais

### Testes Funcionais

- [ ] Login com usuário admin
- [ ] Criar categoria
- [ ] Criar produto
- [ ] Criar cliente
- [ ] Criar fornecedor
- [ ] Registrar compra
- [ ] Registrar venda
- [ ] Verificar estoque atualizado
- [ ] Gerar relatório

### Testes de Performance

- [ ] Tempo de resposta aceitável (< 2s)
- [ ] Páginas carregando rapidamente
- [ ] Sem erros no console do navegador
- [ ] Sem erros nos logs

### Testes de Segurança

- [ ] HTTPS funcionando
- [ ] Redirect HTTP → HTTPS
- [ ] Headers de segurança presentes
- [ ] Sem informações sensíveis expostas
- [ ] APP_DEBUG=false verificado

**Tempo estimado:** 20 minutos

---

## 📝 FASE 11: Documentação

### Documentação do Deploy

- [ ] Credenciais documentadas (em local seguro)
- [ ] Configurações documentadas
- [ ] Procedimentos de backup documentados
- [ ] Procedimentos de restore documentados
- [ ] Contatos de suporte documentados

### Treinamento

- [ ] Usuários treinados
- [ ] Manual de uso entregue
- [ ] Procedimentos de manutenção explicados

**Tempo estimado:** 30 minutos

---

## 🎉 FASE 12: Go Live

### Pré-Go Live

- [ ] Todos os itens acima verificados
- [ ] Backup completo realizado
- [ ] Plano de rollback preparado
- [ ] Equipe de suporte disponível

### Go Live

- [ ] Aplicação em produção
- [ ] Usuários notificados
- [ ] Monitoramento ativo
- [ ] Suporte disponível

### Pós-Go Live

- [ ] Monitorar por 24h
- [ ] Verificar logs regularmente
- [ ] Coletar feedback dos usuários
- [ ] Ajustes necessários realizados

**Tempo estimado:** Contínuo

---

## 📈 Manutenção Contínua

### Diário

- [ ] Verificar logs de erro
- [ ] Verificar uso de recursos
- [ ] Verificar backups

### Semanal

- [ ] Testar restore de backup
- [ ] Revisar logs de acesso
- [ ] Verificar atualizações de segurança

### Mensal

- [ ] Atualizar dependências
- [ ] Revisar performance
- [ ] Limpar logs antigos
- [ ] Revisar usuários e permissões

---

## ⏱️ Resumo de Tempo

| Fase             | Tempo Estimado |
| ---------------- | -------------- |
| 1. Preparação    | 15 min         |
| 2. Docker        | 10 min         |
| 3. Projeto       | 10 min         |
| 4. Deploy        | 20 min         |
| 5. Web           | 15 min         |
| 6. SSL           | 10 min         |
| 7. Segurança     | 15 min         |
| 8. Backup        | 15 min         |
| 9. Monitoramento | 10 min         |
| 10. Testes       | 20 min         |
| 11. Documentação | 30 min         |
| **TOTAL**        | **~3 horas**   |

---

## 🆘 Contatos de Emergência

**Suporte Técnico:**

- Nome: ******\_\_\_******
- Telefone: ******\_\_\_******
- Email: ******\_\_\_******

**Servidor:**

- IP: ******\_\_\_******
- Domínio: ******\_\_\_******
- Provedor: ******\_\_\_******

**Banco de Dados:**

- Host: mysql (container)
- Database: ******\_\_\_******
- Usuário: ******\_\_\_******

---

## ✅ Assinaturas

**Deploy realizado por:**

- Nome: ******\_\_\_******
- Data: ******\_\_\_******
- Assinatura: ******\_\_\_******

**Aprovado por:**

- Nome: ******\_\_\_******
- Data: ******\_\_\_******
- Assinatura: ******\_\_\_******

---

**Versão do Checklist:** 1.0  
**Data:** Fevereiro 2026  
**Sistema:** Laravel 5.7 + CRUDBooster 5.4 + Docker
