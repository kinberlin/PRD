<!DOCTYPE html>

<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="{!! url('assets') !!}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Demande de Congés - Authentification</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{!! url('assets/img/logo/favicon.png') !!}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{!! url('assets/vendor/fonts/boxicons.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/fonts/fontawesome.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/fonts/flag-icons.css') !!}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{!! url('assets/vendor/css/rtl/core.css') !!}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{!! url('assets/vendor/css/rtl/theme-default.css') !!}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{!! url('assets/css/demo.css') !!}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/typeahead-js/typeahead.css') !!}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/%40form-validation/form-validation.css') !!}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-auth.css') !!}">

    <!-- Helpers -->
    <script src="{!! url('assets/vendor/js/helpers.js') !!}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{!! url('assets/vendor/js/template-customizer.js') !!}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{!! url('assets/js/js/config.js') !!}"></script>

</head>

<body>

    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center" style="background-color: white">
                            <a class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">

                                    <img src="{!! url('assets/img/logo/cadyst.png') !!}" width="120" height="30" />

                                </span>
                                <span class="app-brand-text demo fw-bold">PRD</span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <div  style="background-image: url('{!! url('assets/img/pages/planet-earth-svgrepo-com.svg') !!}') ; background-position: center;   background-repeat: no-repeat;  background-size: contain;">
                        <h4 class="mb-2">Bienvenue à vous! 👋</h4>
                        <p class="mb-4">Entrez vos informations pour continuer...</p>

                        <form id="formAuthentication" class="mb-3" action="/login" method="POST"
                           >
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Matricule/Email</label>
                                <input type="text" class="form-control" id="email" name="matricule"
                                    placeholder="Entrez votre matricule / adresse mail" autofocus>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Se Connecter</button>
                            </div>
                        </form>
                        </div>
                        <p class="text-center">
                            <span>Vous ne trouvez pas votre compte ?</span>
                            <a href="#">
                                <span>Contacter la QHSE</span>
                            </a>
                        </p>
                        @if (session('error'))
                            <p class="text-center">
                                <span style="color: red">{{ session('error') }}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{!! url('assets/vendor/libs/jquery/jquery.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/popper/popper.js') !!}"></script>
    <script src="{!! url('assets/vendor/js/bootstrap.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/hammer/hammer.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/i18n/i18n.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/typeahead-js/typeahead.js') !!}"></script>
    <script src="{!! url('assets/vendor/js/menu.js') !!}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{!! url('assets/vendor/libs/%40form-validation/popular.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/bootstrap5.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/%40form-validation/auto-focus.js') !!}"></script>

    <!-- Main JS -->
    <script src="{!! url('assets/js/js/main.js') !!}"></script>

    <!-- Page JS -->
    <script src="{!! url('assets/js/js/pages-auth.js') !!}"></script>

</body>

</html>
