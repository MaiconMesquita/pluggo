# Brandscard Backend

Esse projeto é uma API Restful com intuito de gerir transações por cartão virtual(baas) tanto para os usuário comuns quanto aos estabelecimentos.

## Status

![Build and Tests](https://github.com/AdagaDigital/Blue-Assist-Backend/actions/workflows/ci.yaml/badge.svg)

## Tecnologias

O projeto é construído com:

![MySql](https://img.shields.io/badge/MySql-black.svg?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=jsonwebtokens&logoColor=white)
![Docker](https://img.shields.io/badge/docker-black.svg?style=for-the-badge&logo=docker&logoColor=white)
![Doctrine](https://img.shields.io/badge/Doctrine%20ORM-black.svg?style=for-the-badge&logo=doctrine&logoColor=white)
![PHP](https://img.shields.io/badge/php-black?style=for-the-badge&logo=php&logoColor=white)

## Inicialização

1. Para inicializar o projeto, é necessário ter ambos o Docker e o Composer instalados em sua máquina, e rodar o seguinte comando:

```bash
docker-compose up
```

> OBS: Necessário verificar se a porta 3306 do seu computador não está em uso por outro server de banco de dados, caso esteja é necessário parar o serviço ou alterar a porta da imagem blueassist_db!!!

Isso iniciará um container com 4 imagens: a aplicação, uma stack, um database e o phpMyAdmin.

2. Após o container iniciar com as imagens rodando, você poderá acessar o phpMyAdmin em `localhost:8181` e precisa executar as migrations:

```bash
make migration-run
```

3. E depois popular o banco com:

```bash
make run-seed
```

4. Após isso, a aplicação estará apta a receber as requisições em `localhost:8080` :D
