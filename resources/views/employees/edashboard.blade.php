@extends('employees.theme.main')
@section('title')
    Tableau de bord
@endsection
@section('manualstyle')
    <style>
        .circle {
            width: 150px;
            /* Diamètre du cercle */
            height: 150px;
            /* Diamètre du cercle */
            border: 12px solid transparent;
            /* Contour initial transparent */
            border-radius: 50%;
            /* Pour obtenir un cercle */
            position: relative;
            animation: lightenBorder 2s linear infinite;
            /* Animation du contour */
        }

        @keyframes lightenBorder {
            0% {
                border-color: rgba(0, 128, 0, 1);
            }

            /* Vert opaque */
            50% {
                border-color: rgba(255, 255, 255, 0.5);
            }

            /* Blanc semi-transparent */
            100% {
                border-color: rgba(0, 128, 0, 1);
            }

            /* Retour au vert opaque */
        }

        #text {
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
            color: green;
            /* Couleur du texte */
        }
    </style>
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Hour chart  -->
        <div class="card bg-transparent shadow-none border-0 my-4">
            <div class="card-body row p-0 pb-3">
                <div class="col-12 col-md-8 card-separator">
                    <h3>Bienvenue, {{ Auth::user()->firstname }} 👋🏻 </h3>
                    <div class="col-12 col-lg-7">
                        <p>Vous trouverez ci-dessous, un résumé de vos données de congés actuelles pour suivre facilement
                            votre utilisation des congés et permissions.</p>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap gap-3 me-5">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class=" bg-label-primary p-2 rounded">
                                <i class='bx bx-badge bx-sm'></i>
                            </span>
                            <div class="content-right">
                                <p class="mb-0">PE Prises</p>
                                <h4 class="text-primary mb-0">
                                    {{ App\Models\Pme::where('matricule', Auth::user()->matricule)->where('status', 4)->sum('duration') * 24 }}h
                                </h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-info p-2 rounded">
                                <i class='bx bx-bulb bx-sm'></i>
                            </span>
                            <div class="content-right">
                                <p class="mb-0">PNE Prises</p>
                                <h4 class="text-info mb-0">
                                    {{ App\Models\Pne::where('matricule', Auth::user()->matricule)->where('status', 4)->sum('duration') }}
                                    Jours
                                </h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class='bx bx-check-circle bx-sm'></i>
                            </span>
                            <div class="content-right">
                                <p class="mb-0">Taux d'utilisation des congés </p>
                                @if (Auth::user()->holiday == 26)
                                    <h4 class="text-warning mb-0">0%</h4>
                                @else
                                    <h4 class="text-warning mb-0">{{ ((26 - Auth::user()->holiday) * 100) / 26 }}%</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 ps-md-3 ps-lg-5 pt-3 pt-md-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div>
                                <h5 class="mb-2">Congés Restants</h5>
                                <p class="mb-4">Solde totale</p>
                            </div>
                            <div class="time-spending-chart">
                                <h3 class="mb-2">{{ Auth::user()->holiday }}<span class="text-muted">j</span> 00<span
                                        class="text-muted">m</span>
                                </h3>
                            </div>
                        </div>
                        <div class="circle">
                            <p id="text">{{ Auth::user()->holiday }}J</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Hour chart End  -->

        <!-- Topic and Instructors -->
        <div class="row mb-4 g-4">
            <div class="col-12 col-xl-8 mb-4 order-5 order-xxl-0">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="card-title mb-0">
                            <h5 class="m-0">Vue rapides sur vos demandes cette année</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-none d-lg-flex vehicles-progress-labels mb-3">
                            <div class="vehicles-progress-label on-the-way-text" style="width: 35%;">PE (Permissions
                                Exceptionelles)</div>
                            <div class="vehicles-progress-label unloading-text" style="width: 30%;">Congés</div>
                            <div class="vehicles-progress-label loading-text" style="width: 35%;">PNE (Permissions non
                                exceptionelles)</div>
                        </div>
                        <div class="vehicles-overview-progress progress rounded-2 mb-3" style="height: 46px;">
                            <div class="progress-bar fs-big fw-medium text-start bg-lighter text-body px-1 px-lg-4 rounded-start shadow-none"
                                role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0"
                                aria-valuemax="100">{{ count($pme) }}</div>
                            <div class="progress-bar fs-big fw-medium text-start bg-primary px-1 px-lg-4 shadow-none"
                                role="progressbar" style="width: 35%" aria-valuenow="30" aria-valuemin="0"
                                aria-valuemax="100">{{ count($holiday) }}</div>
                            <div class="progress-bar fs-big fw-medium text-start text-bg-info px-1 px-lg-4 rounded-end shadow-none"
                                role="progressbar" style="width: 30%" aria-valuenow="35" aria-valuemin="0"
                                aria-valuemax="100">{{ count($pne) }}</div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4 col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Mes supérieurs hierachiques</h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless border-top">
                            <thead class="border-bottom">
                                <tr>
                                    <th>
                                        @if ($dg->id == Auth::user()->id)
                                            Chef de Département
                                        @else
                                            Managers
                                        @endif
                                    </th>
                                    <!--th class="text-end">Rappeller</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dg != null && Auth::user()->id != \App\Models\Enterprise::where('id', Auth::user()->enterprise)->value('manager'))
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center mt-lg-4">
                                                <div class="avatar me-3">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-success">{{ $dg->firstname[0] . $dg->lastname[0] }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1 text-truncate">
                                                        {{ $dg->firstname . ' ' . $dg->lastname }} @if ($dg->id == Auth::user()->id)
                                                            (C'est Vous!)
                                                        @endif
                                                    </h6>
                                                    <small class="text-truncate text-muted">
                                                        @if (empty($dg->poste))
                                                            Poste Inconnu
                                                        @else
                                                            {{ $dg->poste }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center mt-lg-4">
                                                <div class="avatar me-3">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-success">{{ Auth::user()->firstname[0] . Auth::user()->lastname[0] }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1 text-truncate">
                                                        {{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}
                                                        @if (Auth::user()->id == Auth::user()->id)
                                                            (C'est Vous!)
                                                        @endif
                                                    </h6>
                                                    <small class="text-truncate text-muted">
                                                        @if (empty(Auth::user()->poste))
                                                            Poste Inconnu
                                                        @else
                                                            {{ Auth::user()->poste }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($managers != null)
                                    @foreach ($managers as $m)
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-start align-items-center mt-lg-4">
                                                    <div class="avatar me-3">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-success">{{ $m->firstname[0] . $m->lastname[0] }}</span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-1 text-truncate">
                                                            {{ $m->firstname . ' ' . $m->lastname }} @if ($m->id == Auth::user()->id)
                                                                (C'est Vous!)
                                                            @endif
                                                        </h6>
                                                        <small class="text-truncate text-muted">
                                                            @if (empty($m->poste))
                                                                Poste Inconnu
                                                            @else
                                                                {{ $m->poste }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($directM != null && $directM->id != Auth::user()->id)
                                    <tr>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center mt-lg-4">
                                                <div class="avatar me-3">
                                                    <span
                                                        class="avatar-initial rounded-circle bg-success">{{ $directM->firstname[0] . $directM->lastname[0] }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1 text-truncate">
                                                        {{ $directM->firstname . ' ' . $directM->lastname }}@if ($directM->id == Auth::user()->id)
                                                            (C'est Vous!)
                                                        @endif
                                                    </h6>
                                                    <small class="text-truncate text-muted">
                                                        @if (empty($directM->poste))
                                                            Poste Inconnu
                                                        @else
                                                            {{ $directM->poste }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-3 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="bg-label-primary rounded-3 text-center mb-3 pt-4">
                            <img class="img-fluid w-60"
                                src="../../assets/img/illustrations/sitting-girl-with-laptop-dark.png"
                                alt="Card girl image" />
                        </div>
                        @if ($permissionInfo['nearestEndDate'] == null)
                            <h4 class="mb-2 pb-1">Vous n'êtes pas actuellement en congés/sur permission</h4>
                        @else
                            <h4 class="mb-2 pb-1">Vous êtes actuellement en congés/sur permission</h4>
                        @endif
                        <p class="small">Ici, s'affichent les informations relatifs a si vous avez actuellement une
                            demande de congé ou de permession en cours.</p>

                        <div class="row mb-3 g-3">
                            <div class="col-6">
                                <div class="d-flex">
                                    <div class="avatar flex-shrink-0 me-2">
                                        <span class="avatar-initial rounded bg-label-primary"><i
                                                class="bx bx-calendar-exclamation bx-sm"></i></span>
                                    </div>
                                    <div>
                                        @if ($permissionInfo['nearestEndDate'] != null)
                                            <h6 class="mb-1"
                                                style="overflow-wrap: break-word; word-wrap: break-word; word-break: break-word;">
                                                {{ \Carbon\Carbon::parse($permissionInfo['nearestEndDate'])->format('d-m-Y H:i:s') }}
                                            </h6>
                                        @else
                                            <h6 class="mb-1 text-nowrap">Aucune Date</h6>
                                        @endif
                                        <small>Date de Fin</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex">
                                    <div class="avatar flex-shrink-0 me-2">
                                        <span class="avatar-initial rounded bg-label-primary"><i
                                                class="bx bx-time-five bx-sm"></i></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 text-nowrap">{{ $permissionInfo['differenceInHours'] }} H</h6>
                                        <small>Restants</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-primary w-100">Besoin de conseil ?</a>
                    </div>
                </div>
            </div>
            <!-- Début de l'Historique-->
            <div class="col-12 col-xl-9 col-md-6">
                <div class="table-responsive mb-3">
                    <table id="datatables-orders"
                        class="table table-striped datatables-basic table border-top dataTable no-footer dtr-column">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Date de<br> soumission</th>
                                <th>Date de<br> Début</th>
                                <th>Date <br>de Fin</th>
                                <th>Durée <br>Souhaitée</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sortedCollection as $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->created_at)->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->begin)->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($d->end)->format('d-m-Y H:i:s') }}</td>
                                    <td>{{ $d->duration }}J</td>
                                    <td>{{ $status->where('id', $d->status)->value('name') }}</td>
                                    <td>
                                        @if ($d instanceof \App\Models\Pme)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detailpme{{ $d->id }}">
                                                Détails
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="detailpme{{ $d->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Détaille de la
                                                                Demande</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($d->status == 1)
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-dark mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_pmev = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('pme', $d->id)
                                                                                    ->where('status', 1)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_pmev != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de PE est présentement en attente
                                                                                de validation par : M.
                                                                                {{ $_pmev->firstname . ' ' . $_pmev->lastname }}
                                                                                (@if (empty($_pmev->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_pmev->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @elseif ($d->status == 3)
                                                             @php
                                                                
                                                                    $reasons = \App\Models\Validation::where(
                                                                        'pme',
                                                                        $d->id,
                                                                    )
                                                                        ->where('status', 3)
                                                                        ->get()
                                                                        ->first()->reasons;
                                                                @endphp
                                                                <div class="mb-3 row">
                                                                    <div class="col-md-12">
                                                                        <label for="html5-text-input"
                                                                            style="color: red; font-size: 20px"
                                                                            class="col-md-12 col-form-label">Motif de Rejet
                                                                            : <span
                                                                                style="color: indigo ; font-family: 'Courier New', Courier, monospace">{{ $reasons }}
                                                                            </span> </label>
                                                                    </div>
                                                                </div>
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-danger mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_pmev = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('pme', $d->id)
                                                                                    ->where('status', 3)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_pmev != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de PE a été rejeté par : M.
                                                                                {{ $_pmev->firstname . ' ' . $_pmev->lastname }}
                                                                                (@if (empty($_pmev->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_pmev->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-success mb-0"
                                                                    style="text-align: center" role="alert">
                                                                    <span
                                                                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Cette
                                                                        demande de PE a été validé de bout en
                                                                        bout.</span>
                                                                </div>
                                                            @endif
                                                            <div class="mb-3 row">

                                                                <div class="col-md-8">
                                                                    <label for="html5-text-input"
                                                                        class="col-md-3 col-form-label"
                                                                        data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                        data-bs-placement="right" data-bs-html="true"
                                                                        data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Si vous ne trouvez pas ci-dessous, le motif qui vous concerne, alors il ne s'agit peut-être pas d'une Permission Exceptionelle.</span>"
                                                                        aria-describedby="tooltip732616">Motif de demande

                                                                        ?</label>
                                                                    <div class="col-md-8">
                                                                        <input class="form-control" type="text"
                                                                            value="{{ $typepme->where('id', $d->type)->first()->name }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <label for="html5-text-input"
                                                                        class="col-md-4 col-form-label">Durée Maximale (J)
                                                                        :
                                                                        {{ $typepme->where('id', $d->type)->first()->duration }}
                                                                        Jours</label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row">
                                                                <label class="col-md-2 col-form-label">Durée souhaitez
                                                                </label>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" type="text"
                                                                        value="{{ $d->duration }} J" readonly>
                                                                </div>
                                                                <label class="col-md-2 col-form-label">Reste sur Congé
                                                                </label>
                                                                <div class="col-md-4">
                                                                    <input class="form-control" type="text"
                                                                        value="{{ $d->rest }} J" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row" id="divferier">
                                                                <label class="col-md-2 col-form-label">Jours feriés
                                                                    ?</label>
                                                                <div class="col-md-9">
                                                                    @if (count($ferier->where('pme', $d->id)) > 0)
                                                                        @foreach ($ferier->where('pme', $d->id) as $d)
                                                                            <label
                                                                                class="col-md-3 col-form-label">{{ \Carbon\Carbon::parse($d->dates)->format('d-m-Y H:i:s') }}</label>
                                                                        @endforeach
                                                                    @else
                                                                        <label class="col-md-9 col-form-label">Aucun Jour
                                                                            ferier n'a été renseigner lors de la soumission
                                                                            de cette demande.</label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="formFile" class="form-label"
                                                                    data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                    data-bs-placement="right" data-bs-html="true"
                                                                    data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Veuillez fournir les documents justificatifs scanné et mis sous format pdf  dans un seul document pdf de preference. Cela facilitera le traitement de votre demande.</span>"
                                                                    aria-describedby="tooltip732616">Voir la Piece
                                                                    Jointe</label>
                                                                <a href="{{ $d->pj }}" class="form-control"
                                                                    style="text-align: center" target="_blank"> Cliquer
                                                                    ici </a>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Notes supplémentaire</label>
                                                                <textarea class="form-control" rows="3" required readonly>{{ $d->description }}</textarea>
                                                            </div>
                                                            @if ($d->status == 3)

                                                               
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-label-secondary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($d instanceof \App\Models\Pne)
                                            <!-- Modal -->
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detailpne{{ $d->id }}">
                                                Détails
                                            </button>
                                            <div class="modal fade" id="detailpne{{ $d->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Détaille de la
                                                                Demande</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($d->status == 1)
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-dark mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_pnev = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('pne', $d->id)
                                                                                    ->where('status', 1)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_pnev != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de PNE est présentement en attente
                                                                                de validation par : M.
                                                                                {{ $_pnev->firstname . ' ' . $_pnev->lastname }}
                                                                                (@if (empty($_pnev->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_pnev->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @elseif ($d->status == 3)
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-danger mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_pnev = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('pne', $d->id)
                                                                                    ->where('status', 3)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_pnev != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de PNE a été rejeté par : M.
                                                                                {{ $_pnev->firstname . ' ' . $_pnev->lastname }}
                                                                                (@if (empty($_pnev->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_pnev->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-success mb-0"
                                                                    style="text-align: center" role="alert">
                                                                    <span
                                                                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Cette
                                                                        demande de PNE a été validé de bout en
                                                                        bout.</span>
                                                                </div>
                                                            @endif
                                                            <div class="mb-3 row">
                                                                <div class="col-md-8">
                                                                    <label for="html5-text-input"
                                                                        class="col-md-3 col-form-label"
                                                                        data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                        data-bs-placement="right" data-bs-html="true"
                                                                        data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Si vous ne trouvez pas ci-dessous, le motif qui vous concerne, alors il ne s'agit peut-être pas d'une Permission Exceptionelle.</span>"
                                                                        aria-describedby="tooltip732616">SOLDE A DEDUIRE

                                                                        ?</label>
                                                                    <div class="col-md-5">
                                                                        <input class="form-control" type="text"
                                                                            value="{{ $typepne->where('id', $d->type)->first()->name }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="col-md-6 col-form-label">Durée
                                                                        <br>souhaitez : <span
                                                                            style="color: red">{{ $d->duration }} J
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row" id="divferier">
                                                                <label class="col-md-2 col-form-label">Jours feriés
                                                                    ?</label>
                                                                <div class="col-md-9">
                                                                    @if (count($ferier->where('pne', $d->id)) > 0)
                                                                        @foreach ($ferier->where('pne', $d->id) as $f)
                                                                            <label
                                                                                class="col-md-3 col-form-label">{{ \Carbon\Carbon::parse($f->dates)->format('d-m-Y H:i:s') }}</label>
                                                                        @endforeach
                                                                    @else
                                                                        <label class="col-md-9 col-form-label">Aucun Jour
                                                                            ferier n'a été renseigner lors de la soumission
                                                                            de cette demande.</label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="formFile" class="form-label"
                                                                    data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                    data-bs-placement="right" data-bs-html="true"
                                                                    data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Veuillez fournir les documents justificatifs scanné et mis sous format pdf  dans un seul document pdf de preference. Cela facilitera le traitement de votre demande.</span>"
                                                                    aria-describedby="tooltip732616">Voir la Piece
                                                                    Jointe</label>
                                                                <a href="{{ $d->pj }}" class="form-control"
                                                                    style="text-align: center" target="_blank"> Cliquer
                                                                    ici </a>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Notes supplémentaire</label>
                                                                <textarea class="form-control" rows="3" required readonly>{{ $d->description }}</textarea>
                                                            </div>
                                                            @if ($d->status == 3)
                                                                @php
                                                                    $reasons = \App\Models\Validation::where(
                                                                        'pne',
                                                                        $d->id,
                                                                    )
                                                                        ->where('status', 3)
                                                                        ->get()
                                                                        ->first()->reasons;
                                                                @endphp
                                                                <div class="mb-3 row">
                                                                    <div class="col-md-12">
                                                                        <label for="html5-text-input"
                                                                            style="color: red; font-size: 20px"
                                                                            class="col-md-12 col-form-label">Motif de Rejet
                                                                            : <span
                                                                                style="color: indigo ; font-family: 'Courier New', Courier, monospace">{{ $reasons }}
                                                                            </span> </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-label-secondary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif ($d instanceof \App\Models\Holliday)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#detailholliday{{ $d->id }}">
                                                Détails
                                            </button>
                                            <div class="modal fade" id="detailholliday{{ $d->id }}"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Détaille de la
                                                                Demande</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if ($d->status == 1)
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-dark mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_holv = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('holliday', $d->id)
                                                                                    ->where('status', 1)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_holv != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de Congé est présentement en attente
                                                                                de validation par : M.
                                                                                {{ $_holv->firstname . ' ' . $_holv->lastname }}
                                                                                (@if (empty($_holv->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_holv->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @elseif ($d->status == 3)
                                                                <div class="mb-3 row">
                                                                    <div class="alert alert-danger mb-0"
                                                                        style="text-align: center" role="alert">
                                                                        @php
                                                                            $_holv = \App\Models\Users::where(
                                                                                'id',
                                                                                $validations
                                                                                    ->where('holliday', $d->id)
                                                                                    ->where('status', 3)
                                                                                    ->first()->validator,
                                                                            )
                                                                                ->get()
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($_holv != null)
                                                                            <span
                                                                                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Votre
                                                                                demande de Congé a été rejeté par : M.
                                                                                {{ $_holv->firstname . ' ' . $_holv->lastname }}
                                                                                (@if (empty($_holv->post))
                                                                                    Poste Inconnu
                                                                                @else
                                                                                    {{ $_holv->poste }}
                                                                                @endif)
                                                                            </span>
                                                                        @else
                                                                            Une erreur grave portant sur l'authenticité des
                                                                            données dans notre systeme nous empêche de
                                                                            tracer les validations de votre demande.
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-success mb-0"
                                                                    style="text-align: center" role="alert">
                                                                    <span
                                                                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif ; font-size: 18px;">Cette
                                                                        demande de congé a été validé de bout en
                                                                        bout.</span>
                                                                </div>
                                                            @endif
                                                            <div class="mb-3 row">
                                                                <div class="col-md-8">
                                                                    <label for="html5-text-input"
                                                                        class="col-md-3 col-form-label"
                                                                        data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                        data-bs-placement="right" data-bs-html="true"
                                                                        data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Mode de remplacement lors mon congé.</span>"
                                                                        aria-describedby="tooltip732616">Comment je
                                                                        souhaite me faire remplacer ? </label>
                                                                    <div class="col-md-5">
                                                                        <input class="form-control" type="text"
                                                                            value="{{ $substitution->where('id', $d->substitution)->first()->name }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="col-md-6 col-form-label">Durée : <span
                                                                            style="color: blue; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; font-size: 24px">
                                                                            {{ $d->duration }} J </span></label>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3 row" id="divferier">
                                                                <label class="col-md-2 col-form-label">Jours feriés
                                                                    ?</label>
                                                                <div class="col-md-9">
                                                                    @if (count($ferier->where('holliday', $d->id)) > 0)
                                                                        @foreach ($ferier->where('holliday', $d->id) as $f)
                                                                            <label
                                                                                class="col-md-3 col-form-label">{{ \Carbon\Carbon::parse($f->dates)->format('d-m-Y H:i:s') }}</label>
                                                                        @endforeach
                                                                    @else
                                                                        <label class="col-md-9 col-form-label">Aucun Jour
                                                                            ferier n'a été renseigner lors de la soumission
                                                                            de cette demande.</label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="formFile" class="form-label"
                                                                    data-bs-toggle="tooltip" data-bs-offset="0,6"
                                                                    data-bs-placement="right" data-bs-html="true"
                                                                    data-bs-original-title="<i class='bx bx-trending-up bx-xs' ></i> <span>Veuillez fournir les documents justificatifs scanné et mis sous format pdf  dans un seul document pdf de preference. Cela facilitera le traitement de votre demande.</span>"
                                                                    aria-describedby="tooltip732616">Voir la Piece
                                                                    Jointe</label>
                                                                <a href="{{ $d->pj }}" class="form-control"
                                                                    style="text-align: center" target="_blank"> Cliquer
                                                                    ici </a>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Détails sur le moyen de
                                                                    remplacement mis en place</label>
                                                                <textarea class="form-control" rows="3" required readonly>{{ $d->description }}</textarea>
                                                            </div>
                                                            @if ($d->status == 3)
                                                                @php
                                                                    $reasons = \App\Models\Validation::where(
                                                                        'holliday',
                                                                        $d->id,
                                                                    )
                                                                        ->where('status', 3)
                                                                        ->get()
                                                                        ->first()->reasons;
                                                                @endphp
                                                                <div class="mb-3 row">
                                                                    <div class="col-md-12">
                                                                        <label for="html5-text-input"
                                                                            style="color: red; font-size: 20px"
                                                                            class="col-md-12 col-form-label">Motif de Rejet
                                                                            : <span
                                                                                style="color: indigo ; font-family: 'Courier New', Courier, monospace">{{ $reasons }}
                                                                            </span> </label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-label-secondary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <!--  Fin de l'historique-->

    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/js/app-academy-dashboard.js') !!}"></script>
    <script>
        const circle = document.querySelector('.circle');
        let opacity = 1; // Opacité initiale

        function lightenBorder() {
            opacity -= 0.01; // Diminution progressive de l'opacité
            if (opacity <= 0) {
                opacity = 0;
                clearInterval(interval);
            }
            circle.style.borderColor = `rgba(255, 255, 255, ${opacity})`; // Couleur blanche avec opacité variable
        }

        const interval = setInterval(lightenBorder, 20); // Appel de la fonction toutes les 20 ms

        document.addEventListener("DOMContentLoaded", function() {
            // Datatables Orders
            $("#datatables-orders").DataTable({
                "paging": true,
                "pageLength": 4,
                "dom": 'Bfrtip', // Show buttons (B) for export
                "buttons": [
                    'excel' // Add export button for Excel
                ],
                responsive: true,
                aoColumnDefs: [{
                    bSortable: false,
                    aTargets: [-1]
                }]
            });
        });
    </script>
@endsection
