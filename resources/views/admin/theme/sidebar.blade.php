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
        <li class="menu-item @if (request()->route()->getName() == 'admin.index') active @endif">
            <a href="{!! route('admin.index')!!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Accueil">Accueil</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Gestion">Gestion</span>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'admin.enterprise' ||
                request()->route()->getName() == 'admin.department' ||
                request()->route()->getName() == 'admin.site' ||
                request()->route()->getName() == 'admin.employee') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-check-square"></i>
                <div class="text-truncate" data-i18n="Le Groupe">Le Groupe</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'admin.enterprise') active @endif">
                    <a href="/admin/enterprise" class="menu-link">
                        <div class="text-truncate" data-i18n="Entreprises">Entreprises</div>
                    </a>
                </li>
                <li class="menu-item @if (request()->route()->getName() == 'admin.department') active @endif">
                    <a href="/admin/department" class="menu-link">
                        <div class="text-truncate" data-i18n="Départements">Départements</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'admin.site') active @endif">
                    <a href="/admin/site" class="menu-link">
                        <div class="text-truncate" data-i18n="Sites">Sites</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'admin.employee') active @endif">
                    <a href="/admin/employee" class="menu-link">
                        <div class="text-truncate" data-i18n="Employés">Employés</div>
                    </a>
                </li>
            </ul>
        </li>
        <li 
        class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Dysfonctionnements">Dysfonctionnements</span>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'admin.processes' ||
                request()->route()->getName() == 'admin.signals' ||
                request()->route()->getName() == 'admin.planif' ||
                request()->route()->getName() == 'admin.employee') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div class="text-truncate" data-i18n="Signalements">Signalements</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'admin.processes') active @endif">
                    <a href="{!! route('admin.processes') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Processus">Processus</div>
                    </a>
                </li>
                <li class="menu-item @if (request()->route()->getName() == 'admin.signals') active @endif">
                    <a href="{!! route('admin.signals') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Signalements">Signalements</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'admin.planif') active @endif">
                    <a href="{!! route('admin.planif') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Planification">Planifications</div>
                    </a>
                </li>

                <li class="menu-item @if (request()->route()->getName() == 'admin.employee') active @endif">
                    <a href="/admin/employee" class="menu-link">
                        <div class="text-truncate" data-i18n="Responsable Qualité">Responsable Qualité</div>
                    </a>
                </li>
                <li class="menu-item @if (request()->route()->getName() == 'admin.employee') active @endif">
                    <a href="/admin/employee" class="menu-link">
                        <div class="text-truncate" data-i18n="Pilotes">Pilotes</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'admin.gravity') active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-plus"></i>
                <div class="text-truncate" data-i18n="Accessoires">Accessoires</div>
                <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->route()->getName() == 'admin.gravity') active @endif">
                    <a href="{!! route('admin.gravity') !!}" class="menu-link">
                        <div class="text-truncate" data-i18n="Gravité">Gravité</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Misc -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text" data-i18n="Misc">Misc</span></li>
        <li class="menu-item">
            <a href="#" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate" data-i18n="Support">Support</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Documentation">Documentation</div>
            </a>
        </li>
    </ul>

</aside>
