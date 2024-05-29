@extends('admin.theme.main')
@section('title')
    Gestion des Responsable Qualités
@endsection
@section('manualstyle')
    @livewireStyles
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Responsable Qualités</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter un Responsable Qualité</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout d'Employé</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            @livewire('add-r-q-employee-form')
                        </div>
                        <button type="button" class="btn btn-success" id="exportXlsxBtn">Exporter le tableau vers
                            Excel</button>
                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des Responsable Qualités sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Infos sur <br> le Responsable</th>
                                <th>Entreprise d'<br>Action & Role</th>
                                <th>Date d'attribution</th>
                                <th>Matricule</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>
                                        Entreprise :
                                        <b>{{ $ents->where('id', $users->where('id', $d->user)->first()->enterprise)->first()->name }}</b>
                                        <br>Noms :
                                        <b>{{ $users->where('id', $d->user)->first()->firstname .' '. $users->where('id', $d->user)->first()->lastname }}</b>
                                        <br>Email : <b>{{ $users->where('id', $d->user)->first()->email }}</b>
                                        <br>Tel. <b>{{ $users->where('id', $d->user)->first()->phone }}</b>
                                    </td>
                                    <td>Entreprise : {{ $ents->where('id', $d->enterprise)->first()->name }}
                                        <br> Rôle : RQ @if ($d->interim == 1)
                                            en Intérim
                                        @else
                                            Principale
                                        @endif
                                    </td>
                                    <td>{{ $d->created_at }}</td>
                                    <td>{{ $d->matricule }}</td>
                                    <td><button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majemployeerr{{ $d->id }}">
                                            M.A.J
                                        </button></td>
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
@livewireScripts
    <script src="{!! url('assets/vendor/libs/select2/select2.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/js/js/form-layouts.js') !!}"></script>
    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
    <script>
        $(document).ready(function() {
            $('#selents').change(function() {
                var selectedOption = $(this).find(':selected');
                var selectedValue = selectedOption.attr('data-extra-info');
                $('#seldep option').hide();
                $('#seldep option[data-extra-info="' + selectedValue + '"]').show();
                $('#seldep').val($('#selsite option:visible:first').val());
            });
        });
    </script>
@endsection
