# PlugGo

## ⚡️ Nome do Projeto e Proposta

O projeto **PlugGo** consiste em uma aplicação completa (Full-Stack) desenvolvida com o objetivo de **gerenciar motoristas de carros elétricos e seus respectivos pontos de carregamento**.

A solução é dividida em duas partes principais: um **Backend** que atua como uma API Restful para a gestão de dados e um **Frontend** que oferece a interface de usuário para interação com o sistema.

## 🛠 Tecnologias Utilizadas

O projeto é estruturado em duas partes principais, cada uma utilizando um conjunto específico de tecnologias:

### Backend (API Restful)

O backend é construído em PHP e utiliza o framework Slim para a criação da API.

| Categoria | Tecnologia | Versão |
| :--- | :--- | :--- |
| Linguagem | **PHP** | - |
| Framework Web | **Slim Framework** | 4.\* |
| ORM/Banco de Dados | **Doctrine ORM** | 2.16 |
| Migrações | **Doctrine Migrations** | 3.7 |
| Dependências | GuzzleHTTP, Ramsey/UUID, vlucas/phpdotenv, aws/aws-sdk-php, phpmailer/phpmailer, firebase/php-jwt, bref/bref, dompdf/dompdf, rakit/validation, bref/logger, beberlei/doctrineextensions, endroid/qr-code, azuyalabs/yasumi | - |
| Testes | **PHPUnit** | 9.5 |

### Frontend (Interface de Usuário)

O frontend é uma aplicação web moderna baseada em React.

| Categoria | Tecnologia | Versão |
| :--- | :--- | :--- |
| Linguagem | **TypeScript** | 4.9.5 |
| Biblioteca UI | **React** | 19.1.1 |
| Roteamento | **React Router DOM** | 7.8.2 |
| Requisições HTTP | **Axios** | 1.11.0 |
| Gerenciamento de Pacotes | **npm** / **yarn** | - |

## ⚙️ Instruções para Execução Local

Para rodar o projeto PlugGo em seu ambiente local, siga os passos abaixo. O projeto requer que tanto o backend quanto o frontend sejam configurados e iniciados.

### Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas em sua máquina:

*   **Git**
*   **Docker** e **Docker Compose** (para o ambiente de desenvolvimento do Backend)
*   **Node.js** e **npm** ou **yarn** (para o Frontend)

### 1. Clonar o Repositório

```bash
git clone https://github.com/MaiconMesquita/pluggo
cd pluggo
```

### 2. Configuração e Execução do Backend

O backend utiliza Docker Compose para criar um ambiente de desenvolvimento isolado.

1.  **Navegue até o diretório do backend:**
    ```bash
    cd backend
    ```

2.  **Inicie o ambiente Docker:**
    O arquivo `docker-compose.yaml` deve conter a configuração necessária para subir o servidor PHP e o banco de dados.
    ```bash
    docker-compose up -d
    ```

3.  **Instale as dependências do PHP:**
    Você precisará executar o Composer dentro do container do PHP.
    ```bash
    # Exemplo: Encontre o nome do serviço PHP no seu docker-compose.yaml (ex: php-app)
    docker exec -it <nome-do-container-php> composer install
    ```

4.  **Configuração do Banco de Dados e Migrações:**
    Crie o arquivo `.env` com as variáveis de ambiente necessárias (conexão com o banco, chaves de API, etc.). Em seguida, execute as migrações do Doctrine para configurar o esquema do banco de dados.

    ```bash
    # Exemplo de comando para migrações (pode variar dependendo da configuração do projeto)
    docker exec -it <nome-do-container-php> vendor/bin/doctrine-migrations migrate
    ```

### 3. Configuração e Execução do Frontend

1.  **Navegue até o diretório do frontend:**
    ```bash
    cd ../frontend
    ```

2.  **Instale as dependências do Node.js:**
    ```bash
    npm install
    # ou
    yarn install
    ```

3.  **Inicie a aplicação React:**
    O script `start` iniciará o servidor de desenvolvimento do React.
    ```bash
    npm start
    # ou
    yarn start
    ```

A aplicação frontend estará acessível em `http://localhost:3000` (ou outra porta, conforme configurado pelo `react-scripts`).

## 👥 Nomes dos Integrantes do Grupo

Este projeto foi desenvolvido pelos seguintes integrantes:

*   **Bruno França**
*   **Dayane Campos**
*   **Gustavo Carriel**
*   **Leandro Soares**
*   **Maicon Mesquita**
*   **Mateus Lino**
