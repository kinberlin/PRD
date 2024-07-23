
document.addEventListener("DOMContentLoaded", function() {
    // Datatables Orders
    $("#datatables-orders").DataTable({
        "dom": 'Bfrtip', // Show buttons (B) for export
        "buttons": [
            {
                extend: 'excelHtml5',
                text: 'Exporter vers Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'copyHtml5',
                text: 'Copier',
                className: 'btn btn-primary'
            },
            {
                extend: 'colvis',
                text: 'Visibiité des colonnes',
                className: 'btn btn-warning'
            }
        ],
        responsive: true,
        aoColumnDefs: [{
            bSortable: false,
            aTargets: [-1]
        }],
        "order": [] // Disable initial sorting
    });
});
