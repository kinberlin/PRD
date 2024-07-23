@extends('rq.theme.main')
@section('title')
    Mon Profil
@endsection
@section('manualstyle')
    <link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-profile.css') !!}">
@endsection
@section('mainContent')
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Employé/</span>
            Profile
        </h4>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        <img src="{!! url('assets/img/pages/cadyst-banner.png') !!}" alt="Banner image" class="rounded-top">
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if (empty(Auth::user()->image))
                                <img src="{!! url('assets/img/icons/unicons/briefcase-round.png') !!}" alt="user image"
                                    class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                            @else
                                <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="user image"
                                    class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div
                                class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h4>
                                    <ul
                                        class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item fw-medium">
                                            <i class="bx bx-pen"></i>
                                            @if (empty(Auth::user()->poste))
                                                Poste Inconnu
                                            @else
                                                {{ Auth::user()->poste }}
                                            @endif
                                        </li>
                                        <li class="list-inline-item fw-medium">
                                            <i class="bx bx-map"></i>
                                            {{ $ents->where('id', Auth::user()->enterprise)->value('name') }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->
        <!-- Navbar pills -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" style="background-color: #113a60" href="javascript:void(0);">
                            <i class="bx bx-user me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link active btn-primary" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#passupdate">
                            <i class="bx bx-key me-1"></i> Modifier mon mot de passe
                        </button>
                        <div class="modal animate__animated animate__bounceInUp" id="passupdate" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form class="modal-content" action="{{ route('auth.passwordupdate') }}" method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel1">Changer le mot de passe</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @csrf
                                        <div class="row">
                                            <div class="col mb-6">
                                                <label for="nameBasic" class="form-label">Nouveau mot de passe</label>
                                                <input type="password" id="nameBasic" name="newPassword"
                                                    class="form-control" placeholder="Entrer le nouveau dmot de passe">
                                            </div>
                                            <div class="col mb-6">
                                                <label for="cfp" class="form-label">Confirmer le mot de passe</label>
                                                <input type="password" id="cfp" name="confirmPassword"
                                                    class="form-control" placeholder="Confirmer le mot de passe">
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
                    </li>
                </ul>
            </div>
        </div>
        <!--/ Navbar pills -->
        <!-- User Profile Content -->
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-5">
                <!-- About User -->
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Apropos</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-user"></i>
                                <span class="fw-medium mx-2">Nom Complet:</span>
                                <span>{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-flag"></i>
                                <span class="fw-medium mx-2">Département :</span>
                                <span>{{ $deps->where('id', Auth::user()->department)->value('name') }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-star"></i>
                                <span class="fw-medium mx-2">Authentifié en tant que :</span>
                                <span>
                                    Responsable Qualité
                                </span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-detail"></i>
                                <span class="fw-medium mx-2">Matricule:</span>
                                <span>{{ Auth::user()->matricule }}</span>
                            </li>
                        </ul>

                    </div>
                </div>
                <!--/ About User -->

            </div>
            <div class="col-xl-8 col-lg-7 col-md-7">
                <!-- Profile Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Contacts</small>
                        <ul class="list-unstyled mb-4 mt-3">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-phone"></i>
                                <span class="fw-medium mx-2">Contact:</span>
                                <span>{{ Auth::user()->phone }}</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bx bx-envelope"></i>
                                <span class="fw-medium mx-2">Email:</span>
                                <span>
                                    @if (empty(Auth::user()->email))
                                        Votre Email n'a pas été renseigner
                                    @else
                                        {{ Auth::user()->email }}
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--/ Profile Overview -->
                <!-- Profile Overview -->
                <div class="card mb-4">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">For Future Use...</small>

                    </div>
                </div>
            </div>
        </div>
        <!--/ User Profile Content -->
    </div>
    <!-- / Content -->
@endsection
@section('scriptContent')
    <script src="{!! url('assets/js/js/app-user-view-account.js') !!}"></script>
@endsection
