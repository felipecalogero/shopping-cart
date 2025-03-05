<!-- resources/views/carrinho/index.blade.php -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <link href="{{ asset('css/cart/cart.css') }}" rel="stylesheet" type="text/css">
</head>


<body>
    <header>
        <h1>Carrinho de Compras</h1>
    </header>

    @if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0)
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Itens</th>
                        <th>Preço Un.</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($_SESSION['cart'] as $productId => $product)
                        <tr>
                            <td>{{ $product['name'] }}</td>
                            <td>R$ {{ number_format($product['price'], 2, ',', '.') }}</td>
                            <td>{{ $product['quantity'] }}</td>
                            <td>R$ {{ number_format($product['price'] * $product['quantity'], 2, ',', '.') }}</td>
                            <td><a href="{{ route('cart.remove', $productId) }}" class="remove-btn">Remover</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('cart.checkout') }}" method="POST" class="checkout-form">
                @csrf
                <label for="paymentMethod">Escolha a forma de pagamento:</label>
                <select name="paymentMethod" id="paymentMethod">
                    <option disabled selected value="selecionar">Selecionar</option>
                    @foreach ($paymentMethods as $key => $paymentMethod)
                        <option value="{{ $key }}">
                            {{ $paymentMethod }}
                        </option>
                    @endforeach
                </select>

                <div id="credit-card-fields" class="credit-card-fields" style="display: none;">
                    <div class="input-group">
                        <label for="cardName">Nome do Cartão:</label>
                        <input type="text" name="cardName" id="cardName"
                            placeholder="Digite o nome completo do cartão">
                    </div>

                    <div class="input-group">
                        <label for="cardNumber">Número do Cartão:</label>
                        <input type="text" name="cardNumber" id="cardNumber" placeholder="Digite o número do cartão">
                    </div>

                    <div class="input-group">
                        <label for="cardExpiry">Validade:</label>
                        <input type="text" name="cardExpiry" id="cardExpiry" placeholder="MM/AA">
                    </div>

                    <div class="input-group">
                        <label for="cardCVV">CVV:</label>
                        <input type="text" name="cardCVV" id="cardCVV" placeholder="CVV">
                    </div>
                </div>

                <div class="input-group" id="number-months-fields" style="display: none;">
                    <label for="number-months">Número de parcelas:</label>
                    <select name="number-months" id="number-months">
                        <option disabled selected>Selecione</option>
                        @foreach ($installmentOptions as $key => $option)
                            <option value="{{ $key }}">
                                {{ $option['label'] }} - R${{ $option['installmentValue'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="resume-order">
                    <h3>Resumo da compra:</h3>
                    <p id="payment-resume-order" style="display: none">
                        <b>Forma de Pagamento:</b> <span id="payment-method"></span>
                    </p>
                    <p id="subtotal-resume-order">
                        <b>Subtotal:</b> R$<span id="subtotal">{{ $formattedTotal }}</span>
                    </p>
                    <p id="discount-resume-order" style="display: none">
                        <b>Desconto:</b> R$<span id="discount">{{ $formattedTotalDiscount }}</span>
                    </p>
                    <p id="installments-resume-order" style="display: none">
                        <b>Parcelas:</b> <span id="installments"></span>
                    </p>
                    <p id="resume-order"><b>Total:</b> R$<span id="total-amount">{{ $formattedTotal }}</span></p>
                </div>

                <button type="submit" class="submit-btn">Finalizar Compra</button>
            </form>


        </div>
    @else
        <div class="empty-cart">
            <p>O carrinho está vazio.</p>
            <a href="{{ route('products') }}">
                <button class="empty-btn">Voltar à loja</button>
            </a>
        </div>
    @endif

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const installmentOptions = @json($installmentOptions);
            const formattedFinalValue = "{{ $formattedFinalValue }}";
            const formattedTotal = "{{ $formattedTotal }}";

            $('#paymentMethod, #number-months').change(function() {
                const paymentMethod = $('#paymentMethod').val();
                const installments = $('#number-months').val();

                $('#payment-resume-order').css('display', 'block');

                // required fields
                if (paymentMethod === 'credito_vista' || paymentMethod === 'credito_parcelado') {
                    $('#cardName, #cardNumber, #cardExpiry, #cardCVV').attr('required', true);
                } else {
                    $('#cardName, #cardNumber, #cardExpiry, #cardCVV').removeAttr('required');
                }

                if (paymentMethod === 'pix') {
                    $('#credit-card-fields, #number-months-fields').slideUp();
                    $('#total-amount').text(formattedFinalValue);
                    $('#payment-method').text('PIX');
                    $('#discount-resume-order').css('display', 'block');
                    $('#installment-options').css('display', 'none');
                } else if (paymentMethod === 'credito_vista') {
                    $('#credit-card-fields').slideDown();
                    $('#number-months-fields').slideUp();
                    $('#total-amount').text(formattedFinalValue);
                    $('#payment-method').text('Crédito à vista');
                    $('#discount-resume-order').css('display', 'block');
                    $('#installment-options').css('display', 'none');
                } else if (paymentMethod === 'credito_parcelado') {
                    $('#payment-method').text('Crédito parcelado');
                    $('#total-amount').text(formattedTotal);
                    if (installments) {
                        const selectedInstallment = installmentOptions[installments];
                        if (selectedInstallment) {
                            const installmentText =
                                `${selectedInstallment.label} de R$${selectedInstallment.installmentValue}`;
                            $('#installments-resume-order').css('display', 'block');
                            $('#installments').text(`${installmentText}`);
                            $('#total-amount').text(selectedInstallment.totalWithInterest);
                        }
                    }

                    $('#credit-card-fields').slideDown();
                    $('#number-months-fields').slideDown();
                    $('#discount-resume-order').css('display', 'none');
                    $('#installment-options').css('display', 'block');
                } else {
                    $('#credit-card-fields, #number-months-fields').slideUp();
                    $('#installment-options').css('display', 'none');
                }
            });

            // only letters
            $('#cardName').on('input', function() {
                $(this).val($(this).val().replace(/[^a-zA-Z\s]/g, ''));
            });

            // only numbers with formation
            $('#cardNumber').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 4) {
                    value = value.replace(/(\d{4})(?=\d)/g, '$1 ').slice(0, 19);
                }
                $(this).val(value);
            });

            // only 4 characteres
            $('#cardExpiry').on('input', function() {
                var value = $(this).val().replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{1,2})/, '$1/$2').slice(0, 5);
                }
                $(this).val(value);
            });

            // only 3 numbers
            $('#cardCVV').on('input', function() {
                $(this).val($(this).val().replace(/\D/g, '').slice(0, 3));
            });

        });
    </script>



</body>

</html>
