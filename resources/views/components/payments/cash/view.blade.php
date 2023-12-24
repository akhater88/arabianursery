<div id="cash-dev" class="form-row mb-3" style="display: none">
    <div class="col-12 col-sm-4">
        <label for="cash-invoice-number">رقم الفاتورة</label>
        <input id='cash-invoice-number' type="number" min=0 step="1" name="cash_invoice_number"
               value="{{ old('cash_invoice_number') }}"
               class="form-control">
    </div>

    <div class="col-12 col-sm-4 mt-2 mt-sm-0">
        <label for="cash-amount">قيمة الفاتورة</label>
        <div class="input-group mb-2">
            <input type="number" min=0 step="0.01" name="cash_amount" class="form-control"
                   value="{{ old('cash_amount') }}"
                   id="cash-amount">
            <div class="input-group-prepend">
                <div class="input-group-text">دينار</div>
            </div>
        </div>
    </div>
</div>
