<script type="text/javascript">
    @if(!$is_view_only)
        var i = {{ old('installments', $model) ? count(old('installments', $model)) : 0 }};
        $("#add").click(function () {
            $("#dynamicTable").append(
                '<tr>' +
                '<td><button type="button" class="btn btn-danger remove-tr">حذف</button></td>' +
                '<td><input type="text" name="installments[' + i + '][invoice_number]" class="form-control" /></td>' +
                '<td><input required type="number" min=0 step="0.01" name="installments[' + i + '][amount]" class="form-control" /></td>' +
                '<td><input required type="date" name="installments[' + i + '][invoice_date]" class="form-control" /></td>' +
                '</tr>'
            );
            ++i;
        });

        $(document).on('click', '.remove-tr', function () {
            $(this).parents('tr').remove();
        });
    @endif
</script>
