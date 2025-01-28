# Shopping Cart

## Laravel

Este é um sistema de mini e-commerce desenvolvido com **Laravel**, que inclui funcionalidades como **carrinho de compras**, **aplicação de descontos** e **finalização de compras**.

### Funcionalidades

- **Carrinho de Compras**: Adicionar, remover e visualizar produtos no carrinho.
- **Descontos**: Aplicação de descontos baseados em métodos de pagamento (ex: Pix ou Crédito à Vista).
- **Parcelamento**: Cálculo de parcelas com juros para pagamentos parcelados.
- **Checkout**: Finalização da compra.

## Requisitos

- **Docker**: Para executar os contêineres.
- **Docker Compose**: Para facilitar a orquestração dos contêineres.

## Instalação

Siga os passos abaixo para configurar e rodar o projeto em sua máquina local:

### 1. Clonar o repositório

```bash
git clone https://github.com/felipecalogero/shopping-cart.git
```

### 2. Navegar até o diretório do projeto

```bash
cd shopping-cart
```

### 3. Construir e iniciar os contêineres

```bash
docker-compose up --build
```

### 4. Gerar o banco de dados (dentro do container)
```bash
php artisan migrate
```

### 5. Popular o banco de dados com dados de exemplo
Você pode popular o banco de dados com dados de exemplo utilizando o comando de seeder:
```bash
docker-compose exec app php artisan db:seed
```

### 6. Acessar a aplicação no navegador
http://localhost:8001/

