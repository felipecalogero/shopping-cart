<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link href="{{ asset('css/products.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <h1>Produtos</h1>

        @if ($products->isEmpty())
            <div class="empty-products">
                <p>Não há produtos disponíveis no momento. Por favor, certifique-se de que o banco de dados foi populado
                    corretamente.</p>
            </div>
        @else
            <div class="products">
                @foreach ($products as $product)
                    <div class="product">
                        <img src="https://placehold.co/200" alt="{{ $product->name }}">
                        <h3>{{ $product->name }}</h3>
                        <p>{{ $product->description }}</p>
                        <div class="price">R${{ number_format($product->price, 2) }}</div>
                        <button class="add-to-cart" data-id="{{ $product->id }}">Adicionar ao carrinho</button>
                    </div>
                @endforeach
            </div>

            <div id="cart-button-container">
                @if (session()->get('cart'))
                    <a href="{{ route('cart.show') }}">
                        <button class="cart">Visualizar carrinho
                            ({{ array_sum(array_column(session()->get('cart'), 'quantity')) }})</button>
                    </a>
                @endif
            </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            $('.add-to-cart').click(function() {
                let productId = $(this).data('id');
                let cartButtonContainer = $('#cart-button-container');

                $.ajax({
                    url: `/adicionar-carrinho/${productId}`,
                    method: 'POST',
                    success: function(response) {
                        if (cartButtonContainer.find('.cart').length === 0) {
                            cartButtonContainer.html(`
                                <a href="{{ route('cart.show') }}">
                                    <button class="cart">Visualizar carrinho (${response.totalQuantity})</button>
                                </a>
                            `);
                        } else {
                            cartButtonContainer.find('.cart').text(
                                `Visualizar carrinho (${response.totalQuantity})`);
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao adicionar no carrinho.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>
