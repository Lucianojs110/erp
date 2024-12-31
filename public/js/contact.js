
$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $("#tax_number").off('change keyup paste').on('change keyup paste', function () {
        if ($(this).val().length == 11) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                url: '/contacts/consultarcuit',
                dataType: "json",
                data: {
                    tax_number: $("#tax_number").val(),
                },
                success: function(data) {
                    console.log(data);
                    if (data.persona == null) {
                        toastr.warning('Cuit no Valido');
                        $("#name").val('');
                    } else {
                        toastr.success('Cuit Valido');
                        $("#name").val(data.persona.apellido + ' ' + data.persona.nombre);
                        $("#city").val(data.persona.domicilio[0].localidad);
                        $("#state").val(data.persona.domicilio[0].descripcionProvincia);
                        $("#country").val('ARGENTINA');
                        $("#landmark").val(data.persona.domicilio[0].calle + ' ' + data.persona.domicilio[0].numero);
                    }
                },
                error: function(){
                    toastr.warning("Ocurrio un problema");
                }
            });
            return false;
        }
    });



});





