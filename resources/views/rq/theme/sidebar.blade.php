<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo " style="background-color: white">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">

                <!-- Logo -->
                <div class="app-brand justify-content-center">
                    <a class="app-brand-link gap-2">
                        <span class="app-brand-logo demo">

                            <img src="{!! url('assets/img/logo/cadyst.png') !!}" width="90" height="22.5" />

                        </span>
                        <span class="app-brand-text demo fw-bold" style="font-size : 1rem">PRD</span>
                    </a>
                </div>
                <!-- /Logo -->

            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1 overflow-auto">
        <!-- Dashboards -->
        <li class="menu-item @if (request()->route()->getName() == 'rq.index') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Accueil">Accueil</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                @php
                    $permissions = \App\Models\AuthorisationRq::where('user', Auth::user()->id)->get();
                    $ents = \App\Models\Enterprise::whereIn('id', $permissions->pluck('enterprise')->unique())->get();
                @endphp
                @foreach ($permissions as $ar)
                    <li class="menu-item @if (request()->route()->getName() == 'rq.index' && request()->route('id') == $ar->enterprise) active @endif">
                        <a href="{!! route('rq.index', ['id' => $ar->enterprise]) !!}" class="menu-link">
                            <div class="text-truncate"
                                data-i18n="{{ $ents->where('id', $ar->enterprise)->first()->name }}"></div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Gestion">Gestion</span>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.department' || request()->route()->getName() == 'rq.site') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-check-square"></i>
                <div class="text-truncate" data-i18n="Mon Entreprise">Mon Entreprise</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'rq.department') active @endif">
                    <a href="{!! route('rq.department') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Départements">Départements</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'rq.site') active @endif">
                    <a href="{!! route('rq.site') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Sites">Sites</div>
                    </a>
                </li>
            </ul>
        </li>
        <div class="menu-inner-shadow"></div>
        <li class="menu-item @if (request()->route()->getName() == 'rq.employees' ||
                request()->route()->getName() == 'rq.responsables' ||
                request()->route()->getName() == 'rq.pilotes') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user-pin"></i>
                <div class="text-truncate" data-i18n="Le personnel">Le personnel</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'rq.employees') active @endif">
                    <a href="{!! route('rq.employees') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Employés">Employés</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'rq.responsables') active @endif">
                    <a href="{!! route('rq.responsables') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Responsable Qualités">Responsable Qualités</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'rq.pilotes') active @endif">
                    <a href="{!! route('rq.pilotes') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Pilotes">Piotes</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.meeting.inprocess' || request()->route()->getName() == 'rq.meeting.closed') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-color"></i>
                <div class="text-truncate" data-i18n="Réunions">Réunions</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'rq.meeting.inprocess') active @endif">
                    <a href="{{ route('rq.meeting.inprocess') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="En Cours">En Cours</div>
                    </a>
                </li>
                <li class="menu-item @if (request()->route()->getName() == 'rq.meeting.closed') active @endif">
                    <a href="{{ route('rq.meeting.closed') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Terminé">Terminé</div>
                    </a>
                </li>
            </ul>
        </li>
        <div class="menu-inner-shadow"></div>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Autres">Autres</span>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.allsignalement' ||
                request()->route()->getName() == 'rq.signalement' ||
                request()->route()->getName() == 'rq.invitation' ||
                request()->route()->getName() == 'rq.n1dysfonction') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate" data-i18n="Mon espace">Personnel</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'rq.allsignalement') active @endif">
                    <a href="{!! route('rq.allsignalement') !!}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-message-alt-check"></i>
                        <div class="text-truncate">Liste des Signalements</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'rq.signalement') active @endif">
                    <a href="{!! route('rq.signalement') !!}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-list-check"></i>
                        <div class="text-truncate">Mes Signalements</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'rq.invitation') active @endif">
                    <a href="{!! route('rq.invitation') !!}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-envelope"></i>
                        <div class="text-truncate">Mes Invitations</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.dysfonction') active @endif">
            <a href="{!! route('rq.dysfonction') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate">Signaler</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.planif') active @endif">
            <a href="{!! route('rq.planif') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-timer"></i>
                <div class="text-truncate">Planifications</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.dysfunction.report' ||
                request()->route()->getName() == 'rq.dysfunction.report.post') active @endif">
            <a href="{!! route('rq.dysfunction.report') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-search-alt"></i>
                <div class="text-truncate">Rechercher</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Documentation">Documentation</div>
            </a>
        </li>
    </ul>

</aside>
