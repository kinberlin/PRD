@extends('admin.theme.main')
@section('title')
    Gestion des Processus
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Processus</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter un Processus</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body mx-0 flex-grow-0">
                                <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    novalidate="novalidate" action="/admin/processes" method="POST">
                                    @csrf
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Nom du Processus</label>
                                        <input type="text" class="form-control" name="data[0][1]"
                                            placeholder="Nom du Processus" required>
                                        <input type="hidden" class="form-control" name="data[0][0]">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Abbréviation</label>
                                        <input type="text" class="form-control" name="data[0][2]"
                                            placeholder="Abbréviation du Process" required>
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
                        <form action="/admin/processes" method="POST">
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
                <h5 class="card-title">Liste de Processus sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nom du Processus</th>
                                <th>Abbrév.</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->name }}</td>
                                    <td>{{ $d->surfix }}</td>
                                    <td> <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#majprocessus{{ $d->id }}">
                                            M.A.J
                                        </button>
                                        @can('canProcessDelete', $d)
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#delprocess{{ $d->id }}">Supprimer</button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!--Begin with datatable Modals -->
    @foreach ($data as $d)
        @can('canProcessDelete', $d)
            <div class="modal modal-top fade" id="delprocess{{ $d->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalTopTitle">Confirmation de
                                Suppression!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="card-body">
                                <p class="card-text">
                                    Souhaitez vous vraiment supprimer le Processus :
                                    {{ $d->name }} ?
                                    <b>Notez que cela reviens a supprimer celui-ci
                                        et que vous ne serez pas capable de le restaurer.</b>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                            <a href="{{ route('admin.processes.destroy', ['id' => $d->id]) }}"
                                class="btn btn-danger">Continuer</a>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
        <div class="modal animate__animated animate__bounceInUp" id="majprocessus{{ $d->id }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content" action="{{ route('admin.processes.update', ['id' => $d->id]) }}"
                    method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">M.A.J
                            {{ $d->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col mb-3">
                                <label for="nameBasic" class="form-label">Nom</label>
                                <input type="text" name="name" id="nameBasic" value="{{ $d->name }}"
                                    class="form-control" placeholder="Entrer le nom">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="abbrevBasic" class="form-label">Abbréviation</label>
                                <input type="text" id="abbrevBasic" value="{{ $d->surfix }}" name="surfix"
                                    class="form-control" placeholder="Entrer le surfix">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    <!--End with datatable Modals -->
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
