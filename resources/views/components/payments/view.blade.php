<div class="row col-12">
    <label>طريقة الدفع:</label>

    <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" required id="cash" class="custom-control-input" name="payment_type"
               @checked(old('payment_type') == 'cash' || !is_null($model?->cash))
               @disabled($is_view_only)
               onclick="handleCashOption()" value="cash">
        <label class="custom-control-label" for="cash">كاش</label>
    </div>

    <div class="custom-control custom-radio custom-control-inline mb-3">
        <input type="radio" id="installments" class="custom-control-input" name="payment_type"
               @checked(old('payment_type') == 'installments' || !is_null($model?->installments))
               @disabled($is_view_only)
               onclick="handleInstallmentsOption()" value="installments">
        <label class="custom-control-label" for="installments">دفعات</label>
    </div>
</div>

@include('components.payments.cash.view')

@include('components.payments.installments.view')
