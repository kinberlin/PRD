
document.addEventListener("DOMContentLoaded", function() {
    // Datatables Orders
    $("#datatables-orders").DataTable({
        "dom": 'Bfrtip', // Show buttons (B) for export
        "buttons": [
            'excel' // Add export button for Excel
        ],
        responsive: true,
        aoColumnDefs: [{
            bSortable: false,
            aTargets: [-1]
        }]
    });
});

$(document).ready(function() {
    let rowCount = 0; // Track the number of rows
    let dataTable; // Variable to store DataTable instance

    $('#importBtn').click(function() {
        $('#excelFileInput').click();
    });

    $('#excelFileInput').change(function(e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {
                type: 'array'
            });
            const sheetName = workbook.SheetNames[0];
            const sheet = workbook.Sheets[sheetName];
            const jsonData = XLSX.utils.sheet_to_json(sheet, {
                header: 1
            });

            // Clear the existing table
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                // Reset the DataTable
                $('#dataTable').DataTable().destroy();

                // Convert the table back to a regular table
                $('#dataTable').removeClass('dataTable').addClass('table');

                // Empty the table
                $('#dataTable').empty();
            }


            // Iterate over the data and populate the table
            jsonData.forEach(function(rowData, rowIndex) {
                const row = $('<tr></tr>'); // Create a new row

                // Determine if this is the first row (header row)
                if (rowIndex === 0) {
                    rowData.forEach(function(headerData) {
                        const header = $('<th>' + headerData +
                            '</th>'); // Create a new header cell
                        row.append(header); // Append header cell to row
                    });
                    $('#dataTable').append('<thead></thead>'); // Create thead element
                    $('#dataTable thead').append(row); // Append header row to thead
                } else {
                    let col_ = 0;
                    rowData.forEach(function(cellData) {
                        const cell = $('<td></td>'); // Create a new cell
                        const input = $('<input type="text" name="data[' +
                            rowCount + '][' + col_ + ']" value="' +
                            cellData + '" required>'); // Create input field
                        cell.append(input); // Append input field to cell
                        row.append(cell); // Append cell to row
                        col_++;
                    });
                    $('#dataTable').append(row); // Append row to table
                    col_ = 0;
                    rowCount++; // Increment row count
                }
            });

        };
        setTimeout(function() {
            $('#dataTable').DataTable();
        }, 2000);
        reader.readAsArrayBuffer(file);
    });

    $('#checkAllBtn').click(function() {
        $('#dataTable input[type="text"]').each(function() {
            if ($(this).val().trim() === '') {
                $(this).focus();
                return false;
            }
        });
    });

    $('#submitBtn').click(function() {
        const formData = $('#dataTable input[type="text"]').serializeArray();
        console.log(formData); // Here you can send formData to the server via POST
    });
});

$(document).ready(function() {
    var table = $('#datatables-orders').DataTable();

    $('#exportCsvBtn').on('click', function() {
        exportToCsv(table);
    });

    $('#exportXlsxBtn').on('click', function() {
        exportToXlsx(table);
    });
});

function exportToCsv(table) {
    var csvData = table
        .rows({
            search: "applied"
        })
        .data()
        .toArray();

    var csvHeaders = table
        .columns()
        .header()
        .toArray()
        .map(function(header) {
            return header.innerText;
        });

    var lastColumnIndex = csvHeaders.length - 1;
    var csvContent = csvHeaders.join(",") + "\n";

    // Iterate over each row of data
    csvData.forEach(function(row) {
        // Iterate over each cell in the row except the last column
        row.slice(0, lastColumnIndex).forEach(function(cell, index) {
            // Add the cell value to the CSV content
            csvContent += '"' + cell + '"';

            // Add a comma separator if it's not the last cell in the row
            if (index < lastColumnIndex - 1) {
                csvContent += ",";
            }
        });

        // Add a new line after each row
        csvContent += "\n";
    });

    var blob = new Blob([csvContent], {
        type: "text/csv;charset=utf-8;"
    });
    saveAs(blob, "datatable-export.csv");
}

function exportToXlsx(table) {
    var xlsxData = table
        .rows({
            search: "applied"
        })
        .data()
        .toArray();

    var xlsxHeaders = table
        .columns()
        .header()
        .toArray()
        .map(function(header) {
            return header.innerText;
        });

    var lastColumnIndex = xlsxHeaders.length - 1;

    var xlsxArray = [xlsxHeaders.slice(0, lastColumnIndex)].concat(
        xlsxData.map(function(row) {
            return row.slice(0, lastColumnIndex);
        })
    );

    var worksheet = XLSX.utils.aoa_to_sheet(xlsxArray);
    var workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
    var wbout = XLSX.write(workbook, {
        bookType: "xlsx",
        type: "array"
    });
    saveAs(
        new Blob([wbout], {
            type: "application/octet-stream"
        }),
        'cadyst_liste_WorkWave_' + new Date().toLocaleString() + '.xlsx'
    );
}

function saveAs(blob, filename) {
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}
