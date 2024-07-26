@extends('rq.theme.main')
@section('title')
    Détails sur le signalement {{ $data->id }}
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Vue Complémentaire sur le signalement </span> No. {{ $data->code }}
        </h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Détail sur le Signalement</h5>
                        <small class="text-muted float-end">Infos</small>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-fullname">Noms</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    value={{ $data->emp_signaling }} readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-company">Matricule</label>
                                <input type="text" class="form-control" id="basic-default-company"
                                    value="{{ $data->emp_matricule }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-email">Contact</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="basic-default-email" class="form-control"
                                        value="{{ $data->emp_email }}" aria-label="john.doe"
                                        aria-describedby="basic-default-email2">
                                    <span class="input-group-text" id="basic-default-email2">@</span>
                                </div>
                                <div class="form-text"> Extras</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date d'enregistrement sur PRD</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Date de Constat</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($data->occur_date)->format('d-m-Y') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="basic-default-message">Description</label>
                                <textarea id="basic-default-message" class="form-control" placeholder="Aucune description n'a été faites" readonly> {{ $data->description }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pieces Jointes soumises</h5>
                        <small class="text-muted float-end">avec ce Signalement </small>
                    </div>
                    <div class="card-body">
                        <form class="col-md-12">
                            @if ($data->pj != null)
                                @if (count(json_decode($data->pj)) < 8)
                                    @foreach (json_decode($data->pj) as $index => $item)
                                        <div class="d-flex mt-3">
                                            <a href="{{ $item }}" target="_blank"
                                                class="d-flex align-items-center me-3">
                                                <img src="{!! url('assets/img/icons/misc/pdf.png') !!}" alt="Documents" width="46"
                                                    class="me-2">
                                                <h4 class="mb-0">Pieces Jointes No. {{ $index }}</h4>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    @foreach (json_decode($data->pj) as $index => $item)
                                        <div class="d-flex mt-3">
                                            <a href="{{ $item }}" target="_blank"
                                                class="d-flex align-items-center me-3">
                                                <img src="{!! url('assets/img/icons/misc/pdf.png') !!}" alt="Documents" width="23"
                                                    class="me-2">
                                                <h6 class="mb-0">Pieces Jointes No. {{ $index }}</h6>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                            @else
                                Aucune Piece Jointe n'a été soumis.
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Multi Column with Form Separator -->
        <div class="card mb-4">
            <h5 class="card-header">Informations complémentaires</h5>
            <form class="card-body" action="{!! route('dysfunction.store', ['id' => $data->id]) !!}" method="POST">
                <!--<hr class="my-4 mx-n4"> <h6> Info Supplementaires</h6>-->
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-last-name">Entreprise & Site Concerné (Non
                            Modifiable)</label>
                        <input type="text" value="{{ $data->enterprise }}  ({{ $data->site }})" class="form-control"
                            readonly>
                    </div>
                    <div class="col-md-6 select2-primary">
                        <label class="form-label" for="multicol-language1">Processus Concernés (<span
                                style="color: red">*</span>)</label>
                        <select id="multicol-language1" name="concern_processes[]" class="select2 form-select" required>
                            <option value="" style="display: none;"></option>
                            @foreach ($processes as $p)
                                <option value="{{ $p->name }}" data-extra-info="{{ $p->id }}"
                                    @if ($data->concern_processes != null && in_array($p->name, json_decode($data->concern_processes, true))) selected @endif>
                                    {{ $p->name }} ({{ $p->surfix }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 select2-primary">
                        <label class="form-label" for="multicol-language2">Processus Impactés (<span
                                style="color: red">*</span>)</label>
                        <select name="impact_processes[]" class="select2 form-select" multiple required>
                            @foreach ($processes as $p)
                                <option value="{{ $p->name }}" data-extra-info="{{ $p->id }}"
                                    @if ($data->concern_processes != null && in_array($p->name, json_decode($data->impact_processes, true))) selected @endif>
                                    {{ $p->name }} ({{ $p->surfix }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gravité (<span style="color: red">*</span>)</label>
                        <select class="form-control" name="gravity" data-allow-clear="true" required>
                            @foreach ($gravity as $g)
                                <option value="{{ $g->name }}" @if ($g->name == $data->gravity) selected @endif>
                                    {{ $g->name }} (Note : {{ $g->note }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Catégorie (<span style="color: red">*</span>)</label>
                        <select class="form-control" name="origin" data-allow-clear="true" required>
                            @foreach ($origin as $o)
                                <option value="{{ $o->id }}" @if ($o->name == $data->origin) selected @endif>
                                    {{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-last-name">Probabilité(<span
                                style="color: red">*</span>)</label>
                        <select class="form-control" name="probability" data-allow-clear="true" required>
                            @foreach ($probability as $p)
                                <option value="{{ $p->id }}" @if ($p->id == $data->probability) selected @endif>
                                    {{ $p->name }} (Note : {{ $p->note }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Responsable(s) probable(s) de l'incident</label>
                        <input type="text"
                            value="@if (empty($data->cause)) Aucun Responsable Identifié @else {{ $data->cause }} @endif"
                            name="cause" class="form-control"
                            placeholder="Le(s) Nom(s) de(s) Responsable(s) & matricule(s) si possible">
                    </div>
                </div>
                <div class="pt-4">
                    @if ($data->status != 3)
                        <div class="row">
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-success me-sm-3 me-1">Mettre à jour</button>
                            </div>
                            <div class="col-md-8 text-end">
                                <button type="reset" class="btn btn-secondary">Annuler les modifications</button>
                                <button class="btn btn-danger " data-bs-toggle="modal"
                                    data-bs-target="#rejectdys{{ $data->id }}">Rejeter ce signalement</button>

                            </div>
                        </div>
                    @endif
                </div>

            </form>
        </div>
        <div class="modal modal-top fade" id="rejectdys{{ $data->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTopTitle">Confirmation de
                            Rejet!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card-body">
                            <p class="card-text">
                                Souhaitez-vous vraiment rejeter ce dysfonctionnement ?
                                <b>Notez que cette action est irréversible.</b>
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Fermer</button>
                        <a href="{!! route('dysfunction.cancel', ['id' => $data->id]) !!}" class="btn btn-danger">Continuer</a>
                    </div>
                </form>
            </div>
        </div>
        <!-- Collapsible Section -->
        @if ($data->status != 1 && $data->status != 3)
            <div class="row my-4">
                <div class="col">
                    <h6> Mesures correctives</h6>
                    <div class="accordion" id="collapsibleSection2">
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#accordionPlanner" aria-expanded="true"
                                    aria-controls="accordionPlanner"> Actions correctives</button>
                            </h2>
                            <div id="accordionPlanner" class="accordion-collapse collapse show"
                                data-bs-parent="#collapsibleSection2">
                                <div class="accordion-body">
                                    <div class="pt-4">
                                        @if ($data->status != 3)
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <form action="{!! route('rq.planner', ['id' => $data->id]) !!}" method="GET">
                                                        <div>
                                                            <button type="submit" id="saveActionsBtn"
                                                                class="btn btn-success me-sm-3 me-1">Aller à la page de
                                                                planification</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                @if ($data->status != 5)
                                                    <div class="col-md-8 text-end">
                                                        <a href="{!! route('dysfunction.evaluation.launch', ['id' => $data->id]) !!}" class="btn btn-success">Débuter
                                                            l'évaluation</a>
                                                    </div>
                                                @else
                                                    <div class="col-md-8 text-end">
                                                        <a href="{!! route('dysfunction.evaluation.cancel', ['id' => $data->id]) !!}" class="btn btn-success">Arrêter
                                                            l'évaluation</a>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($data->status >= 5)
            <div class="row my-4">
                <div class="col">
                    <h6> Evaluation des Actions</h6>
                    <div class="accordion" id="collapsibleSection">
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="headingDeliveryAddress">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseDeliveryAddress" aria-expanded="true"
                                    aria-controls="collapseDeliveryAddress"> Evaluations des Actions</button>
                            </h2>
                            <div id="collapseDeliveryAddress" class="accordion-collapse collapse show"
                                data-bs-parent="#collapsibleSection">
                                <div class="accordion-body">
                                    <form class="row g-3" id="evaluateConfirmForm" action="{!! route('dysfunction.evaluation', ['id' => $data->id]) !!}"
                                        method="POST">
                                        @csrf
                                        @foreach ($corrections as $c)
                                            @php
                                                $ev = $evaluations->where('task', $c->id)->first();
                                            @endphp
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <input type="hidden" name="id[]" value="{{ $c->id }}" />
                                                    <div class="mb-4 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="form-repeater-1-1">Action</label>
                                                        <input type="text" name="action" value="{{ $c->text }}"
                                                            class="form-control" disabled />
                                                    </div>
                                                    <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label" for="form-repeater-1-3">Satisfaction
                                                            (%)
                                                        </label>
                                                        <input type="number" name="satisfaction[]" class="form-control"
                                                            placeholder="%" min="0" max="100"
                                                            value="{{ $ev != null ? $ev->satisfaction : null }}"
                                                            @if ($data->status == 5) required @else disabled @endif />
                                                    </div>
                                                    <div class="mb-4 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="multicol-country">Criteres</label>
                                                        <textarea rows="2" name="criteria[]" class="form-control"
                                                            @if ($data->status == 5) required @else disabled @endif>{{ $ev != null ? $ev->evaluation_criteria : null }}</textarea>
                                                    </div>
                                                    <div class="mb-3 col-lg-6 col-xl-2 col-12 mb-0">
                                                        <label class="form-label">Completude(%)</label>
                                                        <input type="number" name="completion[]" class="form-control"
                                                            placeholder="%" min="0" max="100"
                                                            value="{{ $ev != null ? $ev->completion : null }}"
                                                            @if ($data->status == 5) required @else disabled @endif />
                                                    </div>
                                                </div>
                                        @endforeach
                                        <hr>
                                        @if ($data->status == 5)
                                            <div class="mb-0">
                                                <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#evalCompleteModal">
                                                    <i class="bx bx-plus me-1"></i>
                                                    <span class="align-middle">Terminer l'Evaluation</span>
                                                </button>
                                            </div>
                                        @endif
                                    </form>
                                    @if ($data->status == 5)
                                        <div class="modal modal-top fade" id="evalCompleteModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTopTitle">Confirmation de
                                                            Fermeture!</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <p class="card-text">
                                                                Souhaitez vous vraiment terminer l'évaluation des actions
                                                                correctives de ce dysfonctionnement ?
                                                                <b>Notez que vous ne pourrez plus modifier ce
                                                                    dysfonctionnement en terme d'identification, de
                                                                    planification etc..</b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal">Fermer</button>
                                                        <button id="confirmEvaluation"
                                                            class="btn btn-warning">Continuer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($data->status == 7)
            <div class="row my-4">
                <div class="col">
                    <h6> Evaluation du Dysfonctionnement</h6>
                    <div class="accordion" id="DysEvaluation">
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="headingDysEvaluation">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseDysEvaluation" aria-expanded="true"
                                    aria-controls="collapseDysEvaluation"> Evaluations du Dysfonctionnement</button>
                            </h2>
                            <div id="collapseDysEvaluation" class="accordion-collapse collapse show"
                                data-bs-parent="#DysEvaluation">
                                <div class="accordion-body">
                                    <form class="row g-3" id="dysConfirmForm" action="{!! route('dysfunction.close', ['id' => $data->id]) !!}"
                                        method="POST">
                                        @csrf
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="mb-4 form-check form-switch col-lg-6 col-xl-3 col-12 mb-0">
                                                    <label class="form-check-label" for="switchSolve">Dysfonctionnements
                                                        résolus ? </label>
                                                    <input class="form-check-input" id="switchSolve" type="checkbox"
                                                        name="solved" @if ($data->solved == 1) checked @endif
                                                        @if (!is_null($data->closed_at)) readonly @endif required>
                                                </div>
                                                <div class="mb-4 col-lg-6 col-xl-9 col-12 mb-0">
                                                    <label class="form-label" for="multicol-country">Décrivez le niveau de
                                                        satisfaction</label>
                                                    <textarea rows="2" name="satisfaction_description" class="form-control" required
                                                        @if (!is_null($data->closed_at)) readonly @endif>{{ $data->satisfaction_description != null ? $data->satisfaction_description : null }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        @can('DysEvaluation', $data)
                                            <div class="mb-0">
                                                <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#dysCompleteModal">
                                                    <i class="bx bx-check me-1"></i>
                                                    <span class="align-middle">Terminer ce Dysfonctionnement</span>
                                                </button>
                                            </div>
                                        @endcan
                                    </form>
                                    <div class="modal modal-top fade" id="evalCompleteModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTopTitle">Confirmation de
                                                        Fermeture!</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body">
                                                        <p class="card-text">
                                                            Souhaitez vous vraiment terminer l'évaluation des actions
                                                            correctives de ce dysfonctionnement ?
                                                            <b>Notez que vous ne pourrez plus modifier ce
                                                                dysfonctionnement en terme d'identification, de
                                                                planification etc..</b>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-label-secondary"
                                                        data-bs-dismiss="modal">Fermer</button>
                                                    <button id="confirmEvaluation"
                                                        class="btn btn-warning">Continuer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @can('DysEvaluation', $data)
                                        <div class="modal modal-top fade" id="dysCompleteModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalTopTitle">Confirmation de
                                                            Fermeture!</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card-body">
                                                            <p class="card-text">
                                                                Souhaitez vous vraiment terminer l'évaluation de ce
                                                                dysfonctionnement ?
                                                                <b>Notez que ceci clôturera définitivement ce
                                                                    dysfonctionnement.</b>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-label-secondary"
                                                            data-bs-dismiss="modal">Fermer</button>
                                                        <button id="btnCloseDys" class="btn btn-warning">Continuer</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/cleavejs/cleave-phone.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/jquery-repeater/jquery-repeater.js') !!}"></script>
    <script src="{!! url('assets/js/js/info.js') !!}"></script>
    <script src="{!! url('assets/js/js/forms-extras.js') !!}"></script>
    <script src="{!! url('assets/js/js/form-layouts.js') !!}"></script>
@endsection
