<style>
    #usdExchangeRateModal {
        z-index: 1060 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    #usdExchangeRateJobStatus {
        margin-top: 15px;
        margin-bottom: 0;
    }

    #usdExchangeRateJobProgress {
        display: block;
        margin-top: 6px;
    }
</style>

<div class="modal fade" id="usdExchangeRateModal" tabindex="-1" role="dialog" aria-labelledby="usdExchangeRateModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="usdExchangeRateModalLabel">
                    Cotización del dólar
                </h4>
            </div>

            <div class="modal-body">

                <div id="usdExchangeRateError" class="alert alert-danger" style="display: none;"></div>

                <div id="usdExchangeRateJobStatus" class="alert alert-info" style="display: none;">
                    <i id="usdExchangeRateJobIcon" class="fa fa-spinner fa-spin"></i>

                    <span id="usdExchangeRateJobMessage"></span>

                    <small id="usdExchangeRateJobProgress" style="display: none;"></small>
                </div>

                <div class="form-group">
                    <label for="usd_exchange_rate">
                        USD 1 equivale a:
                    </label>

                    <div class="input-group">
                        <span class="input-group-addon">
                            ARS $
                        </span>

                        <input type="number" name="usd_exchange_rate" id="usd_exchange_rate" class="form-control"
                            min="0.01" step="0.01"
                            value="{{ data_get(Session::get('business'), 'usd_exchange_rate') }}"
                            placeholder="Ejemplo: 1350.00" autocomplete="off">
                    </div>

                    <p class="help-block">
                        Valor en pesos argentinos correspondiente
                        a USD 1.
                    </p>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancelar
                </button>

                <button type="button" id="saveUsdExchangeRate" class="btn btn-primary">
                    <i class="fa fa-save"></i>
                    Guardar cotización
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        if (typeof jQuery === 'undefined') {
            console.error(
                'jQuery is required to manage the USD exchange rate.'
            );

            return;
        }

        var $ = jQuery;

        var statusPollTimer = null;
        var statusPollAttempts = 0;
        var maximumStatusPollAttempts = 360;
        var isJobRunning = false;

        var $modal = $('#usdExchangeRateModal');
        var $button = $('#saveUsdExchangeRate');
        var $input = $('#usd_exchange_rate');
        var $errorContainer = $('#usdExchangeRateError');
        var $jobStatus = $('#usdExchangeRateJobStatus');
        var $jobIcon = $('#usdExchangeRateJobIcon');
        var $jobMessage = $('#usdExchangeRateJobMessage');
        var $jobProgress = $('#usdExchangeRateJobProgress');
        var $dismissButtons = $modal.find(
            '[data-dismiss="modal"]'
        );

        var originalButtonHtml = $button.html();

        function showJobStatus(
            type,
            message,
            progressMessage
        ) {
            var alertClass = 'alert-info';
            var iconClass = 'fa-spinner fa-spin';

            if (type === 'processing') {
                alertClass = 'alert-info';
                iconClass = 'fa-spinner fa-spin';
            }

            if (type === 'completed') {
                alertClass = 'alert-success';
                iconClass = 'fa-check-circle';
            }

            if (type === 'skipped') {
                alertClass = 'alert-warning';
                iconClass = 'fa-exclamation-triangle';
            }

            if (type === 'failed') {
                alertClass = 'alert-danger';
                iconClass = 'fa-times-circle';
            }

            $jobStatus
                .removeClass(
                    'alert-info ' +
                    'alert-success ' +
                    'alert-warning ' +
                    'alert-danger'
                )
                .addClass(alertClass)
                .show();

            $jobIcon
                .attr(
                    'class',
                    'fa ' + iconClass
                );

            $jobMessage.text(message);

            if (progressMessage) {
                $jobProgress
                    .text(progressMessage)
                    .show();
            } else {
                $jobProgress
                    .hide()
                    .text('');
            }
        }

        function stopStatusPolling() {
            if (statusPollTimer !== null) {
                clearTimeout(statusPollTimer);
                statusPollTimer = null;
            }
        }

        function releaseModalControls() {
            isJobRunning = false;

            $button
                .prop('disabled', false)
                .html(originalButtonHtml);

            $dismissButtons.prop('disabled', false);
        }

        function finishJobStatus(
            type,
            message,
            progressMessage
        ) {
            stopStatusPolling();
            releaseModalControls();

            showJobStatus(
                type,
                message,
                progressMessage
            );
        }

        function scheduleStatusCheck(statusUrl) {
            statusPollTimer = setTimeout(
                function() {
                    checkUsdPriceUpdateStatus(
                        statusUrl
                    );
                },
                2000
            );
        }

        function checkUsdPriceUpdateStatus(statusUrl) {
            statusPollAttempts++;

            if (
                statusPollAttempts >
                maximumStatusPollAttempts
            ) {
                finishJobStatus(
                    'failed',
                    'La actualización está tardando demasiado.',
                    'Revisa que el worker de la cola esté funcionando.'
                );

                return;
            }

            $.ajax({
                url: statusUrl,
                type: 'GET',
                cache: false,

                success: function(response) {
                    var status = response.status;
                    var updatedCount = parseInt(
                        response.updated_count || 0,
                        10
                    );

                    var totalCount = parseInt(
                        response.total_count || 0,
                        10
                    );

                    var progressMessage = '';

                    if (totalCount > 0) {
                        progressMessage =
                            updatedCount +
                            ' de ' +
                            totalCount +
                            ' precios procesados.';
                    } else if (updatedCount > 0) {
                        progressMessage =
                            updatedCount +
                            ' precios procesados.';
                    }

                    if (status === 'queued') {
                        showJobStatus(
                            'processing',
                            response.message ||
                            'rocesando la actualización de precios.',
                            ''
                        );

                        scheduleStatusCheck(statusUrl);
                        return;
                    }

                    if (status === 'processing') {
                        showJobStatus(
                            'processing',
                            response.message ||
                            'Actualizando precios.',
                            progressMessage
                        );

                        scheduleStatusCheck(statusUrl);
                        return;
                    }

                    if (status === 'completed') {
                        finishJobStatus(
                            'completed',
                            response.message ||
                            'La actualización finalizó correctamente.',
                            updatedCount +
                            ' precios actualizados.'
                        );

                        if (
                            typeof toastr !==
                            'undefined'
                        ) {
                            toastr.success(
                                'Actualización de precios finalizada.'
                            );
                        }

                        return;
                    }

                    if (status === 'skipped') {
                        finishJobStatus(
                            'skipped',
                            response.message ||
                            'La actualización fue omitida.',
                            progressMessage
                        );

                        return;
                    }

                    if (status === 'failed') {
                        var errorMessage =
                            response.error || '';

                        finishJobStatus(
                            'failed',
                            response.message ||
                            'Falló la actualización de precios.',
                            errorMessage
                        );

                        return;
                    }

                    scheduleStatusCheck(statusUrl);
                },

                error: function(xhr) {
                    var message =
                        'No se pudo consultar el estado del Job.';

                    if (
                        xhr.responseJSON &&
                        xhr.responseJSON.message
                    ) {
                        message =
                            xhr.responseJSON.message;
                    }

                    finishJobStatus(
                        'failed',
                        message,
                        'Revisa la configuración de cache y el worker.'
                    );
                }
            });
        }

        /**
         * Limpia el modal cuando se abre.
         */
        $modal.on(
            'shown.bs.modal',
            function() {
                $errorContainer
                    .hide()
                    .text('');

                if (!isJobRunning) {
                    $jobStatus
                        .hide()
                        .removeClass(
                            'alert-success ' +
                            'alert-warning ' +
                            'alert-danger'
                        )
                        .addClass('alert-info');

                    $jobProgress
                        .hide()
                        .text('');

                    $input
                        .focus()
                        .select();
                }
            }
        );

        /**
         * Impide cerrar el modal mientras el Job trabaja.
         */
        $modal.on(
            'hide.bs.modal',
            function(event) {
                if (isJobRunning) {
                    event.preventDefault();
                    return false;
                }
            }
        );

        /**
         * Guarda la cotización.
         */
        $(document).on(
            'click',
            '#saveUsdExchangeRate',
            function() {
                if (isJobRunning) {
                    return;
                }

                var exchangeRate = parseFloat(
                    $input.val()
                );

                $errorContainer
                    .hide()
                    .text('');

                $jobStatus.hide();

                if (
                    isNaN(exchangeRate) ||
                    exchangeRate <= 0
                ) {
                    $errorContainer
                        .text(
                            'La cotización debe ser mayor que cero.'
                        )
                        .show();

                    $input.focus();
                    return;
                }

                isJobRunning = true;
                statusPollAttempts = 0;

                stopStatusPolling();

                $button
                    .prop('disabled', true)
                    .html(
                        '<i class="fa fa-spinner fa-spin"></i> ' +
                        'Guardando...'
                    );

                $dismissButtons.prop(
                    'disabled',
                    true
                );

                showJobStatus(
                    'processing',
                    'Guardando la cotización.',
                    ''
                );

                $.ajax({
                    url: "{{ route('business.update-usd-exchange-rate') }}",
                    type: 'POST',

                    data: {
                        _token: "{{ csrf_token() }}",

                        usd_exchange_rate: exchangeRate
                    },

                    success: function(response) {
                        if (!response.success) {
                            finishJobStatus(
                                'failed',
                                response.message ||
                                'No se pudo guardar la cotización.',
                                ''
                            );

                            return;
                        }

                        var savedRate = parseFloat(
                            response
                            .usd_exchange_rate
                        );

                        $input.val(
                            savedRate.toFixed(2)
                        );

                        $('#currentUsdExchangeRate')
                            .text(
                                savedRate.toLocaleString(
                                    'es-AR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    }
                                )
                            );

                        if (!response.status_url) {
                            finishJobStatus(
                                'failed',
                                'La cotización se guardó, pero no se recibió la URL de seguimiento.',
                                ''
                            );

                            return;
                        }

                        $button.html(
                            '<i class="fa fa-spinner fa-spin"></i> ' +
                            'Actualizando precios...'
                        );

                        showJobStatus(
                            'processing',
                            'Cotización guardada. Esperando que comience el Job.',
                            ''
                        );

                        checkUsdPriceUpdateStatus(
                            response.status_url
                        );
                    },

                    error: function(xhr) {
                        var message =
                            'No se pudo actualizar la cotización del dólar.';

                        if (
                            xhr.status === 422 &&
                            xhr.responseJSON &&
                            xhr.responseJSON.errors &&
                            xhr.responseJSON.errors
                            .usd_exchange_rate
                        ) {
                            message =
                                xhr.responseJSON
                                .errors
                                .usd_exchange_rate[0];
                        } else if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {
                            message =
                                xhr.responseJSON.message;
                        }

                        stopStatusPolling();
                        releaseModalControls();

                        $jobStatus.hide();

                        $errorContainer
                            .text(message)
                            .show();
                    }
                });
            }
        );

        /**
         * Permite guardar presionando Enter.
         */
        $input.on(
            'keydown',
            function(event) {
                if (
                    event.key === 'Enter' ||
                    event.which === 13
                ) {
                    event.preventDefault();

                    $button.trigger('click');
                }
            }
        );
    });
</script>
