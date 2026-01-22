# Mini Controle de Fábrica de Software – Backend

API REST desenvolvida em **Laravel** para controle de clientes, projetos, lançamentos de horas (timesheet) e cálculo de lucratividade por projeto.

Este projeto faz parte de um desafio técnico com foco em **operações e custos de uma fábrica de software**.

---

##  Tecnologias Utilizadas

- PHP 8+
- Laravel 12
- MySQL
- Composer
- Git / GitHub

---

##  Estrutura do Projeto

- `app/Models` → Models (Cliente, Projeto, Lancamento)
- `app/Http/Controllers/Api` → Controllers da API
- `database/migrations` → Versionamento do banco de dados
- `routes/api.php` → Rotas da API
- `public/` → Entrada da aplicação

---

##  Como rodar o projeto localmente

### 1️ Clonar o repositório

```bash
git clone https://github.com/dev-froes/fabrica-software-backend.git
cd fabrica-software-backend

2️ Instalar dependências
composer install

3️ Configurar variáveis de ambiente
cp .env.example .env
php artisan key:generate

Configure no .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fabrica_software
DB_USERNAME=SEU_USUARIO
DB_PASSWORD=SUA_SENHA

4️ Rodar migrations
php artisan migrate

5️ Subir o servidor
php artisan serve

A API estará disponível em:

http://127.0.0.1:8000

 Endpoints Principais
Clientes

GET /api/clientes

POST /api/clientes

GET /api/clientes/{id}

PUT /api/clientes/{id}

DELETE /api/clientes/{id}

Projetos

GET /api/projetos

POST /api/projetos

GET /api/projetos/{id}

PUT /api/projetos/{id}

DELETE /api/projetos/{id}

Lançamentos de Horas

GET /api/lancamentos

POST /api/lancamentos

GET /api/lancamentos/{id}

PUT /api/lancamentos/{id}

DELETE /api/lancamentos/{id}

 Dashboard de Lucratividade

Endpoint específico para cálculo financeiro do projeto:

GET /api/projetos/{id}/dashboard?inicio=YYYY-MM-DD&fim=YYYY-MM-DD


Retorna:

Horas totais

Custo total

Receita

Margem bruta (R$ e %)

Break-even

Resumo por tipo de demanda

 Autor

Projeto desenvolvido por Daniel Fróes para desafio técnico.