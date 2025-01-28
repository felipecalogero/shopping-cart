$(document).ready(function () {
    const installmentOptionsScript = document.getElementById("installment-options-data");
    const installmentOptions = installmentOptionsScript ? JSON.parse(installmentOptionsScript.textContent) : {};
    const formattedFinalValue = "{{ $formattedFinalValue }}";
    const formattedTotal = "{{ $formattedTotal }}";

    $('#paymentMethod, #number-months').change(function () {
        const paymentMethod = $('#paymentMethod').val();
        const installments = $('#number-months').val();

        $('#payment-resume-order').css('display', 'block');

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
});
