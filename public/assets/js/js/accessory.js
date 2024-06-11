
document.addEventListener("DOMContentLoaded", function() {
    // Datatables Orders
    $("#datatables-orders").DataTable({
        "dom": 'Bfrtip', // Show buttons (B) for export
        "buttons": [
            {
                extend: 'excelHtml5',
                text: 'Exporter sur Excel',
                className: 'btn btn-success'
            },
            {
                extend: 'copyHtml5',
                text: 'Copier',
                className: 'btn btn-primary'
            },
            {
                extend: 'colvis',
                text: 'Column Visibility',
                className: 'btn btn-warning'
            }
        ],
        responsive: true,
        aoColumnDefs: [{
            bSortable: false,
            aTargets: [-1]
        }]
    });
});
