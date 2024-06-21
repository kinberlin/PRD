@extends('admin.theme.main')
@section('title')
    Tableau de Bord | Administrateur
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12 col-lg-4 mb-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-8">
                            <div class="card-body">
                                <h6 class="card-title mb-1 text-nowrap">
                                    Salut, M. {{ Auth::user()->firstname }}!
                                </h6>
                                <small class="d-block mb-3 text-nowrap">Administrateur PRD</small>

                                <h5 class="card-title text-primary mb-1">{{ formatNumber(App\Models\Users::count()) }}</h5>
                                <small class="d-block mb-4 pb-1 text-muted">Utilisateurs</small>

                                <a href="{{ route('admin.employee') }}" class="btn btn-sm btn-primary">Plus d'Info</a>
                            </div>
                        </div>
                        <div class="col-4 pt-3 ps-0">
                            <img src="{{ url('assets/img/illustrations/prize-light.png') }}" width="90" height="140"
                                class="rounded-start" alt="Utilisateurs" />
                        </div>
                    </div>
                </div>
            </div>
            <!-- New Visitors & Activity -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-body row g-4">
                        <div class="col-md-6 pe-md-4 card-separator">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <h5 class="mb-0">Nouveaux Signalements</h5>
                                <small>La semaine derniere</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-auto">
                                    @php
                                        $alldys = \App\Models\Dysfunction::whereYear(
                                            'created_at',
                                            \Carbon\Carbon::now()->year,
                                        )->get();
                                        $alldystype = \App\Models\DysfunctionType::all();
                                        $allgravity = \App\Models\Gravity::all();
                                        $allsite = \App\Models\Site::all();
                                        $allprocess = \App\Models\Processes::all();
                                        $allprobability = \App\Models\Probability::all();
                                        $created = $alldys
                                            ->whereBetween('created_at', [
                                                Carbon\Carbon::now()->subWeek()->startOfWeek(),
                                                Carbon\Carbon::now()->subWeek()->endOfWeek(),
                                            ])
                                            ->count();
                                        $rejected = $alldys
                                            ->whereBetween('created_at', [
                                                Carbon\Carbon::now()->subWeek()->startOfWeek(),
                                                Carbon\Carbon::now()->subWeek()->endOfWeek(),
                                            ])
                                            ->where('status', 3)
                                            ->count();
                                        $percentage = $rejected > 0 ? ($rejected * 100) / $created : 0;
                                        //sites with the highest occurances
                                        $sites = '';
                                        $siteCounts = $alldys->groupBy('site')->map(function ($group) {
                                            return $group->count();
                                        });

                                        // Step 2: Find the maximum count
                                        $maxCount = $siteCounts->max();

                                        // Step 3: Filter the first sites that have the maximum count
                                        $mostFrequentSites = $siteCounts->filter(function ($count) use ($maxCount) {
                                            return $count == $maxCount;
                                        });

                                        // Output the results
                                        foreach ($mostFrequentSites as $s => $count) {
                                            $sites .= $s . ' - Compte: ' . $count . ' Signalement ;';
                                        }
                                        // Calculate the value and get the top 30 dysfunctions
                                        $critics = $alldys
                                            ->sortByDesc(function ($k) use ($allgravity, $allprobability) {
                                                $value =
                                                    ($allgravity->where('name', $k->gravity)->first() == null
                                                        ? 0
                                                        : $allgravity->where('name', $k->gravity)->first()->note) +
                                                    ($allprobability->where('id', $k->probability)->first() == null
                                                        ? 0
                                                        : $allprobability->where('id', $k->probability)->first()
                                                            ->note);
                                                $k->setAttribute('critic', $value);
                                                return $k;
                                            })
                                            ->take(30);
                                        $criticaldys = $alldys
                                            ->sortByDesc(function ($k) use ($allgravity, $allprobability) {
                                                $value =
                                                    ($allgravity->where('name', $k->gravity)->first() == null
                                                        ? 0
                                                        : $allgravity->where('name', $k->gravity)->first()->note) +
                                                    ($allprobability->where('id', $k->probability)->first() == null
                                                        ? 0
                                                        : $allprobability->where('id', $k->probability)->first()
                                                            ->note);
                                                $k->setAttribute('cal_gravity', $value);
                                                return $k;
                                            })
                                            ->take(30);
                                    @endphp
                                    <h2 class="mb-2">{{ formatNumber($created) }}</h2>
                                    <small class="text-danger text-nowrap fw-medium"><i class="bx bx-down-arrow-alt"></i>
                                        -{{ $percentage }}%</small>
                                </div>
                                <div id="visitorsChart"></div>
                            </div>
                        </div>
                        <div class="col-md-6 ps-md-4">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <h5 class="mb-0">Progression</h5>
                                <small>Dysfonctionnement</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mt-auto">
                                    <h4 class="mb-2" id="activityDysProgression">__%</h4>
                                    <small class="text-success text-nowrap fw-medium" id="longestduration"><i
                                            class="bx bx-time"></i> __J</small>
                                </div>
                                <div id="activityChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ New Visitors & Activity -->

            <div class="col-md-12 col-lg-4">
                <div class="row">
                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="../../assets/img/icons/unicons/wallet-info.png" alt="Total Issues"
                                            class="rounded" />
                                    </div>
                                </div>
                                <span class="d-block">Cette Année</span>
                                <h4 class="card-title mb-1">{{ formatNumber($alldys->count()) }} <span
                                        style="font-size: 8px">Signalements</span></h4>
                                <small class="text-success fw-medium"><i class="bx bx-check"></i>
                                    {{ $alldys->whereIn('status', [3, 6])->count() }} terminés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body pb-0">
                                <span class="d-block fw-medium">Le/Les plus signalé</span>
                            </div>
                            <div id="sitesChart" class="mb-2"></div>
                            <div class="p-3 pt-2">
                                <small class="text-muted d-block text-center">{{ $sites }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-12 mb-4">
                        <div class="card">
                            <div class="card-body pb-2">
                                <span class="d-block fw-medium">Entreprise</span>
                                <h3 class="card-title mb-0">Résolus/En cours</h3>
                                <div id="enterpriseChart"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-12 col-lg-8 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Vue sur l'année</h5>
                                <small class="card-subtitle">Aperçu du rapport annuel</small>
                            </div>
                            <div class="card-body">
                                <div id="yearChart"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title mb-0">Rapport</h5>
                                    <small class="card-subtitle">Cout de Non Qualité :
                                        <b>{{ formatNumber($alldys->sum('cost')) }}</b> XAF</small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="report-list">
                                    @foreach ($alldys->sortByDesc('cost')->take(5) as $_c)
                                        <div class="report-list-item rounded-2 mb-3">
                                            <div class="d-flex align-items-start">
                                                <div class="report-list-icon shadow-sm me-2">
                                                    <i class="bx bx-cog" style="width: 22px, height : 22px"></i>
                                                </div>
                                                <div
                                                    class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2">
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $_c->code }}</span>
                                                        <h6 class="mb-0">{{ formatNumber($_c->cost) }} XAF</h6>
                                                    </div>
                                                    <small
                                                        class="text-success">{{ \Carbon\Carbon::parse($_c->created_at)->format('d/m') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-12 mb-4 pb-3">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Type</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($alldystype as $dyst)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-paperclip"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $dyst->name }}</h6>
                                                    <small class="text-muted d-block mb-1">Nombre de dysfonctionnements
                                                        :</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span
                                                        class="fw-medium">{{ formatNumber(count($dyst->dysfunctions)) }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Site</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($allsite as $si)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-current-location"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $si->name }}</h6>
                                                    <small class="text-muted d-block mb-1">Nombre de dysfonctionnements
                                                        :</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span
                                                        class="fw-medium">{{ formatNumber(count($si->dysfunctions)) }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Processus</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($allprocess as $p)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-color"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $p->name }}</h6>
                                                    <small class="text-muted d-block mb-1">Nombre de dysfonctionnements
                                                        :</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span
                                                        class="fw-medium">{{ formatNumber(count($p->dysfunctions())) }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-12 mb-4 pb-3">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Criticité</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($critics->sortByDesc('critic') as $cri)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-fast-forward"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $cri->code }}</h6>
                                                    <small class="text-muted d-block mb-1">Note Critique :</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span class="fw-medium">{{ $cri->critic }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Gravité</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($criticaldys->sortByDesc('cal_gravity') as $gra)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-bell"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $gra->code }}</h6>
                                                    <small class="text-muted d-block mb-1">Gravité Enregistré : </small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span class="fw-medium">{{ $gra->cal_gravity }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-header d-flex align-items-center justify-content-between mb-4">
                                <h5 class="card-title m-0 me-2">Dysfonctionnements par <span
                                        class="text-primary">Processus</span></h5>
                            </div>
                            <div class="card-body" style="height:420px; overflow-y: auto;">
                                <ul class="p-0 m-0">
                                    @foreach ($allprocess as $p)
                                        <li class="d-flex mb-4 pb-1">
                                            <div class="avatar flex-shrink-0 me-3">
                                                <i class="bx bx-loader"></i>
                                            </div>
                                            <div
                                                class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                <div class="me-2">
                                                    <h6 class="mb-0">{{ $p->name }}</h6>
                                                    <small class="text-muted d-block mb-1">Nombre de dysfonctionnements
                                                        :</small>
                                                </div>
                                                <div class="user-progress d-flex align-items-center gap-1">
                                                    <span
                                                        class="fw-medium">{{ formatNumber(count($p->dysfunctions())) }}</span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Total Balance -->
            <div class="col-md-12 col-lg-12">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Apercu des Dépenses</h5>
                        <!--<div class="dropdown">
                                <button class="btn p-0" type="button" id="totalBalance" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalBalance">
                                    <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                </div>
                            </div>-->
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-start">
                            <div class="d-flex pe-4">
                                <div class="me-3">
                                    <span class="badge bg-label-warning p-2"><i
                                            class="bx bx-wallet text-warning"></i></span>
                                </div>
                                <div>
                                    <h6 class="mb-0" id="noqualityCost">------ XAF</h6>
                                    <small>Cout de non Qualité</small>
                                </div>
                            </div>
                        </div>
                        <div id="totalBalanceChart" class="border-bottom mb-3"></div>

                    </div>
                </div>
            </div>
            <!--/ Total Balance -->
        </div>
    </div>
@endsection
@section('scriptContent')
    <script src="{{ route('admin.dashboardjs') }}"></script>
@endsection
