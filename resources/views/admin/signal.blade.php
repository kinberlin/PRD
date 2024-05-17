@extends('admin.theme.main')
@section('title')
    Gestion des Signalements
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Signal</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-widget-separator-wrapper">
                                    <div class="card-body card-widget-separator">
                                        <div class="row gy-4 gy-sm-1">
                                            <div class="col-sm-6 col-lg-3">
                                                <div
                                                    class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                                    <div>
                                                        <h3 class="mb-1">{{$complainant}}</h3>
                                                        <p class="mb-0">Plaignants</p>
                                                    </div>
                                                    <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                                                        <i class="bx bx-user bx-sm"></i>
                                                    </span>
                                                </div>
                                                <hr class="d-none d-sm-block d-lg-none me-4">
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <div
                                                    class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-3 pb-sm-0">
                                                    <div>
                                                        <h3 class="mb-1">{{count($data)}}</h3>
                                                        <p class="mb-0">Signalements</p>
                                                    </div>
                                                    <span class="badge bg-label-secondary rounded p-2 me-lg-4">
                                                        <i class="bx bx-file bx-sm"></i>
                                                    </span>
                                                </div>
                                                <hr class="d-none d-sm-block d-lg-none">
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <div
                                                    class="d-flex justify-content-between align-items-start border-end pb-3 pb-sm-0 card-widget-3">
                                                    <div>
                                                        <h3 class="mb-1">{{count($data->where('status',6))}}</h3>
                                                        <p class="mb-0">Traités</p>
                                                    </div>
                                                    <span class="badge bg-label-secondary rounded p-2 me-sm-4">
                                                        <i class="bx bx-check-double bx-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h3 class="mb-1">{{count($data->whereNotIn('id', [1, 3, 6]))}}</h3>
                                                        <p class="mb-0">En cours de traitement</p>
                                                    </div>
                                                    <span class="badge bg-label-secondary rounded p-2">
                                                        <i class="bx bx-error-circle bx-sm"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des Signalements sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Lieu</th>
                                <th>Date d'Ajout<br>sur PRD</th>
                                <th>Date de Constat</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>#{{$d->id}}</td>
                                    <td>{{$d->enterprise}} ( {{$d->site}} )</td>
                                    <td>{{$d->created_at}}</td>
                                    <td>{{$d->occur_date}}</td>
                                    <td>{{\App\Models\Status::find($d->status)->name}}</td>
                                    <td></td>
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
@endsection
