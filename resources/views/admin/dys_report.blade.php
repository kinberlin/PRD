@extends('admin.theme.main')
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
            <div class="input-wrapper my-3 input-group input-group-merge">
                <span class="input-group-text" id="basic-addon1"><i class="bx bx-search-alt bx-xs text-muted"></i></span>
                <input type="text" class="form-control form-control-lg"
                    placeholder="Entrez le code d'un dysfonctionnement" aria-label="Search"
                    aria-describedby="basic-addon1" />
            </div>
            <p class="text-center mb-0 px-3">
                ou alors contacter le DQ si difficulté survient
            </p>
        </div>
        <div class="row">
            <div class="card mb-4" style="margin-bottom: 25px;">
                <h5 class="card-header">Information sur la déclaration</h5>
                <form class="card-body">
                    <!--<hr class="my-4 mx-n4">
                                                                                                <h6> Info Supplementaires</h6>-->
                    @csrf
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
                            <h4 class="form-label text-center" style="font-size: 18px" for="basic-default-message">Status :
                                <span class="text-primary">{{ $data->status_id->name }}</span></h2>
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
                        <input type="text" value="{{ $data->enterprise }}  ({{ $data->site }})" class="form-control"
                            readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-last-name">Processus Concernés</label>
                        <input type="text" value="{{ implode(', ', $data->getCProcesses()->pluck('name')->toArray()) }}"
                            class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="multicol-last-name">Processus Impactés</label>
                        <input type="text" value="{{ implode(', ', $data->getIProcesses()->pluck('name')->toArray()) }}"
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
                        <input type="text" value="{{ $data->probabilities->name }}" class="form-control" readonly>
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
            <h3 class="card-header text-center text-primary"> Actions Correctives mise en place & Evaluations</h3>
        </div>
        <!-- Project Cards -->
        <div class="row g-4 mt-6">
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
                                        <h5 class="mb-1"><a href="javascript:;" class="h5">{{ $co->text }}</a>
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
                                            <li><a class="dropdown-item text-danger" href="javascript:void(0);"></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap">
                                <div class="bg-lighter p-2 rounded me-auto mb-3">
                                    <h6 class="mb-1">{!! str_replace(' ', '<br>', $co->created_by) !!} <span class="text-body fw-normal">.</span>
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
                                    <a href="{{ $co->proof }}" target="_blank">Voir la Preuve</a>
                                @endif
                                <small>{{ $co->progress * 100 }}%</small>
                            </div>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $co->progress * 100 }}%;"
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
                                                class="text-body fw-normal">{{ $eva->evaluation_criteria }}</span></h6>
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
                            @else
                                <p class="mb-0">Pas encore évaluée.</p>
                            @endif
                            <h6 class="mb-1">Date d'Evaluation : <span
                                    class="text-body fw-normal">{{ formatDateInFrench($eva->created_at, 'short') }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            @endforeach
            @foreach ( as )
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
                                    <h5 class="mb-1"><a href="javascript:;" class="h5 stretched-link">Social
                                            Banners</a>
                                    </h5>
                                    <div class="client-info d-flex align-items-center">
                                        <h6 class="mb-0 me-1">Client:</h6><span>Christian Jimenez</span>
                                    </div>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown z-2">
                                    <button type="button" class="btn dropdown-toggle hide-arrow p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="bx bx-dots-vertical-rounded"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Rename project</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View details</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add to favorites</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="javascript:void(0);">Leave
                                                Project</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-wrap">
                            <div class="bg-lighter p-2 rounded me-auto mb-3">
                                <h6 class="mb-1">$24.8k <span class="text-body fw-normal">/ $18.2k</span></h6>
                                <span>Total Budget</span>
                            </div>
                            <div class="text-end mb-3">
                                <h6 class="mb-1">Start Date: <span class="text-body fw-normal">14/2/21</span></h6>
                                <h6 class="mb-1">Deadline: <span class="text-body fw-normal">28/2/22</span></h6>
                            </div>
                        </div>
                        <p class="mb-0">We are Consulting, Software Development and Web Development Services.</p>
                    </div>
                    <div class="card-body border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="mb-1">All Hours: <span class="text-body fw-normal">380/244</span></h6>
                            <span class="badge bg-label-success ms-auto">28 Days left</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small>Task: 290/344</small>
                            <small>95% Completed</small>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 95%;" aria-valuenow="95"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 z-2">
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Vinnie Mostowy" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/5.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Allen Rieske" class="avatar avatar-sm pull-up">
                                        <img class="rounded-circle" src="../../assets/img/avatars/12.png" alt="Avatar">
                                    </li>
                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                        title="Julee Rossignol" class="avatar avatar-sm pull-up me-2">
                                        <img class="rounded-circle" src="../../assets/img/avatars/6.png" alt="Avatar">
                                    </li>
                                    <li><small class="text-muted">280 Members</small></li>
                                </ul>
                            </div>
                            <div class="ms-auto">
                                <a href="javascript:void(0);" class="text-body"><i class="bx bx-chat"></i> 15</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
                <div class="card mb-4">
            <h3 class="card-header text-center text-primary"> Réunions et participations</h3>
        </div>
        <!--/ Project Cards -->
    </div>
@endsection
@section('scriptContent')
@endsection
