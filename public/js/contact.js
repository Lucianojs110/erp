$(document).ready(function () {

    $('[data-toggle="tooltip"]').tooltip();

    $("#tax_number")
        .off('change keyup paste')
        .on('change keyup paste', function () {

            const taxNumber = $(this).val().replace(/\D/g, '');

            if (taxNumber.length !== 11) {
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '/contacts/consultarcuit',
                dataType: "json",
                data: {
                    tax_number: taxNumber
                },
                success: function (data) {
                    console.log(data);

                    const persona = data.persona;

                    if (!persona) {
                        toastr.warning('CUIT no válido');

                        $("#name").val('');
                        $("#supplier_business_name").val('');
                        $("#city").val('');
                        $("#state").val('');
                        $("#country").val('');
                        $("#landmark").val('');

                        return;
                    }

                    toastr.success('CUIT válido');

                    const esPersonaJuridica =
                        persona.tipoPersona === 'JURIDICA';

                    const nombreCompleto = esPersonaJuridica
                        ? persona.razonSocial
                        : [persona.apellido, persona.nombre]
                            .filter(Boolean)
                            .join(' ');

                    const domicilio = Array.isArray(persona.domicilio)
                        ? persona.domicilio[0] || {}
                        : persona.domicilio || {};

                    const direccion = [
                        domicilio.calle,
                        domicilio.numero
                    ].filter(Boolean).join(' ');

                    $("#name").val(nombreCompleto || '');

                    $("#supplier_business_name").val(
                        esPersonaJuridica
                            ? persona.razonSocial || ''
                            : ''
                    );

                    $("#city").val(domicilio.localidad || '');
                    $("#state").val(domicilio.descripcionProvincia || '');
                    $("#country").val('ARGENTINA');
                    $("#landmark").val(direccion);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    toastr.warning('Ocurrió un problema al consultar el CUIT');
                }
            });

            return false;
        });

});