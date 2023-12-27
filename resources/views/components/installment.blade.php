<tr>
    <td>
        <button @disabled(!$canBeDeleted) type="button" class="btn btn-danger remove-tr">حذف</button>
    </td>
    <td>
        <input type="text" name="installments[{{$index}}][invoice_number]" class="form-control" value="{{$installment['invoice_number'] ?? ''}}"/>
    </td>
    <td>
        <input type="number" min=0 step="0.01" name="installments[{{$index}}][amount]"
               value="{{$installment['amount'] ?? ''}}"
               class="form-control"/></td>
    <td>
        <input type="date" name="installments[{{$index}}][invoice_date]" value="{{$installment['invoice_date'] ?? ''}}" class="form-control"/>
    </td>
</tr>
