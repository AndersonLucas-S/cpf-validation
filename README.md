# CPF Validation

Este projeto foi criado para validar e armazenar grande volume de CPFs a partir de um arquivo CSV.

## Requisitos

- PHP >= 7.4
- Composer
- MySQL
- Redis
- Laravel 8.x

## Instalação

1. Clone o repositório:

    ```sh
    git clone https://github.com/seu-usuario/cpf-validation.git
    cd cpf-validation-api
    ```

2. Instale as dependências:

    ```sh
    composer install
    ```

3. Configure o arquivo `.env`:

    ```sh
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure o banco de dados e o Redis no arquivo `.env`.

5. Execute as migrações:

    ```sh
    php artisan migrate
    ```

6. Inicie o servidor de desenvolvimento:

    ```sh
    php artisan serve
    ```

7. Inicie o Redis e processe a fila:

    ```sh
    php artisan queue:work --memory=1024
    ```

## Uso

Certifique-se de que o arquivo CSV contendo os CPFs esteja no diretório `storage/app/public`. A API irá processar o arquivo, validar os CPFs e armazená-los no banco de dados.
