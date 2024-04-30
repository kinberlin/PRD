@extends('admin.theme.main')
@section('title')
    Tabbleau de Bord | Administrateur
@endsection
@section('manualstyle')
@endsection
@section('mainContent')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Re-bienvenue, {{ Auth::user()->firstname }} 🎉</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-4 col-lg-4 col-xl-4 order-0 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between pb-0">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Statistiques sur les demandes</h5>
                                <small class="text-muted">{{ count($pne) + count($holliday) + count($pme) }} Demandes au
                                    total</small>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <h2 class="mb-2">{{ $reports }}</h2>
                                    <span>Demandes cette année</span>
                                </div>
                                <div id="orderStatisticsChart"></div>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            <i class="bx bx-mobile-alt"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">PNE</h6>
                                            <small class="text-muted">Permissions Non Exceptionelles(validés)</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-medium">{{ count($pne) }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="bx bx-closet"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Congés</h6>
                                            <small class="text-muted">Demande de Congés </small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-medium">{{ count($holliday) }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-4 pb-1">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded bg-label-info">
                                            <i class="bx bx-home-alt"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">PE</h6>
                                            <small class="text-muted">Permissions Exceptionelles</small>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-medium">{{ count($pme) }}</small>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/ Order Statistics -->
                <div class="col-md-6 col-xxl-4 mb-4 order-1 order-xxl-3">
                    <div class="card h-100">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">Vue rapide sur les données du Personnel</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="deliveryExceptionsChart"></div>
                        </div>
                    </div>
                </div>

                <!-- pill table -->
                <div class="col-md-4 order-3 order-lg-4 mb-4 mb-lg-0">
                    <div class="card text-center">
                        <div class="card-header py-3">
                            <ul class="nav nav-pills" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-pills-browser" aria-controls="navs-pills-browser"
                                        aria-selected="true">PE</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-pills-os" aria-controls="navs-pills-os"
                                        aria-selected="false">PNE</button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                        data-bs-target="#navs-pills-country" aria-controls="navs-pills-country"
                                        aria-selected="false">Congés</button>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade show active" id="navs-pills-browser" role="tabpanel">
                                <div class="table-responsive text-start">
                                    <table class="table table-borderless text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Entreprise</th>
                                                <th>Qte</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ents as $e)
                                                <tr>
                                                    <td>{{ $e->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="" height="24" class="me-2">
                                                            <span>{{ $e->name }}</span>
                                                        </div>
                                                    </td>
                                                    @php
                                                        $tots = count($pme->where('enterprise', $e->id));

                                                        function formatNumber($number)
                                                        {
                                                            if ($number > 1000) {
                                                                if ($number >= 1100) {
                                                                    return number_format($number / 1000, 1) . 'K';
                                                                } else {
                                                                    return number_format($number / 1000) . 'K';
                                                                }
                                                            } else {
                                                                return $number;
                                                            }
                                                        }
                                                        $qty = formatnumber($tots);
                                                    @endphp
                                                    <td>{{ $qty }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-os" role="tabpanel">
                                <div class="table-responsive text-start">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Entreprise</th>
                                                <th>Qte</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ents as $e)
                                                <tr>
                                                    <td>{{ $e->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="" height="24" class="me-2">
                                                            <span>{{ $e->name }}</span>
                                                        </div>
                                                    </td>
                                                    @php
                                                        $tots = count($pne->where('enterprise', $e->id));
                                                        $qty = formatnumber($tots);
                                                    @endphp
                                                    <td>{{ $qty }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-pills-country" role="tabpanel">
                                <div class="table-responsive text-start">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Entreprise</th>
                                                <th>Qte</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ents as $e)
                                                <tr>
                                                    <td>{{ $e->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="" height="24" class="me-2">
                                                            <span>{{ $e->name }}</span>
                                                        </div>
                                                    </td>
                                                    @php
                                                        $tots = count($holliday->where('enterprise', $e->id));
                                                        $qty = formatnumber($tots);
                                                    @endphp
                                                    <td>{{ $qty }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ pill table -->
            </div>
        </div>
    </div>
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/js/dashboards-analytics.js') !!}"></script>
@endsection
