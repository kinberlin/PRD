@extends('rq.theme.main')
@section('title')
    Rapport complet sur un dysfonctionnement
@endsection
@section('manualstyle')
    <link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-faq.css') !!}" />
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="faq-header d-flex flex-column justify-content-center align-items-center h-px-300 position-relative"
            style="margin-bottom: 25px;">
            <img src="{{ url('assets/img/pages/header.png') }}" class="scaleX-n1-rtl faq-banner-img" alt="background image" />
            <h3 class="text-center">Plus d'info sur un Dysfonctionnement signalé ?</h3>
            <form class="input-wrapper my-3 input-group input-group-merge" method="POST"
                action="{{ route('rq.dysfunction.report.post') }}">
                @csrf
                <span class="input-group-text" id="basic-addon1"><i
                            class="text-muted"></i></span>
                <input type="text" name="code" class="form-control form-control-lg"
                    placeholder="Entrez le code d'un dysfonctionnement" aria-label="Search"
                    aria-describedby="basic-addon1" />
                <button type="submit" class="btn btn-info"><span class="input-group-text" id="basic-addon1"><i
                            class="bx bx-search-alt bx-xs text-muted"></i></span></button>
            </form>
            <p class="text-center mb-0 px-3">
                ou alors contacter le DQ si difficulté survient
            </p>
        </div>
        @if (!is_null($data))
            <div class="card mb-4">
                <h5 class="card-header text-center text-primary"> Dysfonctionnement No.{{ $data->code }}</h5>
            </div>
            <div class="row">
                <div class="card mb-4" style="margin-bottom: 25px;">
                    <h5 class="card-header">Information sur la déclaration</h5>
                    <form class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="basic-default-fullname">Noms</label>
                                <input type="text" class="form-control" id="basic-default-fullname"
                                    value={{ $data->emp_signaling }} readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="basic-default-company">Matricule</label>
                                <input type="text" class="form-control" id="basic-default-company"
                                    value="{{ $data->emp_matricule }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="basic-default-email">Contact</label>
                                <div class="input-group input-group-merge">
                                    <input type="text" id="basic-default-email" class="form-control"
                                        value="{{ $data->emp_email }}" aria-label="john.doe"
                                        aria-describedby="basic-default-email2">
                                    <span class="input-group-text" id="basic-default-email2">@</span>
                                </div>
                                <div class="form-text"> Extras</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date d'enregistrement sur PRD</label>
                                <input type="text" class="form-control"
                                    value="{{ formatDateInFrench($data->created_at, 'complete') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="basic-default-message">Date de Constat</label>
                                <input type="text" class="form-control"
                                    value="{{ formatDateInFrench($data->occur_date, 'complete') }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="basic-default-message">Description</label>
                                <textarea id="basic-default-message" class="form-control" placeholder="Aucune description n'a été faites" readonly> {{ $data->description }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <h4 class="form-label text-center" style="font-size: 18px" for="basic-default-message">
                                    Statut :
                                    @if ($data->status == 6)
                                        <span class="text-primary">{{ $data->status_id->name }}</span> | Le :
                                            {{ formatDateInFrench($data->closed_at, 'short') }} | Par :
                                            <span class="text-primary">{{ $data->closed_by }}</span>
                                    @else
                                        <span class="text-primary">{{ $data->status_id->name }} </span>
                                    @endif
                            </div>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
                <h5 class="card-header">Information d'Identification </h5>
                <form class="card-body" method="POST">
                    <!--<hr class="my-4 mx-n4">
                                                            <h6> Info Supplementaires</h6>-->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Entreprise & Site Concerné</label>
                            <input type="text" value="{{ $data->enterprise }}  ({{ $data->site }})"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Processus Concernés</label>
                            <input type="text"
                                value="{{ implode(', ', $data->getCProcesses()->pluck('name')->toArray()) }}"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Processus Impactés</label>
                            <input type="text"
                                value="{{ implode(', ', $data->getIProcesses()->pluck('name')->toArray()) }}"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Gravité</label>
                            <input type="text" value="{{ $data->gravity }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Catégorie</label>
                            <input type="text" value="{{ $data->origin }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Probabilité</label>
                            <input type="text" value="{{ !is_null($data->probabilities) ? $data->probabilities->name : ''}}" class="form-control"
                                readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="multicol-last-name">Coût de non-qualité</label>
                            <input type="text" value="{{ formatNumber($data->cost) }}" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Responsable(s) probable(s) de l'incident</label>
                            <input type="text"
                                value="@if (empty($data->cause)) Aucun Responsable Identifié @else {{ $data->cause }} @endif"
                                name="cause" class="form-control"
                                placeholder="Le(s) Nom(s) de(s) Responsable(s) & matricule(s) si possible" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div class="card mb-4">
                <h3 class="card-header text-center text-primary"> Actions correctives mises en place et évaluations</h3>
            </div>
            <!-- Task Cards -->
            <div class="row g-4 mt-6 mb-4">
                @foreach ($corrections as $co)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar me-3">
                                            <img src="../../assets/img/icons/brands/social-label.png" alt="Avatar"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="me-2">
                                            <h5 class="mb-1"><a href="javascript:;"
                                                    class="h5">{{ $co->text }}</a>
                                            </h5>
                                            <div class="client-info d-flex align-items-center">
                                                <h6 class="mb-0 me-1">Processus:</h6>
                                                <span>{{ $processes->where('id', $co->process)->first()->surfix }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="dropdown z-2">
                                            <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="bx bx-dots-vertical-rounded"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0);"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="bg-lighter p-2 rounded me-auto mb-3">
                                        <h6 class="mb-1">{!! str_replace(' ', '<br>', $co->created_by) !!} <span
                                                class="text-body fw-normal">.</span>
                                        </h6>
                                        <span>RQ</span>
                                    </div>
                                    <div class="text-end mb-3">
                                        <h6 class="mb-1">Début: <span
                                                class="text-body fw-normal">{{ formatDateInFrench($co->start_date, 'short') }}</span>
                                        </h6>
                                        <h6 class="mb-1">Fin: <span
                                                class="text-body fw-normal">{{ formatDateInFrench(\Carbon\Carbon::parse($co->start_date)->addDays($co->duration), 'short') }}</span>
                                        </h6>
                                    </div>
                                </div>
                                <p class="mb-0" data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                    data-bs-placement="top" title="{{ $co->description }}">
                                    {{ substr($co->description, 0, 100) . '...' }}</p>
                            </div>
                            <div class="card-body border-top">
                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-1">Durée: <span class="text-body fw-normal">{{ $co->duration }}
                                            Jours</span></h6>
                                    <span class="badge bg-label-success ms-auto">
                                        @if (\Carbon\Carbon::parse($co->start_date)->addDays($co->duration) < now())
                                            0
                                        @else
                                            {{ now()->diffInDays(\Carbon\Carbon::parse($co->start_date)->addDays($co->duration)) }}
                                        @endif Jours
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    @if (is_null($co->proof))
                                        <small>Aucune Preuve</small>
                                    @else
                                        <a href="{{ Storage::url($co->proof) }}" target="_blank">Voir la Preuve</a>
                                    @endif
                                    <small>{{ $co->progress * 100 }}%</small>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $co->progress * 100 }}%;"
                                        aria-valuenow="{{ $co->progress * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                                <hr>
                                @php
                                    $eva = $evaluations->where('task', $co->id)->first();
                                @endphp
                                @if (!is_null($eva))
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="bg-lighter p-2 rounded me-auto mb-3">
                                            <h6 class="mb-1"><span
                                                    class="text-body fw-normal">{{ $eva->evaluation_criteria }}</span>
                                            </h6>
                                        </div>
                                        <div class="text-end mb-3">
                                            <h6 class="mb-1">Satisfaction : <span
                                                    class="text-body fw-normal">{{ $eva->satisfaction }}/100</span>
                                            </h6>
                                            <h6 class="mb-1">Complétude : <span
                                                    class="text-body fw-normal">{{ $eva->completion }}/100</span>
                                            </h6>
                                        </div>
                                    </div>
                                <h6 class="mb-1">Date d'Evaluation : <span
                                        class="text-body fw-normal">{{ formatDateInFrench($eva->created_at, 'short') }}</span>
                                </h6>
                                @else
                                    <p class="mb-0">Pas encore évaluée.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!--/ Task Cards -->
            <div class="card mb-4">
                <h3 class="card-header text-center text-primary"> Réunions et participations</h3>
            </div>
            <!--/ Invitation Cards -->
            <div class="row g-4 mt-6 mb-4">
                @foreach ($invitations as $i)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-start">
                                    <div class="d-flex align-items-start">
                                        <div class="avatar me-3">
                                            <img src="../../assets/img/icons/brands/team.png" alt="Teams"
                                                class="rounded-circle" />
                                        </div>
                                        <div class="me-2">
                                            <h5 class="mb-1"><a href="javascript:;"
                                                    class="h5">{{ $i->object }}(No.{{ $i->id }})</a>
                                            </h5>
                                            <div class="client-info d-flex align-items-center">
                                                <h6 class="mb-0 me-1">Du :</h6>
                                                <span>{{ formatDateInFrench($i->odates, 'short') }}</span>
                                            </div>
                                            <div class="client-info d-flex align-items-center">
                                                <h6 class="mb-0 me-1">Initier le :</h6>
                                                <span>{{ formatDateInFrench($i->created_at, 'short') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="dropdown z-2">
                                            <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="bx bx-dots-vertical-rounded"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a>
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);"></a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="javascript:void(0);"></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $_internali = $i->getInternalInvites();
                                $_externali = json_decode($i->external_invites, true);
                                $_participation = $i->getParticipants();
                            @endphp
                            <div class="card-body">
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="bg-lighter p-2 rounded me-auto mb-3">
                                        <h6 class="mb-1">{{ count($_internali) }} <span class="text-body fw-normal">|
                                                {{ count($_externali) }}</span></h6>
                                        <span>Interne | Externe</span>
                                    </div>
                                    <div class="text-end mb-3">
                                        <h6 class="mb-1">Début : <span
                                                class="text-body fw-normal">{{ $i->begin }}</span></h6>
                                        <h6 class="mb-1">Fin : <span
                                                class="text-body fw-normal">{{ $i->end }}</span>
                                        </h6>
                                    </div>
                                </div>
                                <p class="mb-0">Description : {{ $i->description }}</p><br>
                                <p class="mb-0">Motif : {{ $i->motif }}</p>
                            </div>
                            <div class="card-body border-top">

                                <div class="d-flex align-items-center mb-3">
                                    <h6 class="mb-1">Invités / Participants :<span
                                            class="text-body fw-normal">{{ count($_internali) + count($_externali) }}/{{ count($_participation) }}</span>
                                    </h6>
                                    @if (is_null($i->closed_at))
                                        <span class="badge bg-label-success ms-auto">En Cours</span>
                                    @else
                                        <span class="badge bg-label-warning ms-auto">Terminée le
                                            {{ formatDateInFrench($i->closed_at, 'short') }}</span>
                                    @endif
                                </div>
                                <button type="button" class="mb-0 mt-3 btn btn-info" type="button"
                                    class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#invitedetail{{ $i->id }}">
                                    Données sur les invités
                                </button>
                                <div class="modal fade" id="invitedetail{{ $i->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <form method="POST" class="modal-content"
                                            action="{{ route('invitation.participation', $i->id) }}">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalCenterTitle">Invitations Reunion :
                                                    No. #{{ $i->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                @csrf
                                                <div class="table-responsive">
                                                    <table class="table border-top table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-nowrap">Invités</th>
                                                                <th class="text-nowrap text-center">✅ Accepté
                                                                </th>
                                                                <th class="text-nowrap text-center">❌ Rejeté
                                                                </th>
                                                                <th class="text-nowrap text-center">👩🏻‍💻
                                                                    Présent</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($_internali as $inn)
                                                                @php
                                                                    $u = $users
                                                                        ->where('matricule', $inn->matricule)
                                                                        ->first();
                                                                    $p = $i->findParticipantByMatricule(
                                                                        $inn->matricule,
                                                                    );
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-nowrap">
                                                                        {{ $u != null ? $u->firstname . ' (' . $u->matricule . ')' : 'Utilisateur Introuvable.' }}
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                @if ($inn->decision == 'Confirmer') checked @endif
                                                                                disabled />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                @if ($inn->decision == 'Rejeté') checked @endif
                                                                                disabled />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" name="participant[]"
                                                                                value="{{ $inn->matricule }}"
                                                                                @if ($p != null) checked @endif
                                                                                disabled />
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            @foreach ($_externali as $e)
                                                                @php
                                                                    $p = $i->findParticipantByMatricule($e);
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-nowrap">{{ $e }}
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" disabled />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" disabled />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="form-check d-flex justify-content-center">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" name="participantext[]"
                                                                                value="{{ $e }}"
                                                                                @if ($p != null) checked @endif
                                                                                disabled />
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-label-secondary"
                                                    data-bs-dismiss="modal">Fermer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!--/ Invitation Cards -->
        @else
            <div class="card mb-4">
                <h5 class="card-header text-center text-primary">Veuillez entrer un code ou un identifiant de dysfonctionnement valide.</h5>
            </div>
        @endif
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/ui-popover.js') !!}"></script>
@endsection
