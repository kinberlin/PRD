@extends('rq.theme.main')
@section('title')
    Gestion des Départements
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Département</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter un Département</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout de Département</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body mx-0 flex-grow-0">
                                <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    action="/admin/department" method="POST">
                                    @csrf
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Nom du Département</label>
                                        <input type="text" class="form-control" id="name" placeholder="..."
                                            name="data[0][2]" required>
                                        <input type="hidden" class="form-control" name="data[0][0]">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Entreprise</label>
                                        <select id="defaultSelect" name="data[0][1]" class="form-select" required>
                                            @foreach ($ents as $e)
                                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                                            @endforeach
                                        </select>
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
                        
                        <input type="file" style="visibility: hidden" id="excelFileInput">

                        <form action="/admin/department" method="POST">
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
                <h5 class="card-title">Liste des Départements sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>#Id</th>
                                <th>Entreprise</th>
                                <th>Département</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{$ents->where('id', $d->enterprise)->first()->name}} (ID : {{ $d->enterprise }})</td>
                                    <td>{{ $d->name }}</td>
                                    <td> <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majentreprise{{ $d->id }}">
                                            M.A.J
                                        </button>
                                        <div class="modal animate__animated animate__bounceInUp"
                                            id="majentreprise{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form class="modal-content" action="/admin/department/{{ $d->id }}"
                                                    method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">M.A.J
                                                            {{ $d->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="nameBasic" class="form-label">Nom</label>
                                                                <input type="text" name="name"
                                                                    value="{{ $d->name }}" class="form-control"
                                                                    placeholder="Entrer le nom">
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col mb-3">
                                                                <label for="nameBasic" class="form-label">Choisissez
                                                                    l'Entreprise</label>
                                                                <select name="enterprise" class="form-select" required>
                                                                    @foreach ($ents as $e)
                                                                        @if ($d->enterprise == $e->id)
                                                                            <option value="{{ $e->id }}" selected>
                                                                                {{ $e->name }}</option>
                                                                        @else
                                                                            <option value="{{ $e->id }}">
                                                                                {{ $e->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal">Fermer</button>
                                                        <button type="submit"
                                                            class="btn btn-primary">Enregistrer</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
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

    <script src="{!! url('assets/js/js/accessory.js') !!}"></script>
@endsection
