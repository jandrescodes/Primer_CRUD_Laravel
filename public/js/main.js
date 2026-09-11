$(document).ready(function () {
    const dtLanguage = {
        sProcessing: 'Procesando...',
        sLengthMenu: 'Mostrar _MENU_ registros',
        sZeroRecords: 'No se encontraron resultados',
        sEmptyTable: 'Ningún dato disponible en esta tabla',
        sInfo: 'Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros',
        sInfoEmpty: 'Mostrando registros del 0 al 0 de un total de 0 registros',
        sInfoFiltered: '(filtrado de un total de _MAX_ registros)',
        sInfoPostFix: '',
        sSearch: 'Buscar:',
        sUrl: '',
        sInfoThousands: ',',
        sLoadingRecords: 'Cargando...',
        oPaginate: {
            sFirst: 'Primero',
            sLast: 'Último',
            sNext: 'Siguiente',
            sPrevious: 'Anterior'
        },
        oAria: {
            sSortAscending: ': Activar para ordenar la columna de manera ascendente',
            sSortDescending: ': Activar para ordenar la columna de manera descendente'
        },
        buttons: {
            copyTitle: 'Copiado al portapapeles',
            copySuccess: {
                1: '1 fila copiada',
                _: '%d filas copiadas'
            },
            colvis: 'Visibilidad de columnas',
            colvisRestore: 'Restaurar visibilidad'
        }
    };

    // DataTables genera aria-role (inválido) en la paginación; se limpia en cada sort/paginación.
    function limpiarAriaPaginacion(api) {
        $(api.table().container()).find('[aria-role]').removeAttr('aria-role');
    }

    // Tabla principal de personas
    const tablaPersonas = $('#tabla-personas');
    if (tablaPersonas.length) {
        try {
            tablaPersonas.css('visibility', 'hidden');
            tablaPersonas.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 5,
            lengthMenu: [
                [3, 5, 10, 25, 50],
                [3, 5, 10, 25, 50]
            ],
            language: dtLanguage,
            buttons: [
                {
                    extend: 'collection',
                    text: '<i class="fa-solid fa-file-export me-1"></i> Reportes',
                    orientation: 'landscape',
                    buttons: [
                        {
                            text: '<i class="fa-solid fa-copy me-1"></i> Copiar',
                            extend: 'copy',
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="fa-solid fa-file-pdf me-1"></i> PDF',
                            title: 'Reporte de Personas',
                            filename: 'reporte_personas_' + new Date().toISOString().slice(0, 10),
                            pageSize: 'LEGAL',
                            orientation: 'portrait',
                            exportOptions: { columns: [0, 1, 2, 3, 4] },
                            customize: function (doc) {
                                doc.defaultStyle.fontSize = 10;
                                doc.styles.tableHeader.fontSize = 11;
                                doc.styles.tableHeader.fillColor = '#0d6efd';
                                doc.styles.tableHeader.color = '#ffffff';

                                doc.content.splice(0, 1, {
                                    text: 'REPORTE DE PERSONAS',
                                    style: {
                                        fontSize: 16,
                                        alignment: 'center',
                                        bold: true,
                                        margin: [0, 10, 0, 10]
                                    }
                                });

                                doc.content.splice(1, 0, {
                                    text: 'Listado de personas registradas en el sistema',
                                    style: {
                                        fontSize: 11,
                                        alignment: 'center',
                                        italic: true,
                                        margin: [0, 0, 0, 10]
                                    }
                                });

                                doc.content.splice(2, 0, {
                                    text: 'Generado el: ' + new Date().toLocaleString('es-BO'),
                                    style: {
                                        fontSize: 9,
                                        alignment: 'right',
                                        margin: [0, 0, 0, 10]
                                    }
                                });

                                doc.footer = function (currentPage, pageCount) {
                                    return {
                                        columns: [
                                            { text: 'Primer CRUD Laravel', alignment: 'left', fontSize: 8 },
                                            { text: 'Página ' + currentPage + ' de ' + pageCount, alignment: 'center', fontSize: 8 },
                                            { text: new Date().getFullYear(), alignment: 'right', fontSize: 8 }
                                        ],
                                        margin: [40, 0]
                                    };
                                };
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="fa-solid fa-file-excel me-1"></i> Excel',
                            title: 'Reporte de Personas',
                            messageTop: 'Listado de personas registradas en el sistema',
                            messageBottom: 'Documento generado el ' + new Date().toLocaleDateString('es-BO'),
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        },
                        {
                            extend: 'csv',
                            text: '<i class="fa-solid fa-file-csv me-1"></i> CSV',
                            exportOptions: { columns: [0, 1, 2, 3, 4] }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fa-solid fa-print me-1"></i> Imprimir',
                            title: 'Reporte de Personas',
                            messageTop: 'Reporte generado el ' + new Date().toLocaleDateString('es-BO'),
                            exportOptions: { columns: [0, 1, 2, 3, 4] },
                            customize: function (win) {
                                $(win.document.body).find('table')
                                    .addClass('table-striped')
                                    .css('font-size', '12px');
                            }
                        }
                    ]
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns me-1"></i> Columnas'
                }
            ],
            initComplete: function () {
                $(this.api().table().node()).css('visibility', 'visible');
            },
            drawCallback: function () {
                limpiarAriaPaginacion(this.api());
            }
        }).buttons().container().addClass('mt-2').appendTo('#tabla-personas_wrapper .col-md-6:eq(0)');
        } catch (err) {
            tablaPersonas.css('visibility', 'visible');
        }
    }

    // Tabla papelera
    const tablaPapelera = $('#tabla-papelera');
    if (tablaPapelera.length) {
        try {
            tablaPapelera.css('visibility', 'hidden');
            tablaPapelera.DataTable({
            responsive: true,
            autoWidth: false,
            pageLength: 5,
            lengthMenu: [
                [3, 5, 10, 25, 50],
                [3, 5, 10, 25, 50]
            ],
            language: dtLanguage,
            initComplete: function () {
                $(this.api().table().node()).css('visibility', 'visible');
            },
            drawCallback: function () {
                limpiarAriaPaginacion(this.api());
            }
        });
        } catch (err) {
            tablaPapelera.css('visibility', 'visible');
        }
    }

    // Envía un POST con _token y _method (spoofing de verbo) sin necesitar
    // un <form> en el HTML, para no romper el layout del .btn-group.
    function submitAction(url, method) {
        const token = $('meta[name="csrf-token"]').attr('content');
        const form = $('<form>', { action: url, method: 'POST' });
        form.append($('<input>', { type: 'hidden', name: '_token', value: token }));
        form.append($('<input>', { type: 'hidden', name: '_method', value: method }));
        form.appendTo('body').trigger('submit');
    }

    // Eliminación suave con SweetAlert2
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const nombre = $(this).data('nombre') || 'este registro';

        Swal.fire({
            title: '¿Estás seguro?',
            text: `Vas a mover a la papelera a: ${nombre}.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) submitAction(url, 'DELETE');
        });
    });

    // Restaurar desde papelera
    $(document).on('click', '.btn-restore', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const nombre = $(this).data('nombre') || 'este registro';

        Swal.fire({
            title: '¿Restaurar registro?',
            text: `Se restaurará a: ${nombre}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) submitAction(url, 'PUT');
        });
    });

    // Eliminación permanente
    $(document).on('click', '.btn-force-delete', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const nombre = $(this).data('nombre') || 'este registro';

        Swal.fire({
            title: '¡Eliminación permanente!',
            text: `Se eliminará definitivamente a: ${nombre}. Esta acción no se puede deshacer.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar para siempre',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) submitAction(url, 'DELETE');
        });
    });
});
