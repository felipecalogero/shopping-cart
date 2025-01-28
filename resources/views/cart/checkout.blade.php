<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Realizado</title>
    <link href="{{ asset('css/cart/final.css') }}" rel="stylesheet" type="text/css">
    <style>

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pedido Realizado com Sucesso!</h1>
        </div>
        <div class="order-number">
            <p>O número do seu pedido é:</p>
            <h1>{{ $orderId }}</h1>
        </div>
        <a href="{{ route('products') }}">
            <button class="final-btn">Voltar à loja</button>
        </a>
    </div>
</body>

</html>
