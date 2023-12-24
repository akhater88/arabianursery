<div class="row col-12">
    <label for="customRadioInline">طريقة الدفع:</label>

    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="cash" class="custom-control-input" name="payment_type"
               {{ old('payment_type') == 'cash' ? 'checked' : '' }}
               onclick="handleCashOption()" value="cash">
        <label class="custom-control-label" for="cash">كاش</label>
    </div>

    <div class="custom-control custom-radio custom-control-inline mb-3">
        <input type="radio" id="installments" class="custom-control-input" name="payment_type"
               {{ old('payment_type') == 'installments' ? 'checked' : '' }}
               onclick="handleInstallmentsOption()" value="installments">
        <label class="custom-control-label" for="installments">دفعات</label>
    </div>
</div>

@include('components.payments.cash.view')

@include('components.payments.installments.view')
