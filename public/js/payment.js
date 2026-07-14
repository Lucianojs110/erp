$(document).ready(function () {
    $(document).on('click', '.add_payment_modal', function (e) {
        e.preventDefault();
        var container = $('.payment_modal');
        $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            success: function (result) {
               if (result.status == 'due') {
                    container.html(result.view).modal('show');
                    __currency_convert_recursively(container);
                    $('#paid_on').datepicker({
                        autoclose: true,
                    });
                    container.find('form#transaction_payment_add_form').validate();
                } else {
                    toastr.error(result.msg);
                }
            },
        });
    });
    $(document).on('submit', '#transaction_payment_add_form', function (e) {
        e.preventDefault();
    
        var form = $(this)[0];
        var formData = new FormData(form);
        var url = $(this).attr('action');
    
        $.ajax({
            method: 'POST',
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (result) {
                if (result.success) {
                    toastr.success(result.msg);
                    $('.payment_modal').modal('hide');
    
                    if (typeof purchase_table !== 'undefined') {
                        purchase_table.ajax.reload();
                    } else if (typeof sell_table !== 'undefined') {
                        sell_table.ajax.reload();
                    }
    
                    if (result.receipt) {
                        payment_print(result.receipt);
                    }
                } else {
                    toastr.error(result.msg);
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Hubo un error al guardar el pago.');
            }
        });
    });
    $(document).on('click', '.edit_payment', function (e) {
        e.preventDefault();
        var container = $('.edit_payment_modal');

        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function (result) {
                container.html(result).modal('show');
                __currency_convert_recursively(container);
                $('#paid_on').datepicker({
                    autoclose: true,
                    toggleActive: false,
                });
                container.find('form#transaction_payment_add_form').validate();
            },
        });
    });

    $(document).on('click', '.view_payment_modal', function (e) {
        e.preventDefault();
        var container = $('.payment_modal');

        $.ajax({
            url: $(this).attr('href'),
            dataType: 'html',
            success: function (result) {
                $(container).html(result).modal('show');
                __currency_convert_recursively(container);
            },
        });
    });
    $(document).on('click', '.delete_payment', function (e) {
        swal({
            title: LANG.sure,
            text: LANG.confirm_delete_payment,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: $(this).data('href'),
                    method: 'delete',
                    dataType: 'json',
                    success: function (result) {
                        if (result.success === true) {
                            $('div.payment_modal').modal('hide');
                            $('div.edit_payment_modal').modal('hide');
                            toastr.success(result.msg);
                            if (typeof purchase_table != 'undefined') {
                                purchase_table.ajax.reload();
                            }
                            if (typeof sell_table != 'undefined') {
                                sell_table.ajax.reload();
                            }
                            if (typeof expense_table != 'undefined') {
                                expense_table.ajax.reload();
                            }
                            if (typeof ob_payment_table != 'undefined') {
                                ob_payment_table.ajax.reload();
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
        });
    });

    //view single payment
    $(document).on('click', '.view_payment', function () {
        var url = $(this).data('href');
        var container = $('.view_modal');
        $.ajax({
            method: 'GET',
            url: url,
            dataType: 'html',
            success: function (result) {
                $(container).html(result).modal('show');
                __currency_convert_recursively(container);
            },
        });
    });
    
});
function payment_print(receipt) {
    //If printer type then connect with websocket
    if (receipt.print_type == 'printer') {
        var content = receipt;
        content.type = 'print-receipt';

        //Check if ready or not, then print.
        if (socket != null && socket.readyState == 1) {
            socket.send(JSON.stringify(content));
        } else {
            initializeSocket();
            setTimeout(function () {
                socket.send(JSON.stringify(content));
            }, 700);
        }
    } else if (receipt.html_content != '') {
        //If printer type browser then print content
        $('#receipt_section').html(receipt.html_content);
        __currency_convert_recursively($('#receipt_section'));
        __print_receipt('receipt_section');
    }
}
