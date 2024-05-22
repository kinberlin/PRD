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
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text" data-i18n="Gestion">Gestion</span>
    </li>
    <li class="menu-item @if (request()->route()->getName() == 'employee.dysfonction') active @endif">
        <a href="{!! route('employee.dysfonction') !!}" target="_blank" class="menu-link">
            <i class="menu-icon tf-icons bx bx-support"></i>
            <div class="text-truncate">Signaler</div>
        </a>
    </li>
    <li class="menu-item @if (request()->route()->getName() == 'employee.signalement') active @endif">
        <a href="{!! route('employee.signalement') !!}" target="_blank" class="menu-link">
            <i class="menu-icon tf-icons bx bx-list-check"></i>
            <div class="text-truncate">Mes Signalements</div>
        </a>
    </li>

    <div class="menu-inner-shadow"></div>

    <div class="menu-inner-shadow"></div>
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text" data-i18n="Apps & Pages">Apps &amp; Pages</span>
    </li>
    <ul class="menu-inner py-1">
        <li class="menu-item @if (request()->route()->getName() == 'rq.dysfonction') active @endif">
            <a href="{!! route('rq.dysfonction') !!}" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate">Signaler</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.signalement') active @endif">
            <a href="{!! route('rq.signalement') !!}" target="_blank" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div class="text-truncate">Mes Signalements</div>
            </a>
        </li>
        <li class="menu-item @if (request()->route()->getName() == 'rq.planif') active @endif">
            <a href="{!! route('rq.planif') !!}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-timer"></i>
                <div class="text-truncate">Planifications</div>
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
