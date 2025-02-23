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
    <ul class="menu-inner py-1 overflow-auto">
        @can('isPilote', Auth::user())
            <!-- Dashboards -->
            <li class="menu-item @if (request()->route()->getName() == 'employee.index') active open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div class="text-truncate" data-i18n="Accueil">Accueil</div>
                    <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
                </a>
                <ul class="menu-sub">
                    @php
                        $permissions = \App\Models\AuthorisationPilote::where('user', Auth::user()->id)->get();
                        $procs = \App\Models\Processes::whereIn('id', $permissions->pluck('process')->unique())->get();
                    @endphp
                    @foreach ($permissions as $ar)
                        <li class="menu-item @if (request()->route()->getName() == 'employee.index' && request()->route('id') == $ar->process) active @endif">
                            <a href="{!! route('employee.index', ['id' => $ar->process]) !!}" class="menu-link">
                                <div class="text-truncate"
                                    data-i18n="{{ $procs->where('id', $ar->process)->first()->name }}"></div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endcan
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Gestion">Gestion</span>
        </li>

        <li class="menu-item @if (request()->route()->getName() == 'employees.dysfunction') active @endif">
            <a href="{!! route('employees.dysfunction') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate">Signaler</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'employees.signalement') active @endif">
            <a href="{!! route('employees.signalement') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div class="text-truncate">Mes Signalements</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'emp.invitation') active @endif">
            <a href="{!! route('emp.invitation') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate">Mes Invitations</div>
            </a>
        </li>
        @can('isPilote', Auth::user())
            <li class="menu-item @if (request()->route()->getName() == 'employees.mytask') active @endif">
                <a href="{!! route('employees.mytask') !!}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-task-x"></i>
                    <div class="text-truncate">Mes Tâches</div>
                </a>
            </li>
        @endcan

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Autres">Autres</span>
        </li>
        <ul class="menu-inner py-1">
            @can('isPilote', Auth::user())
                <li class="menu-item @if (request()->route()->getName() == 'emp.dysfunction.report' || request()->route()->getName() == 'emp.dysfunction.report.post') active @endif">
                    <a href="{!! route('emp.dysfunction.report') !!}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-search-alt"></i>
                        <div class="text-truncate">Rechercher</div>
                    </a>
                </li>
            @endcan
            <li class="menu-item">
                <a href="#" target="_blank" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div class="text-truncate" data-i18n="Documentation">Documentation</div>
                </a>
            </li>
        </ul>
    </ul>
</aside>
