@extends('admin.theme.main')
@section('title')
    Gestion des Responsable Qualités
@endsection
@section('manualstyle')
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
                            <div class="offcanvas-body mx-0 flex-grow-0">
                                <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    action="{!! route('admin.employee.onestore') !!}" method="POST">
                                    <div class="mb-3 fv-plugins-icon-container">
                                        @csrf
                                        <label class="form-label" for="selents">Entreprise/Filiale d'action</label>
                                        <select id="selents" name="enterprise" class="form-select" required>
                                            @foreach ($ents as $e)
                                                <option value="{{ $e->id }}" data-extra-info="{{ $e->id }}">
                                                    {{ $e->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="firstname">Employé a Pourvoir</label>
                                        <select class="select2 select-event-guests form-select" id="eventGuests"
                                        name="user" required>
                                        @foreach ($employees as $u)
                                            <option data-avatar="{{ $u->image }}" value="{{ $u->email }}">
                                                 Matricule : ({{ $u->matricule }}) {{$u->firstname. ' '. $u->lastname}}
                                            </option>
                                        @endforeach
                                    </select>
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <div class="col-md">
                                            <label class="form-label" for="firstname">Responsable qualité en Intérim ? </label>
                                            <div class="form-check form-check-inline mt-3">
                                                <input class="form-check-input" type="radio" name="interim"
                                                    id="inlineRadio1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">Oui</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="interim"
                                                    id="inlineRadio2" value="0">
                                                <label class="form-check-label" for="inlineRadio2">Non</label>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Ajouter</button>
                                    <button type="reset" class="btn btn-label-secondary"
                                        data-bs-dismiss="offcanvas">Annuler</button>
                                    <input type="hidden">
                                </form>
                            </div>
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
                                        <b>{{ $users->where('id', $d->user)->first()->firstname . $users->where('id', $d->user)->first()->lastname }}</b>
                                        <br>Email : <b>{{ $users->where('id', $d->user)->first()->email }}</b>
                                        <br>Tel. <b>{{ $users->where('id', $d->user)->first()->phone }}</b>
                                    </td>
                                    <td>Entreprise : {{ $ents->where('id', $d->enterprise)->first()->name }}
                                        <br> Rôle : RQ @if ($data->interim == 1)
                                            en Intérim
                                        @else
                                            Principale
                                        @endif
                                    </td>
                                    <td>Aucun département renseigner.</td>
                                    <td>{{ $d->created_at }}</td>
                                    <td>{{ $d->email }}</td>
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
