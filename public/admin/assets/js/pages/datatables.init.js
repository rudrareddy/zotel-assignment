$(document).ready(function() {
    $("#datatable-buttons").DataTable({
        lengthChange: !1,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All']
        ],
        layout: {
            topStart: {
                buttons: [
                    { extend: 'pageLength', text: 'Showing <i class="fa fa-caret-down"></i>'},
                    { extend: 'copy', text: 'Copy', exportOptions: { modifier: { search: 'applied' }},columns: [1,2]},
					{ extend: 'print', text: 'Print', exportOptions: { modifier: { search: 'applied' }},columns: [1,2]},
					{ extend: 'csv', text: 'CSV', exportOptions: { modifier: { search: 'applied' }}},
                    { extend: 'pdfHtml5', text: 'PDF', exportOptions: { modifier: { search: 'applied' }},download: 'open',columns: [1,2]},
                ]
            }
        },
        fixedHeader: {
            header: true,
            footer: false,
            headerOffset: 70
        },
        columnDefs: [{
            "targets": 'no-sort',
            "orderable": false,
        }]

    });
});