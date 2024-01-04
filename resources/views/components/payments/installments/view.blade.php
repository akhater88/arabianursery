<div id="installments-dev" class="form-row mb-3 table-responsive" style="display: none">
    <table class="table table-bordered" id="dynamicTable">
        <tr>
            @if(!$is_view_only)
                <th>
                    <button type="button" name="add" id="add" class="btn btn-success">+</button>
                </th>
            @endif
            <th style="min-width: 110px">رقم الفاتورة</th>
            <th style="min-width: 110px">قيمة الدفعة</th>
            <th style="min-width: 110px">تاريخ الدفعة</th>
        </tr>
        @if(old('installments', $model))
            @foreach(old('installments', $model) as $installment)
                <x-installment :index="$loop->index" :canBeDeleted="!$loop->first && (!$model?->installments || count($model->installments) < $loop->iteration)" :installment="$installment" :isViewOnly="$is_view_only"></x-installment>
            @endforeach
        @endif
    </table>
</div>
