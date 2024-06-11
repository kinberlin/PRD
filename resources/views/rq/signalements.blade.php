@extends('rq.theme.main')
@section('title')
    Liste des Signalements cette année
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Tous les Signalements</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        
                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des signalements Soumis</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date de soumission</th>
                                <th>Date de Constat</th>
                                <th>Description</th>
                                <th>Lieu</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->occur_date)->format('d-m-Y') }}</td>
                                    <td>{{ $d->description }}</td>
                                    <td>{{ $d->enterprise . ' (' . $d->site . ')' }}</td>
                                    <td>{{ $status->where('id', $d->status)->first()->name }}</td>
                                    <td>
                                        @canany(['isEnterpriseRQ', 'isAdmin'], [\App\Models\Enterprise::where('name', $d->enterprise)->get()->first(), Auth::user()])
                                            <a href="/rq/detail/dysfonctionnement/{{ $d->id }}" target="_blank"
                                                class="btn rounded-pill btn-icon btn-info">
                                                <span class="tf-icons bx bx-info-circle"></span>
                                            </a>
                                        @else
                                        Aucune action possible.
                                        @endcanany
                                    </td>
                                </tr>
                            @endforeach
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
        /* document.addEventListener("DOMContentLoaded", function() {
                // Datatables Orders
                $("#datatables-order").DataTable({
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
            });*/
    </script>
@endsection
