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
                  Salut, M. {{Auth::user()->firstname}}!
                </h6>
                <small class="d-block mb-3 text-nowrap"
                  >Administrateur PRD</small
                >

                <h5 class="card-title text-primary mb-1">{{formatNumber(App\Models\Users::count())}}</h5>
                <small class="d-block mb-4 pb-1 text-muted"
                  >Utilisateurs</small
                >

                <a href="{{route('admin.employee')}}" class="btn btn-sm btn-primary"
                  >Plus d'Info</a
                >
              </div>
            </div>
            <div class="col-4 pt-3 ps-0">
              <img
                src="{{url('assets/img/illustrations/prize-light.png')}}"
                width="90"
                height="140"
                class="rounded-start"
                alt="Utilisateurs"
              />
            </div>
          </div>
        </div>
      </div>
      <!-- New Visitors & Activity -->
      <div class="col-lg-8 mb-4">
        <div class="card">
          <div class="card-body row g-4">
            <div class="col-md-6 pe-md-4 card-separator">
              <div
                class="card-title d-flex align-items-start justify-content-between"
              >
                <h5 class="mb-0">Nouveaux Signalements</h5>
                <small>La semaine derniere</small>
              </div>
              <div class="d-flex justify-content-between">
                <div class="mt-auto">
                    @php
                      $alldys = \App\Models\Dysfunction::whereYear('created_at',\Carbon\Carbon::now()->year)->get();
                      $alldystype = \App\Models\DysfunctionType::all();  
                      $allgravity = \App\Models\Gravity::all();  
                      $allsite = \App\Models\Site::all();  
                      $allprocess = \App\Models\Processes::all();  
                      $created = $alldys->whereBetween('created_at', [Carbon\Carbon::now()->subWeek()->startOfWeek(), Carbon\Carbon::now()->subWeek()->endOfWeek()])->count();
                        $rejected = $alldys->whereBetween('created_at', [Carbon\Carbon::now()->subWeek()->startOfWeek(), Carbon\Carbon::now()->subWeek()->endOfWeek()])->where('status', 3)->count();
                        $percentage = $rejected > 0 ? $rejected  * 100 / $created : 0;
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
                            $sites .= $s. " - Compte: " . $count . " Signalement ;";
                        }
                    @endphp
                  <h2 class="mb-2">{{formatNumber($created)}}</h2>
                  <small class="text-danger text-nowrap fw-medium"
                    ><i class="bx bx-down-arrow-alt"></i>
                    -{{$percentage}}%</small
                  >
                </div>
                <div id="visitorsChart"></div>
              </div>
            </div>
            <div class="col-md-6 ps-md-4">
              <div
                class="card-title d-flex align-items-start justify-content-between"
              >
                <h5 class="mb-0">Progression</h5>
                <small>Dysfonctionnement</small>
              </div>
              <div class="d-flex justify-content-between">
                <div class="mt-auto">
                  <h4 class="mb-2" id="activityDysProgression">__%</h4>
                  <small class="text-success text-nowrap fw-medium" id="longestduration"
                    ><i class="bx bx-time"></i> __J</small
                  >
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
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img
                      src="../../assets/img/icons/unicons/wallet-info.png"
                      alt="Total Issues"
                      class="rounded"
                    />
                  </div>
                </div>
                <span class="d-block">Cette Année</span>
                <h4 class="card-title mb-1">{{formatNumber($alldys->count())}} <span style="font-size: 8px">Signalements</span></h4>
                <small class="text-success fw-medium"
                  ><i class="bx bx-check"></i> {{$alldys->whereIn('status', [3,6])->count()}} terminés</small
                >
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
                <small class="text-muted d-block text-center"
                  >{{$sites}}</small
                >
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
                <small class="card-subtitle"
                  >Aperçu du rapport annuel</small
                >
              </div>
              <div class="card-body">
                <div id="yearChart"></div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card-header d-flex justify-content-between">
                <div>
                  <h5 class="card-title mb-0">Rapport</h5>
                  <small class="card-subtitle"
                    >Cout de Non Qualité : <b>{{formatNumber($alldys->sum('cost'))}}</b> XAF</small
                  >
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
                        class="d-flex justify-content-between align-items-end w-100 flex-wrap gap-2"
                      >
                        <div class="d-flex flex-column">
                          <span>{{$_c->code}}</span>
                          <h6 class="mb-0">{{formatNumber($_c->cost)}} XAF</h6>
                        </div>
                        <small class="text-success">{{\Carbon\Carbon::parse($_c->created_at)->format('d/m')}}</small>
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
    <div class="col-12 col-lg-8 mb-4">
      <div class="card">
        <div class="row row-bordered g-0">
          <div class="col-md-6">
            <div class="card-header d-flex align-items-center justify-content-between mb-4">
              <h5 class="card-title m-0 me-2">Dysfonctionnemnets par <span class="text-primary">Type</span></h5>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="topSales" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topSales">
                  <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <ul class="p-0 m-0">
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/oneplus.png" alt="oneplus">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Oneplus Nord</h6>
                      <small class="text-muted d-block mb-1">Oneplus</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <span class="fw-medium">$98,348</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/watch-primary.png" alt="smart band">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Smart Band 4</h6>
                      <small class="text-muted d-block mb-1">Xiaomi</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <span class="fw-medium">$15,459</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/surface.png" alt="Surface">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Surface Pro X</h6>
                      <small class="text-muted d-block mb-1">Miscrosoft</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <span class="fw-medium">$4,589</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/iphone.png" alt="iphone">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">iphone 13</h6>
                      <small class="text-muted d-block mb-1">Apple</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <span class="fw-medium">$84,345</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/earphone.png" alt="Bluetooth Earphone">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Bluetooth Earphone</h6>
                      <small class="text-muted d-block mb-1">Beats</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-1">
                      <span class="fw-medium">$10,374</span>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card-header d-flex align-items-center justify-content-between mb-4">
              <h5 class="card-title m-0 me-2">Top Products by <span class="text-primary">Volume</span></h5>
              <div class="dropdown">
                <button class="btn p-0" type="button" id="topVolume" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topVolume">
                  <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                  <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <ul class="p-0 m-0">
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/laptop-secondary.png" alt="ENVY Laptop" class="rounded">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">ENVY Laptop</h6>
                      <small class="text-muted d-block mb-1">HP</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-3">
                      <span class="fw-medium">124k</span>
                      <span class="badge bg-label-success">+12.4%</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/computer.png" alt="Apple" class="rounded">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Apple</h6>
                      <small class="text-muted d-block mb-1">iMac Pro</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-3">
                      <span class="fw-medium">74.9k</span>
                      <span class="badge bg-label-danger">-8.5%</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/watch.png" alt="Smart Watch" class="rounded">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Smart Watch</h6>
                      <small class="text-muted d-block mb-1">Fitbit</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-3">
                      <span class="fw-medium">4.4k</span>
                      <span class="badge bg-label-success">+62.6%</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex mb-4 pb-1">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/oneplus-success.png" alt="Oneplus RT" class="rounded">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Oneplus RT</h6>
                      <small class="text-muted d-block mb-1">Oneplus</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-3">
                      <span class="fw-medium">12,3k.71</span>
                      <span class="badge bg-label-success">+16.7%</span>
                    </div>
                  </div>
                </li>
                <li class="d-flex">
                  <div class="avatar flex-shrink-0 me-3">
                    <img src="../../assets/img/icons/unicons/pixel.png" alt="Pixel 4a" class="rounded">
                  </div>
                  <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                    <div class="me-2">
                      <h6 class="mb-0">Pixel 4a</h6>
                      <small class="text-muted d-block mb-1">Google</small>
                    </div>
                    <div class="user-progress d-flex align-items-center gap-3">
                      <span class="fw-medium">834k</span>
                      <span class="badge bg-label-danger">-12.9%</span>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between">
          <div class="card-title mb-0">
            <h5 class="m-0 me-2">Earning Reports</h5>
            <small class="text-muted">Weekly Earnings Overview</small>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="earningReports" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReports">
              <a class="dropdown-item" href="javascript:void(0);">Select All</a>
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <div class="card-body pb-0">
          <ul class="p-0 m-0">
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-primary"><i class='bx bx-trending-up'></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Net Profit</h6>
                  <small class="text-muted">12.4k Sales</small>
                </div>
                <div class="user-progress">
                  <small class="fw-medium">$1,619</small><i class='bx bx-chevron-up text-success ms-1'></i> <small class="text-muted">18.6%</small>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-success"><i class='bx bx-dollar'></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Total Income</h6>
                  <small class="text-muted">Sales, Affiliation</small>
                </div>
                <div class="user-progress">
                  <small class="fw-medium">$3,571</small><i class='bx bx-chevron-up text-success ms-1'></i> <small class="text-muted">39.6%</small>
                </div>
              </div>
            </li>
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-secondary"><i class='bx bx-credit-card'></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-0">Total Expenses</h6>
                  <small class="text-muted">ADVT, Marketing</small>
                </div>
                <div class="user-progress">
                  <small class="fw-medium">$430</small><i class='bx bx-chevron-up text-success ms-1'></i> <small class="text-muted">52.8%</small>
                </div>
              </div>
            </li>
          </ul>
          <div id="reportBarChart"></div>
        </div>
      </div>
    </div>
    </div>
    <div class="row">
      <!-- Performance -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <div
            class="card-header d-flex align-items-center justify-content-between"
          >
            <h5 class="card-title m-0 me-2">Performance</h5>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="performanceId"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="performanceId"
              >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last 28 Days</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last Month</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last Year</a
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <small
                  >Earnings:
                  <span class="fw-medium">$846.17</span></small
                >
              </div>
              <div class="col-6">
                <small
                  >Sales: <span class="fw-medium">25.7M</span></small
                >
              </div>
            </div>
          </div>
          <div id="performanceChart"></div>
        </div>
      </div>
      <!--/ Performance -->

      <!-- Conversion rate -->
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
          <div
            class="card-header d-flex align-items-center justify-content-between"
          >
            <div class="card-title mb-0">
              <h5 class="m-0 me-2">Conversion Rate</h5>
              <small class="text-muted">Compared To Last Month</small>
            </div>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="conversionRate"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="conversionRate"
              >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Select All</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Refresh</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Share</a
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <div
              class="d-flex justify-content-between align-items-center"
            >
              <div
                class="d-flex flex-row align-items-center gap-1 mb-4"
              >
                <h2 class="mb-2">8.72%</h2>
                <small class="text-success fw-medium">
                  <i class="bx bx-chevron-up"></i>
                  4.8%
                </small>
              </div>
              <div id="conversionRateChart"></div>
            </div>
            <ul class="p-0 m-0">
              <li class="d-flex mb-4">
                <div
                  class="d-flex w-100 flex-wrap justify-content-between gap-2"
                >
                  <div class="me-2">
                    <h6 class="mb-0">Impressions</h6>
                    <small class="text-muted">12.4k Visits</small>
                  </div>
                  <div class="user-progress">
                    <i
                      class="bx bx-up-arrow-alt text-success me-2"
                    ></i>
                    <span>12.8%</span>
                  </div>
                </div>
              </li>
              <li class="d-flex mb-4">
                <div
                  class="d-flex w-100 flex-wrap justify-content-between gap-2"
                >
                  <div class="me-2">
                    <h6 class="mb-0">Added To Cart</h6>
                    <small class="text-muted"
                      >32 Product in cart</small
                    >
                  </div>
                  <div class="user-progress">
                    <i
                      class="bx bx-down-arrow-alt text-danger me-2"
                    ></i>
                    <span>- 8.5% </span>
                  </div>
                </div>
              </li>
              <li class="d-flex mb-4">
                <div
                  class="d-flex w-100 flex-wrap justify-content-between gap-2"
                >
                  <div class="me-2">
                    <h6 class="mb-0">Checkout</h6>
                    <small class="text-muted"
                      >21 Products checkout</small
                    >
                  </div>
                  <div class="user-progress">
                    <i
                      class="bx bx-up-arrow-alt text-success me-2"
                    ></i>
                    <span>9.12%</span>
                  </div>
                </div>
              </li>
              <li class="d-flex">
                <div
                  class="d-flex w-100 flex-wrap justify-content-between gap-2"
                >
                  <div class="me-2">
                    <h6 class="mb-0">Purchased</h6>
                    <small class="text-muted">12 Orders</small>
                  </div>
                  <div class="user-progress">
                    <i
                      class="bx bx-up-arrow-alt text-success me-2"
                    ></i>
                    <span>2.83%</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!--/ Conversion rate -->

      <div class="col-md-12 col-lg-4">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3 col-lg-6 mb-4">
            <div class="card">
              <div class="card-body">
                <div
                  class="card-title d-flex align-items-start justify-content-between"
                >
                  <div class="avatar flex-shrink-0">
                    <img
                      src="../../assets/img/icons/unicons/cc-warning.png"
                      alt="Credit Card"
                      class="rounded"
                    />
                  </div>
                  <div class="dropdown">
                    <button
                      class="btn p-0"
                      type="button"
                      id="cardOpt5"
                      data-bs-toggle="dropdown"
                      aria-haspopup="true"
                      aria-expanded="false"
                    >
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div
                      class="dropdown-menu dropdown-menu-end"
                      aria-labelledby="cardOpt5"
                    >
                      <a
                        class="dropdown-item"
                        href="javascript:void(0);"
                        >View More</a
                      >
                      <a
                        class="dropdown-item"
                        href="javascript:void(0);"
                        >Delete</a
                      >
                    </div>
                  </div>
                </div>
                <span class="d-block mb-1">Revenue</span>
                <h3 class="card-title text-nowrap mb-2">$42,389</h3>
                <small class="text-success fw-medium"
                  ><i class="bx bx-up-arrow-alt"></i> +52.18%</small
                >
              </div>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-md-3 col-lg-6 mb-4">
            <div class="card">
              <div class="card-body">
                <span class="d-block fw-medium">Sales</span>
                <h3 class="card-title mb-2">482k</h3>
                <span class="badge bg-label-info mb-3">+34%</span>
                <small class="text-muted d-block">Sales Target</small>
                <div class="d-flex align-items-center">
                  <div class="progress w-75 me-2" style="height: 8px">
                    <div
                      class="progress-bar bg-info"
                      style="width: 78%"
                      role="progressbar"
                      aria-valuenow="78"
                      aria-valuemin="0"
                      aria-valuemax="100"
                    ></div>
                  </div>
                  <span>78%</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between gap-3">
                  <div
                    class="d-flex align-items-start flex-column justify-content-between"
                  >
                    <div class="card-title">
                      <h5 class="mb-0">Expenses</h5>
                    </div>
                    <div class="d-flex justify-content-between">
                      <div class="mt-auto">
                        <h3 class="mb-2">$84.7k</h3>
                        <small
                          class="text-danger text-nowrap fw-medium"
                          ><i class="bx bx-down-arrow-alt"></i>
                          8.2%</small
                        >
                      </div>
                    </div>
                    <span
                      class="badge bg-label-secondary rounded-pill"
                      >2021 Year</span
                    >
                  </div>
                  <div id="expensesBarChart"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-8 mb-4 mb-md-0">
        <div class="card">
          <div class="table-responsive text-nowrap">
            <table class="table text-nowrap">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Category</th>
                  <th>Payment</th>
                  <th>Order Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/oneplus-lg.png"
                        alt="Oneplus"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1"
                          >OnePlus 7Pro</span
                        >
                        <small class="text-muted">OnePlus</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-primary rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-mobile-alt bx-xs"></i
                    ></span>
                    Smart Phone
                  </td>
                  <td>
                    <div class="text-muted lh-1">
                      <span class="text-primary fw-medium">$120</span
                      >/499
                    </div>
                    <small class="text-muted">Partially Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-primary"
                      >Confirmed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/magic-mouse.png"
                        alt="Apple"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1"
                          >Magic Mouse</span
                        >
                        <small class="text-muted">Apple</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-warning rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-mouse bx-xs"></i
                    ></span>
                    Mouse
                  </td>
                  <td>
                    <div class="lh-1">
                      <span class="text-primary fw-medium">$149</span>
                    </div>
                    <small class="text-muted">Fully Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-success"
                      >Completed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/imac-pro.png"
                        alt="Apple"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1">iMac Pro</span>
                        <small class="text-muted">Apple</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-info rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-desktop bx-xs"></i
                    ></span>
                    Computer
                  </td>
                  <td>
                    <div class="text-muted lh-1">
                      <span class="text-primary fw-medium">$0</span
                      >/899
                    </div>
                    <small class="text-muted">Unpaid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-danger"
                      >Cancelled</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/note10.png"
                        alt="Samsung"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1">Note 10</span>
                        <small class="text-muted">Samsung</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-primary rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-mobile-alt bx-xs"></i
                    ></span>
                    Smart Phone
                  </td>
                  <td>
                    <div class="lh-1">
                      <span class="text-primary fw-medium">$149</span>
                    </div>
                    <small class="text-muted">Fully Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-success"
                      >Completed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/iphone.png"
                        alt="Apple"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1"
                          >iPhone 11 Pro</span
                        >
                        <small class="text-muted">Apple</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-primary rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-mobile-alt bx-xs"></i
                    ></span>
                    Smart Phone
                  </td>
                  <td>
                    <div class="lh-1">
                      <span class="text-primary fw-medium">$399</span>
                    </div>
                    <small class="text-muted">Fully Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-success"
                      >Completed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/mi-tv.png"
                        alt="Xiaomi"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1"
                          >Mi LED TV 4X</span
                        >
                        <small class="text-muted">Xiaomi</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-danger rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-tv bx-xs"></i
                    ></span>
                    Smart TV
                  </td>
                  <td>
                    <div class="text-muted lh-1">
                      <span class="text-primary fw-medium">$349</span
                      >/2499
                    </div>
                    <small class="text-muted">Partially Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-primary"
                      >Confirmed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <img
                        src="../../assets/img/products/logitech-mx.png"
                        alt="Logitech"
                        height="32"
                        width="32"
                        class="me-2"
                      />
                      <div class="d-flex flex-column">
                        <span class="fw-medium lh-1"
                          >Logitech MX</span
                        >
                        <small class="text-muted">Logitech</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span
                      class="badge bg-label-warning rounded-pill badge-center p-3 me-2"
                      ><i class="bx bx-mouse bx-xs"></i
                    ></span>
                    Mouse
                  </td>
                  <td>
                    <div class="lh-1">
                      <span class="text-primary fw-medium">$89</span>
                    </div>
                    <small class="text-muted">Fully Paid</small>
                  </td>
                  <td>
                    <span class="badge bg-label-primary"
                      >Completed</span
                    >
                  </td>
                  <td>
                    <div class="dropdown">
                      <button
                        type="button"
                        class="btn p-0 dropdown-toggle hide-arrow"
                        data-bs-toggle="dropdown"
                      >
                        <i class="bx bx-dots-vertical-rounded"></i>
                      </button>
                      <div class="dropdown-menu">
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-edit-alt me-1"></i> View
                          Details</a
                        >
                        <a
                          class="dropdown-item"
                          href="javascript:void(0);"
                          ><i class="bx bx-trash me-1"></i> Delete</a
                        >
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Total Balance -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100">
          <div
            class="card-header d-flex align-items-center justify-content-between"
          >
            <h5 class="card-title m-0 me-2">Total Balance</h5>
            <div class="dropdown">
              <button
                class="btn p-0"
                type="button"
                id="totalBalance"
                data-bs-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="totalBalance"
              >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last 28 Days</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last Month</a
                >
                <a class="dropdown-item" href="javascript:void(0);"
                  >Last Year</a
                >
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-start">
              <div class="d-flex pe-4">
                <div class="me-3">
                  <span class="badge bg-label-warning p-2"
                    ><i class="bx bx-wallet text-warning"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-0">$2.54k</h6>
                  <small>Wallet</small>
                </div>
              </div>
              <div class="d-flex">
                <div class="me-3">
                  <span class="badge bg-label-secondary p-2"
                    ><i class="bx bx-dollar text-secondary"></i
                  ></span>
                </div>
                <div>
                  <h6 class="mb-0">$4.2k</h6>
                  <small>Paypal</small>
                </div>
              </div>
            </div>
            <div
              id="totalBalanceChart"
              class="border-bottom mb-3"
            ></div>
            <div class="d-flex justify-content-between">
              <small class="text-muted"
                >You have done
                <span class="fw-medium">57.6%</span> more sales.<br />Check
                your new badge in your profile.</small
              >
              <div>
                <span class="badge bg-label-warning p-2"
                  ><i
                    class="bx bx-chevron-right text-warning scaleX-n1-rtl"
                  ></i
                ></span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ Total Balance -->
    </div>
  </div>
@endsection
@section('scriptContent')
    <script src="{{route ('admin.dashboardjs') }}"></script>
@endsection
