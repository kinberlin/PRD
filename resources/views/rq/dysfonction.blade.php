@extends('rq.theme.main')
@section('title')
    Signaler un Dysfonctionnement
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="col-xl-12">
            <!-- HTML5 Inputs -->
            <div class="card mb-4">
                <h5 class="card-header">Formulaire de Signalement de Dysfonctionnement</h5>
                <form class="card-body" action="{!! route('dysfunction.init') !!}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!--<div class="mb-3 row">

                                                                    <div class="col-md-8">
                                                                        <label for="html5-text-input" class="col-md-3 col-form-label" data-bs-toggle="tooltip"
                                                                            data-bs-offset="0,6" data-bs-placement="right" data-bs-html="true"
                                                                            data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Si vous ne trouvez pas ci-dessous, le motif qui vous concerne, alors il ne s'agit peut-être pas d'une Permission Exceptionelle.</span>"
                                                                            aria-describedby="tooltip732616">Processus Affecté (<span style="color: red">*</span>)
                                                                            ?</label>
                                                                        <div class="col-md-9">
                                                                            <select id="pmetype" name="type" class="form-select" required>
                                                                                <option value="0" data-extra-info="0" selected>
                                                                                    Achat</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>-->
                    <div class="mb-3 row">
                        <label for="html5-tel-input" class="col-md-3 col-form-label">Date de Constat ? (<span
                                style="color: red">*</span>)</label>
                        <div class="col-md-9">
                            <input class="form-control" type="date" name="occur_date" id="occur_date"
                                placeholder="Renseigner la date à laquelle vous avez effectué le constat." required>
                        </div>
                    </div>
                    <div class="mb-3 row">

                        <div class="col-md-6">
                            <label for="html5-text-input" class="col-md-4 col-form-label" data-bs-toggle="tooltip"
                                data-bs-offset="0,6" data-bs-placement="right" data-bs-html="true"
                                data-bs-original-title="<i class='bx bx-info-circle bx-xs' ></i> <span>Filiale où le constat a été effectué.</span>"
                                aria-describedby="tooltip732616">Filiale/Entreprise : ?(<span
                                    style="color: red">*</span>)</label>
                            <div class="col-md-10">
                                <select id="selents" name="enterprise" class="form-select" required>
                                    @foreach ($ents as $e)
                                        @if ($e->visible)
                                            <option value="{{ $e->name }}" data-extra-info="{{ $e->id }}">
                                                {{ $e->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="html5-text-input" class="col-md-4 col-form-label">Site : (<span
                                    style="color: red">*</span>)</label>
                            <div class="col-md-10">
                                <select id="selsite" name="site" class="form-select" required>
                                    @foreach ($site as $d)
                                        @if ($ents->where('id', $d->enterprise)->first()->visible)
                                            @can('isSiteVisible', $d)
                                                <option value="{{ $d->id }}" data-extra-info="{{ $d->enterprise }}">
                                                    {{ $d->name }} ({{ $d->location }})</option>
                                            @endcan
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description du Dysfonctionnement(<span
                                style="color: red">*</span>)</label>
                        <textarea class="form-control" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-repeater">
                            <div data-repeater-list="group-a">
                                <div data-repeater-item>
                                    <div class="row">
                                        <label for="formFile" class="form-label" data-bs-toggle="tooltip"
                                            data-bs-offset="0,6" data-bs-placement="right" data-bs-html="true"
                                            data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Veuillez fournir des documents justificatifs inferieurs a 5mb.</span>"
                                            aria-describedby="tooltip732616">Pieces Jointes ? (<span class="text-danger"
                                                style="font-size: 11px" style="">Si vous n'avez pas choisi de piece
                                                jointe pour ce champ, pensez a cliquer sur le bouton : "Retirer la
                                                Piece"</span>)
                                            ?</label>
                                        <input class="form-control" type="file" name="pj" onchange="getFile(this)"
                                            required>
                                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                            <button class="btn btn-label-danger mt-4" type="button" data-repeater-delete>
                                                <i class="bx bx-x me-1"></i>
                                                <span class="align-middle">Retirer cette piece</span>
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-primary" type="button" data-repeater-create>
                                    <i class="bx bx-plus me-1"></i>
                                    <span class="align-middle">Nouvelle Pieces Jointes</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-label-success" type="submit">
                        <span class="tf-icons bx bx-send me-1"></span>Soumettre
                    </button>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/jquery-repeater/jquery-repeater.js') !!}"></script>
    <script src="{!! url('assets/js/js/forms-extras.js') !!}"></script>
    <script src="{!! url('assets/js/js/dysfonction.js') !!}"></script>
@endsection
