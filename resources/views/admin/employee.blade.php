@extends('admin.theme.main')
@section('title')
    Gestion des Employés
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Employés</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter un Employé</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout d'Employé</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body mx-0 flex-grow-0">
                                <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    action="/admin/employee" method="POST">
                                    <div class="mb-3 fv-plugins-icon-container">
                                        @csrf
                                        <label class="form-label" for="name">Nom du Service</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Responsable du Service</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Vice-Responsable du Service</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Entreprise</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Département</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Niveau</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Service Parent</label>
                                        <input type="text" class="form-control" id="name" placeholder="Cadyst ...."
                                            name="name" aria-label="Cadyst">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Ajouter</button>
                                    <button type="reset" class="btn btn-label-secondary"
                                        data-bs-dismiss="offcanvas">Annuler</button>
                                    <input type="hidden">
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info" id="importBtn">Importer depuis un fichier</button>
                        <button type="button" class="btn btn-success" id="exportXlsxBtn">Exporter le tableau vers
                            Excel</button>
                        <a href="{!! url('assets/extras/cadyst_liste_employee_modele.xlsx') !!}" class="btn btn-secondary">Télécharger le Modele</a>
                        <input type="file" style="visibility: hidden" id="excelFileInput">

                        <form action="/admin/employee" method="POST">
                            @csrf
                            <table id="dataTable" class="display" style="width:100%">
                            </table>
                            <button id="checkAllBtn" class="secondary-btn">Vérifier</button>
                            <button id="submitBtn" type="submit">Soumettre</button>
                        </form>
                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des Employés sur WorkWave</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Entreprise</th>
                                <th>Département</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Téléphone</th>
                                <th>Matricule</th>
                                <th>Solde<br>Vacance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $ents->where('id', $d->enterprise)->first()->name }}</td>
                                    @if ($deps->where('id', $d->department)->first() != null)
                                        <td>{{ $deps->where('id', $d->department)->first()->name }}</td>
                                    @elseif($ents->where('manager', $d->id)->first() != null)
                                        <td>DG : {{ $ents->where('manager', $d->id)->first()->name }}</td>
                                    @else
                                        <td>Aucun département renseigner.</td>
                                    @endif
                                    <td>{{ $d->firstname }}</td>
                                    <td>{{ $d->lastname }}</td>
                                    <td>{{ $d->email }}</td>
                                    @if (empty($d->service))
                                        @if ($deps->where('id', $d->department)->first() != null)
                                            <td>Chef de Département
                                                ({{ $deps->where('id', $d->department)->first()->name }})
                                            </td>
                                        @else
                                            <td>Service Inconnu</td>
                                        @endif
                                    @else
                                        <td>{{ $services->where('id', $d->service)->first()->name }} (ID :
                                            {{ $d->service }})</td>
                                    @endif
                                    <td>{{ $d->phone }}</td>
                                    <td>{{ $d->matricule }}</td>
                                    <td>{{ $d->holiday }}j</td>
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

    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
@endsection
