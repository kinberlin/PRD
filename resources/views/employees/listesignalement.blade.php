@extends('employees.theme.main')
@section('title')
    Mes Signalements cette année
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Mes Signalements</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button type="button" class="btn btn-success" id="exportXlsxBtn">Exporter le tableau vers
                            Excel</button>
                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des signalements que vous avez Soumis</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date de soumission</th>
                                <th>Date de Constat</th>
                                <th>Description</th>
                                <th>Statut</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--<tr>
                                <td>No.</td>
                                <td>Date de soumission</td>
                                <td>Date de Constat</td>
                                <td>Description</td>
                                <td>Statut</td>
                                <td>Statut</td>
                                <td>Actions</td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Orders
            $("#datatables-orders").DataTable({
                "paging": true,
                "pageLength": 10,
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
    </script>
@endsection
