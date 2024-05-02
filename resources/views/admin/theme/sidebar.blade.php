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

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item @if(request()->route()->getName() == "admin.index") active @endif">
            <a href="/admin/dashboard" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate" data-i18n="Accueil">Accueil</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text" data-i18n="Utilisateur">Gestion</span>
        </li>
        <li class="menu-item @if(request()->route()->getName() == "admin.enterprise" || request()->route()->getName() == "admin.department" || request()->route()->getName() == "admin.site" || request()->route()->getName() == "admin.employee") active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-check-square"></i>
                <div class="text-truncate" data-i18n="Le Groupe">Le Groupe</div>
               <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(request()->route()->getName() == "admin.enterprise") active @endif">
                    <a href="/admin/enterprise" class="menu-link">
                        <div class="text-truncate" data-i18n="Entreprises">Entreprises</div>
                    </a>
                </li>
                <li class="menu-item @if(request()->route()->getName() == "admin.department") active @endif">
                    <a href="/admin/department" class="menu-link">
                        <div class="text-truncate" data-i18n="Départements">Départements</div>
                    </a>
                </li>
                
                <li class="menu-item @if(request()->route()->getName() == "admin.site") active @endif">
                    <a href="/admin/site" class="menu-link">
                        <div class="text-truncate" data-i18n="Sites">Sites</div>
                    </a>
                </li>
                
                <li class="menu-item @if(request()->route()->getName() == "admin.employee") active @endif">
                    <a href="/admin/employee" class="menu-link">
                        <div class="text-truncate" data-i18n="Employés">Employés</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item @if(request()->route()->getName() == "admin.pme" || request()->route()->getName() == "admin.pne") active open @endif">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fas fa-person-running"></i>
                <div class="text-truncate" data-i18n="Les Demandes">Les Demandes</div>
               <!--<span class="badge badge-center rounded-pill bg-danger ms-auto"></span>-->
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if(request()->route()->getName() == "admin.pme") active @endif">
                    <a href="/admin/pme" class="menu-link">
                        <div class="text-truncate" data-i18n="Permissions Exceptionelles">Permissions Exceptionelles</div>
                    </a>
                </li>
                <li class="menu-item @if(request()->route()->getName() == "admin.pne") active @endif">
                    <a href="/admin/pne" class="menu-link">
                        <div class="text-truncate" data-i18n="Permissions Non Exceptionelles">Permissions Non Exceptionelles</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item  @if(request()->route()->getName() == "admin.holliday") active @endif">
            <a href="/admin/holliday" class="menu-link">
                <i class="menu-icon tf-icons fas fa-bed"></i>
                <div class="text-truncate" data-i18n="Congés">Congés</div>
            </a>
        </li>

        <!-- Misc -->
        <li class="menu-header small text-uppercase"><span class="menu-header-text"
                data-i18n="Misc">Misc</span></li>
        <li class="menu-item">
            <a href="#" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate" data-i18n="Support">Support</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="#"
                target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate" data-i18n="Documentation">Documentation</div>
            </a>
        </li>
    </ul>

</aside>