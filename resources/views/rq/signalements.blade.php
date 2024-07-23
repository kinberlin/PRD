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
                                <th>Id</th>
                                <th>Detail du<br>Constat</th>
                                <th>Dépense</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>#{{ $d->id }}</td>
                                    <td>Date : <b>{{ $d->occur_date }}</b><br>Lieu :
                                        <b>{{ $d->enterprise . ' (' . $d->site . ')' }}</b>
                                        <br>Enregistrement :
                                        <b>{{ $d->created_at->locale('fr')->isoFormat('DD-MM-YYYY HH:mm:ss') }}</b>
                                    </td>
                                    <td>{{ formatNumber($d->cost) }}</td>
                                    <td>{{ \App\Models\Status::find($d->status)->name }}</td>
                                    <td>
                                        @canany(['isEnterpriseRQ', 'isAdmin'],
                                            [
                                            \App\Models\Enterprise::where('name', $d->enterprise)->get()->first(),
                                            Auth::user(),
                                            ])
                                        @can('DysRunning', $d)
                                            <button type="button" class="btn btn-info rounded-pill btn-icon"
                                                data-bs-toggle="modal" data-bs-target="#dys_cost{{ $d->id }}">
                                                <span class="tf-icons bx bx-dollar"></span>
                                            </button>

                                            <div class="modal animate__animated animate__bounceInUp"
                                                id="dys_cost{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form class="modal-content"
                                                        action="{{ route('dysfunction.cost', ['id' => $d->id]) }}"
                                                        method="POST">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">Dépense non Qualité
                                                                pour [{{ $d->code }}] </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col mb-6">
                                                                    <label for="nameBasic" class="form-label">Montant</label>
                                                                    <input type="number" id="nameBasic" name="cost"
                                                                        value="{{ $d->cost }}" class="form-control"
                                                                        placeholder="Entrer le montant" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-label-secondary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                            <a href="{{ route('rq.n1dysfonction', ['id'=>$d->id]) }}" target="_blank"
                                                class="btn rounded-pill btn-icon btn-success">
                                                <span class="tf-icons bx bx-info-circle"></span>
                                            </a>
                                        @else
                                            Aucune action possible.
                                        @endcanany
                                    </td>
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
@endsection
