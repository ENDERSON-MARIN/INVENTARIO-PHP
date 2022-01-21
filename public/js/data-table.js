(function($) {
  'use strict';
  $(function() {
    $('#order-listing').DataTable({

      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        "info": "_TOTAL_ registros",
        "search": "Buscar",
        "paginate": {
            "next": "Siguiente",
            "previous": "Anterior",
        },
        "lengthMenu": 'Mostrar <select class="form-control">' +
            '<option value="10">10</option>' +
            '<option value="30">30</option>' +
            '<option value="50">50</option>' +
            '<option value="100">100</option>' +
            '<option value="-1">Todo</option>' +
            '</select> registros',
        "loadinRecords": "Cargando...",
        "processing": "Procesando...",
        "emptyTable": "No hay datos",
        "zeroRecords": "No hay coincidencias",
        "infoEmpty": "",
        "infoFiltered": "",
      },
    //para usar los botones   
            responsive: "true",
            dom: 'Bfrtilp',       
            buttons:[ 
          {
            extend:    'excelHtml5',
            text:      '<i class="fa fa-file"></i> ',
            titleAttr: 'Exportar a Excel',
            className: 'btn btn-success'
          },
          {
            extend:    'pdfHtml5',
            text:      '<i class="fa fa-file"></i> ',
            titleAttr: 'Exportar a PDF',
            className: 'btn btn-danger'
          },
          {
            extend:    'print',
            text:      '<i class="fa fa-print"></i> ',
            titleAttr: 'Imprimir',
            className: 'btn btn-primary'
          },
        ]	        
    });


    $('#order-listing1').DataTable({

      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "language": {
        "info": "_TOTAL_ registros",
        "search": "Buscar",
        "paginate": {
            "next": "Siguiente",
            "previous": "Anterior",
        },
        "lengthMenu": 'Mostrar <select class="form-control">' +
            '<option value="10">10</option>' +
            '<option value="30">30</option>' +
            '<option value="50">50</option>' +
            '<option value="100">100</option>' +
            '<option value="-1">Todo</option>' +
            '</select> registros',
        "loadinRecords": "Cargando...",
        "processing": "Procesando...",
        "emptyTable": "No hay datos",
        "zeroRecords": "No hay coincidencias",
        "infoEmpty": "",
        "infoFiltered": "",
      }, 
        //para usar los botones   
            responsive: "true",
            dom: 'Bfrtilp',       
            buttons:[ 
          {
            extend:    'excelHtml5',
            text:      '<i class="fa fa-file"></i> ',
            titleAttr: 'Exportar a Excel',
            className: 'btn btn-success'
          },
          {
            extend:    'pdfHtml5',
            text:      '<i class="fa fa-file"></i> ',
            titleAttr: 'Exportar a PDF',
            className: 'btn btn-danger'
          },
          {
            extend:    'print',
            text:      '<i class="fa fa-print"></i> ',
            titleAttr: 'Imprimir',
            className: 'btn btn-info'
          },
        ]	        
    });



    $('#order-listing').each(function() {
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Buscar');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
    $('#order-listing1').each(function() {
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Buscar');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
})(jQuery);