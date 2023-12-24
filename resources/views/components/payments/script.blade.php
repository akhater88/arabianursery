@include('components.payments.installments.script')

<script>
    function handleCashOption() {
        document.getElementById('cash-dev').style.display = 'flex'
        document.getElementById('installments-dev').style.display = 'none'
        document.getElementById('cash-invoice-number').required = true
        document.getElementById('cash-amount').required = true
    }

    function handleInstallmentsOption() {
        document.getElementById('installments-dev').style.display = 'flex'
        document.getElementById('cash-dev').style.display = 'none'
        document.getElementById('cash-invoice-number').required = false
        document.getElementById('cash-amount').required = false
    }

    if ({{old('payment_type') == 'installments' ? 'true' : 'false'}}) {
        handleInstallmentsOption()
    }

    if ({{old('payment_type') == 'cash' ? 'true' : 'false'}}) {
        handleCashOption()
    }
</script>
