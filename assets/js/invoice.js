var modal_payment;
//////invoice
function calculate_total_line(index)
{
    var $container = $(index).closest('tr');
    var quantity = $container.find('.quantity').val();
    var price = $container.find('.unit_price').val();
    var subtotal = parseInt(quantity) * parseFloat(price);
    if (isNaN(subtotal)) {
        $container.find('.total_line').val('');
    } else {
        $container.find('.total_line').val(subtotal.toFixed(2));
        calculate_subtotal();
        calculate_tax();
        calculate_total();
    }
}

function calculate_subtotal() {
    var sum = 0;
    $(".total_line").each(function () {
        sum += parseFloat($(this).val()) || 0;
    });
    $("#subtotal").html(sum.toFixed(2));

}


function calculate_tax() {
    var tax = parseFloat($("#tax").html());
    var subtotal = parseFloat($("#subtotal").html());
    var tax_total = (tax * subtotal);
    $("#tax_total").html(tax_total.toFixed(2));


}

function calculate_total() {
    var tax_total = parseFloat($("#tax_total").html());
    var subtotal = parseFloat($("#subtotal").html());
    var total = (tax_total + subtotal);
    $("#total").html(total.toFixed(2));
}


function count_lines() {
    if ($('.remove_line').length <= 1)
    {
        $('.remove_line').hide();
    } else {
        $('.remove_line').show();
    }
}




//////invoice end


$(document).ready(function () {


    modal_payment = $('#modal_payment').on('show.bs.modal', function (e) {


        if (e.relatedTarget.parentNode.getAttribute('class') === "disabled")
        {
            return false;
        }

        var invoice_id = e.relatedTarget.getAttribute('data-invoice-id');
        var action = e.relatedTarget.getAttribute('data-action');
        url = '/invoice/payment';
        if (action === 'void_invoice')
        {
            url = '/invoice/void_invoice';
        }
        var formData =
                {
                    'invoice_id': invoice_id
                };
        var modal = $(this);
        $.ajax({
            cache: false,
            type: 'GET',
            url: url,
            //url: '/client/create_contact_get',
            data: formData,
            success: function (data)
            {
                modal.find('.modal-content').html(data);
                $("#datetimepicker_date_paid").datetimepicker({
                    viewMode: 'days',
                    format: 'DD/MM/YYYY'
                });
            }
        });
    });






    $(document).on("click", "#modal_payment button[data-action='pay']", function (event) {

        var formData = {
            'invoice_id': $("#invoice_id").val(),
            'amount_paid': $("#amount_paid").val(),
            'date_paid': $("#date_paid").val()
        };

        $.ajax({
            cache: false,
            type: 'POST',
            url: '/invoice/make_payment',
            data: formData,
            beforeSend: function () {
                // clearValidationErrors();
            },
            success: function (data)
            {
                modal_payment.modal("hide");
            },
            error: function (jqXhr)
            {
                clearValidationErrors();
                if (jqXhr.status === 400)
                { //Validation error or other reason for Bad Request 400
                    displayValidationErrors(jqXhr.responseText);
                }
            }
        });
    });


    $(document).on("click", "#modal_payment button[data-action='void_invoice']", function (event) {

        var formData = {
            'invoice_id': $("#invoice_id").val(),
            'note': $("#note").val()
        };

        $.ajax({
            cache: false,
            type: 'POST',
            url: '/invoice/make_void',
            data: formData,
            beforeSend: function () {
                // clearValidationErrors();
            },
            success: function (data)
            {
                modal_payment.modal("hide");
            },
            error: function (jqXhr)
            {
                clearValidationErrors();
                if (jqXhr.status === 400)
                { //Validation error or other reason for Bad Request 400
                    displayValidationErrors(jqXhr.responseText);
                }
            }
        });
    });














    $('table#invoice_items').on('blur', '.quantity , .unit_price', function () {
        calculate_total_line(this);
    });



    $('#datetimepicker_date_issue').datetimepicker({
        viewMode: 'days',
        format: 'DD/MM/YYYY'
    });



    $('#datetimepicker_due_date').datetimepicker({
        viewMode: 'days',
        format: 'DD/MM/YYYY',
        useCurrent: false //Important! See issue #1075
    });

    //disables due date being before issue date.
    $("#datetimepicker_date_issue").on("dp.change", function (e) {
        $('#datetimepicker_due_date').data("DateTimePicker").minDate(e.date);
    });

    //disables issue date being after due date.
    $("#datetimepicker_due_date").on("dp.change", function (e) {
        $('#datetimepicker_date_issue').data("DateTimePicker").maxDate(e.date);
    });

    $('#invoice_items').on('click', '.remove_line', function () {

        $('#invoice_entries_del').val();
        var $container = $(this).closest('tr');
        var quantity = $container.find('.invoice_detail_id').val();
        var input = $('#invoice_entries_del');

        $("#myInput");
        input.val(input.val() + quantity + ",");



        $(this).closest('tr').remove();
        count_lines();
        calculate_subtotal();
    });




    $('#addRow').on('click', function () {
        $('#invoice_items tr:last').clone()
                .find(".total").text("").end()
                .find("p").text("").end()
                .find("input:text").val("").end()
                .appendTo('#invoice_items');
        count_lines();
    });

    count_lines();
    //invoice end

});