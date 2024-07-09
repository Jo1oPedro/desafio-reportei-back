# Sobre

- Repositório da Api responsável pelo desafio da reportei.
- Com o foco em analisar os dados deq repositorios do github:
  ```
  - PHP, 
  - Swoole, 
  - Laravel,
  - Laravel Octane
  - Redis,
  - SQL
  ```
- Link para o repositório contendo o front do projeto: -

# Hospedagem

- Atualmente o sistema está hospedado em uma vps2 da hostinger sobre o domínio http://www.backend.ejsocial.com/ ou ip: http://85.31.62.148:8888/ para o nginx | (https://85.31.62.148:7070 | https://85.31.62.148:4080) para as instancias da api
- Configurações da vps:
    ```
  - Núcleos de CPU: 2
  - Memória: 8 GB
  - Largura de Banda: 8 TB
  - Espaço em disco: 100 GB
    ```
  
# Repositório da Imagem Docker: 

- https://hub.docker.com/repository/docker/jo1opedro/php83-missing-pets-api-2024/general 

# Conteúdo da Imagem Docker

- <b>PHP</b>, diversas extensões e Libs do PHP, incluindo php-redis, mysql, swoole, memcached.

- <b>Composer</b>, afinal de contas é preciso baixar as dependências mais atuais toda vez que fomos crontruir uma imagem Docker.

# Passo a Passo para execução

## Certifique-se de estar com o Docker em execução.

```sh
docker ps
```

## Certifique-se de ter o Docker Compose instalado.

```sh
docker compose version
```

A listagem de pastas do projeto deve ficar:

```
    app/
    docker/
    .gitignore
    docker-compose.yml
    readme.md
```

## Certifique-se que sua aplicação Laravel ficou em `./app` e que existe o seguinte caminho: `/app/public/index.php`

## Certifique-se que sua aplicação Laravel possuí um .env e que este .env está com a `APP_KEY=` definida com valor válido.

## Contruir a imagem Docker, execute:

```sh
docker compose up --build -d
```

## Caso não queira utilizar o cache da imagem presente no seu ambiente Docker, então execute:

```sh
docker compose up --build -d --no-cache
```

## Para derrubar a aplicação, execute:

```sh
docker compose down
```

## Para entrar dentro do Container da Aplicação, execute:

```sh
docker exec -it (nomeDoServiço) bash
```

# Solução de Problemas

## Problema de permissão

- Quando for criado novos arquivos, ou quando for a primeira inicialização do container com a aplicação, pode então haver um erro de permissão de acesso as pastas, neste caso, entre dentro do container da aplicação e execeute.

```sh
cd /var/www && \
chown -R www-data:www-data * && \
chmod -R o+w app
```
