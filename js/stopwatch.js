// DataTables Defaults
$.extend( true, $.fn.dataTable.defaults, {
    "language": {
                "url": "//bob.bwl.uni-mainz.de/ebis3/vendor/dt-i18n/german.json"
            }
} );

$(document).ready(function() {
	//Edit-User-Data-Popup Funktion für Magnific-Popup
    $('.edit-userdata-button').magnificPopup({
    type: 'inline',
    modal: true,
	closeOnContentClick: false,
	closeonBgClick: false,
	alignTop: true
  });
  $(document).on('click', '.edit-userdata-dismiss', function(e) {
    e.preventDefault();
    $.magnificPopup.close();
  });
  
  // DataTables Kunden-Tabelle Initialisierung
  $('#kunden-table').DataTable({
	  responsive: true,
	   "columnDefs": [
            {
                "targets": [ -1 ],
                "visible": false,
				"searchable": false
            },
			{
                "targets": [ 2 ],
                "visible": false,
				"searchable": false
            }
        ]
  });
  
  // DataTables Arbeitszeiten-Tabelle Initialisierung
  $('#arbeitszeiten-table').DataTable({
	  "order": [[ 2, "asc" ]],
	  rowGroup: {
		  dataSrc: 0,
		  className: 'table-dark'
	  },
	   "columnDefs": [
            {
                "targets": [ 0 ],
                "visible": false,
				"searchable": true
            }
        ],
	responsive: true
  });
  
  $('#projekte-table').DataTable({
	  "order": [[ 1, "asc" ]],
	  rowGroup: {
		  dataSrc: 1,
		  className: 'table-dark'
	  },
	   "columnDefs": [
            {
                "targets": [ 1 ],
                "visible": false,
				"searchable": true
            },
			{
                "targets": [ 2 ],
                "visible": false,
				"searchable": true
            }
        ],
	responsive: true
  });
  
  $('#rechnungen-table').DataTable({
	responsive: true
  });
});