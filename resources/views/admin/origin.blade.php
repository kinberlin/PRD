@extends('admin.theme.main')
@section('title')
    Gestion des divers Catégories de Dysfonctionnements
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Catégorie de Dysfonctionnements</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter une Catégorie</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body mx-0 flex-grow-0">
                                <form class="add-new-user pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    novalidate="novalidate" action="{{ route('admin.origin.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="name">Nommez la Catégorie</label>
                                        <input type="text" maxlength="50" class="form-control" name="data[0][1]"
                                            placeholder="..." required>
                                        <input type="hidden" class="form-control" name="data[0][0]">
                                        <div
                                            class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                        </div>
                                    </div>
                                    <div class="mb-3 fv-plugins-icon-container">
                                        <label class="form-label" for="maxlost">Description</label>
                                        <textarea class="form-control" name="data[0][2]" required></textarea>
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
                        <form action="{{ route('admin.origin.store') }}" method="POST">
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
                <h5 class="card-title">Liste des Catégories sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Catégories</th>
                                <th>Description</th>
                                <th>Visible</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->name }}</td>
                                    <td>{{ $d->description }}</td>
                                    <td>@if ($d->visible) Oui @else Non @endif</td>
                                    <td>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#majorigin{{ $d->id }}">
                                            M.A.J
                                        </button>
                                        @can('canOriginDelete', $d)
                                            <button class="btn btn-danger " data-bs-toggle="modal"
                                                data-bs-target="#delorigin{{ $d->id }}">Supprimer</button>
                                        @endcan
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#originVisibility{{ $d->id }}">
                                            Visibilité
                                        </button>
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
        @can('canOriginDelete', $d)
            <div class="modal modal-top fade" id="delorigin{{ $d->id }}" tabindex="-1">
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
                                    Souhaitez vous vraiment supprimer :
                                    {{ $d->name }} ?
                                    <b>Notez que cela reviens a supprimer celle-ci
                                        et que vous ne serez pas capable de le restaurer
                                        sur cette interface.</b>
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                            <a href="{{ route('admin.origin.destroy', ['id' => $d->id]) }}"
                                class="btn btn-danger">Continuer</a>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
        <div class="modal animate__animated animate__bounceInUp" id="majorigin{{ $d->id }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content" action="{{ route('admin.origin.update', ['id' => $d->id]) }}"
                    method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">M.A.J
                            {{ $d->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="col mb-12">
                                <label for="nameBasic{{ $d->id }}" class="form-label">Nom</label>
                                <input type="text" maxlength="50" id="nameBasic{{ $d->id }}" name="name"
                                    value="{{ $d->name }}" class="form-control" placeholder="Entrer le nom"
                                    required>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="note{{ $d->id }}" class="form-label">Description</label>
                                <textarea rows="2" id="note{{ $d->id }}" name="description" class="form-control" required>{{ $d->description }}</textarea>
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
        <div class="modal animate__animated animate__bounceInUp" id="originVisibility{{ $d->id }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form class="modal-content" action="{{ route('origin.visible', ['id' => $d->id]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="orivisible{{ $d->id }}">M.A.J Visibilité
                            {{ $d->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <p class="card-text">
                                Souhaitez-vous vraiment mettre à jour la visibilité de la ressource :
                                {{ $d->name }} ?
                                <b>Notez que dans ce cas de figure, si la visibilité est désactivée,
                                    la ressource ne sera pas affichée sur la page d'identification des dysfonctionnements
                                    réservée aux RQ.</b>
                            </p>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-check-success">
                                <label class="form-check-label" for="visCheckOri{{ $d->id }}">Cocher pour rendre
                                    visible.</label>
                                <input class="form-check-input" type="checkbox" name="visibility" value="1"
                                    @if ($d->visible) checked @endif id="visCheckOri{{ $d->id }}">
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
