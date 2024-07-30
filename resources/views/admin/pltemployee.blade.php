@extends('admin.theme.main')
@section('title')
    Gestion des Pilotes de Processus
@endsection
@section('manualstyle')
    @livewireStyles
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="col-12">
            <div class="card mb-4">
                <h5 class="card-header">Pilotes</h5>
                <div class="card-body">
                    <div class="demo-inline-spacing">
                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEnd"
                            aria-controls="offcanvasEnd">Ajouter un Pilote</button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEnd"
                            aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasEndLabel" class="offcanvas-title">Formulaire d'Ajout de Pilote</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                                    aria-label="Close"></button>
                            </div>
                            @livewire('addplt-employee-form')
                        </div>

                    </div>
                </div>
                <hr class="m-0">
            </div>
        </div>
        <div class="card">

            <div class="card-body">
                <h5 class="card-title">Liste des Pilotes sur PRD</h5>
                <div class=" align-items-start justify-content-between">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Infos sur <br> le Pilote</th>
                                <th>Processus d'<br>Action & Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>
                                        Entreprise :
                                        <b>{{ $ents->where('id', $users->where('id', $d->user)->first()->enterprise)->first()->name }}</b>
                                        <br>Noms :
                                        <b>{{ $users->where('id', $d->user)->first()->firstname . ' ' . $users->where('id', $d->user)->first()->lastname }}</b>
                                        <br>Email : <b>{{ $users->where('id', $d->user)->first()->email }}</b>
                                        <br>Tel. <b>{{ $users->where('id', $d->user)->first()->phone }} |
                                            {{ $users->where('id', $d->user)->first()->matricule }}</b>
                                    </td>
                                    <td>Processus :
                                        {{ $processes->where('id', $d->process)->first()->name . ' (' . $processes->where('id', $d->process)->first()->surfix . ')' }}
                                        <br> Rôle : Pilote @if ($d->interim == 1)
                                            en Intérim
                                        @else
                                            Principale
                                        @endif
                                    </td>
                                    <td><button type="button" class="btn btn-danger fs-5 fw-bold" data-bs-toggle="modal"
                                            data-bs-target="#delauthRQ{{ $d->id }}">
                                            Retirer l'authorisation
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
        <div class="modal modal-top fade" id="delauthRQ{{ $d->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTopTitle">Confirmation de
                            supression!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <p class="card-text">
                                En continuant, vous allez supprimer l'authorisation RQ possèdé par cet utilisateur. Voulez
                                vous Continuer ?
                                <b>Notez que cette action est irréversible!</b>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                        <a href="{{ route('admin.authplt.destroy', ['id' => $d->id]) }}" class="btn btn-danger">Continuer</a>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    <!--End with datatable Modals -->
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
@endsection
