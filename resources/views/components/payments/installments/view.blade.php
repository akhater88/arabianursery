<div id="installments-dev" class="form-row mb-3 table-responsive" style="display: none">
    <table class="table table-bordered" id="dynamicTable">
        <tr>
            <th>
                <button type="button" name="add" id="add" class="btn btn-success">+</button>
            </th>
            <th style="min-width: 110px">رقم الفاتورة</th>
            <th style="min-width: 110px">قيمة الدفعة</th>
            <th style="min-width: 110px">تاريخ الدفعة</th>
        </tr>
        @if(old('installments'))
            @foreach(old('installments') as $installment)
                <x-installment :index="$loop->index" :canBeDeleted="!$loop->first" :installment="$installment"></x-installment>
            @endforeach
        @else
            <x-installment :index="0" :canBeDeleted="false"></x-installment>
        @endif
    </table>
</div>
