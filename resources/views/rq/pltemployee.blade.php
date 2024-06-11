@extends('rq.theme.main')
@section('title')
    Vue sur les Pilotes de Processus
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
                                        <b>{{ $users->where('id', $d->user)->first()->firstname .' '. $users->where('id', $d->user)->first()->lastname }}</b>
                                        <br>Email : <b>{{ $users->where('id', $d->user)->first()->email }}</b>
                                        <br>Tel. <b>{{ $users->where('id', $d->user)->first()->phone }} | {{ $users->where('id', $d->user)->first()->matricule }}</b>
                                    </td>
                                    <td>Processus : {{ $processes->where('id', $d->process)->first()->name . ' ('.$processes->where('id', $d->process)->first()->surfix.')' }}
                                        <br> Rôle : Pilote @if ($d->interim == 1)
                                            en Intérim
                                        @else
                                            Principale
                                        @endif
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
