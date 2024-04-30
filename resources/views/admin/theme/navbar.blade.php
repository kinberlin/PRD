                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">

                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- Language -->
                            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-globe bx-sm'></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="en"
                                            data-text-direction="ltr">
                                            <span class="align-middle">English</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-language="fr"
                                            data-text-direction="ltr">
                                            <span class="align-middle">Français</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- /Language -->

                            <!-- Année -->
                            <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-calendar bx-sm'></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-text-direction="ltr">
                                            <span class="align-middle">
                                                <script>
                                                    document.write(new Date().getFullYear())
                                                </script>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-text-direction="ltr">
                                            <span class="align-middle">
                                                <script>
                                                    document.write(new Date().getFullYear() - 1)
                                                </script>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-text-direction="ltr">
                                            <span class="align-middle">
                                                <script>
                                                    document.write(new Date().getFullYear() - 2)
                                                </script>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- /Année -->

                            <!-- Style Switcher -->
                            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <i class='bx bx-sm'></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                                            <span class="align-middle"><i class='bx bx-sun me-2'></i>Light</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                                            <span class="align-middle"><i class="bx bx-moon me-2"></i>Dark</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- / Style Switcher-->

                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="bx bx-bell bx-sm"></i>
                                    <span
                                        class="badge bg-danger rounded-pill badge-notifications">{{ \App\Models\Notification::where('receiver', Auth::user()->id)->get()->count() }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h5 class="text-body mb-0 me-auto">Notifications</h5>
                                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container ps ps--active-y">
                                        <ul class="list-group list-group-flush">
                                            @foreach (\App\Models\Notification::where('receiver', Auth::user()->id)->get() as $n)
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-danger">{{ \App\Models\Users::where('id', $n->sender)->value('firstname')[0] . \App\Models\Users::where('id', $n->sender)->value('lastname')[0] }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                {{ \App\Models\Users::where('id', $n->sender)->value('firstname') . ' ' . \App\Models\Users::where('id', $n->sender)->value('lastname') }}<br>({{ $n->title }})
                                                            </h6>
                                                            <p class="mb-0">{{ $n->message }}</p>
                                                            @php
                                                                // Assuming $timestamp is the timestamp retrieved from MySQL
                                                                $timestamp = \Carbon\Carbon::parse($n->created_at);

                                                                $currentDate = \Carbon\Carbon::now();
                                                                $diffInHours = $currentDate->diffInHours($timestamp);

                                                                if ($diffInHours > 48) {
                                                                    $diffInDays = $currentDate->diffInDays($timestamp);
                                                                    $result = "$diffInDays J";
                                                                } else {
                                                                    $result = "$diffInHours H";
                                                                }
                                                            @endphp
                                                            <small class="text-muted">{{ $result }}</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="ps__rail-x" style="left: 0px; bottom: -41px;">
                                            <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;">
                                            </div>
                                        </div>
                                        <div class="ps__rail-y" style="top: 41px; right: 0px; height: 480px;">
                                            <div class="ps__thumb-y" tabindex="0"
                                                style="top: 20px; height: 233px;"></div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ Notification -->
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <span
                                            class="avatar-initial rounded-circle bg-success">{{ Auth::user()->firstname[0] . Auth::user()->lastname[0] }}</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <span
                                                            class="avatar-initial rounded-circle bg-success">{{ Auth::user()->firstname[0] . Auth::user()->lastname[0] }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">{{ Auth::user()->firstname }}
                                                        <br>{{ Auth::user()->lastname }}</span>
                                                    <small class="text-muted">
                                                        @if (empty(Auth::user()->poste))
                                                            Poste Inconnu
                                                        @else
                                                            {{ Auth::user()->poste }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/employee/profile">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Mon Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="">
                                            <i class="bx bx-help-circle me-2"></i>
                                            <span class="align-middle">Documentation</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Déconnexion</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->

                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper  d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Search..." aria-label="Search...">
                        <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
                    </div>

                </nav>

                <!-- / Navbar -->
