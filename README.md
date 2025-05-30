POSTMAN Collection = https://luana-4762307.postman.co/workspace/Luana's-Workspace~af687210-d5b5-422b-8fbc-afad669147b7/collection/45298415-5c55a1a3-d56d-4195-b765-7c993ccb97ee?action=share&creator=45298415

# 🚀 Projeto Laravel com Docker

Este projeto é um ambiente Laravel rodando com Docker, utilizando PostgreSQL como banco de dados.

---

## 🐳 Pré-requisitos

- [Docker](https://www.docker.com/products/docker-desktop) instalado
- [Git](https://git-scm.com/) instalado

---


### 1️ - Clone o projeto

```bash
git clone https://github.com/usuario/repositorio.git
cd repositorio
```

### 2️ -  Copie o arquivo de ambiente
```bash
cp .env.example .env
```

### 3 - Configure o arquivo .env
```bash
DB_CONNECTION=pgsql
DB_HOST=localhost 
DB_PORT=5432  
DB_DATABASE=banco-desafio
DB_USERNAME=postgres
DB_PASSWORD=123
```

### 4 - Suba os containers para o Docker
```bash
docker-compose up -d --build
```

### 5 - No terminal dentro do container, execute:
```bash
composer install
```

### 6 - Execute o projeto:
```bash
php artisan serve
```
