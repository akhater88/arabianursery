@include('components.payments.installments.script')

<script>
    function handleCashOption() {
        document.getElementById('cash-dev').style.display = 'flex'
        document.getElementById('installments-dev').style.display = 'none'
    }

    function handleInstallmentsOption() {
        document.getElementById('installments-dev').style.display = 'flex'
        document.getElementById('cash-dev').style.display = 'none'
    }

    if ({{old('payment_type') == 'installments' || !is_null($model?->installments) ? 'true' : 'false'}}) {
        handleInstallmentsOption()
    }

    if ({{old('payment_type') == 'cash' || !is_null($model?->cash) ? 'true' : 'false'}}) {
        handleCashOption()
    }
</script>
