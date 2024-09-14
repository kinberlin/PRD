<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../../assets/" data-template="vertical-menu-template">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PRD - Confirmation de Présence à la Réunion du Dysfonctionnement No. {{ $invitation->dysfonction }}</title>
    <meta name="description" content="@yield('description')">
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

    <!-- Page CSS -->
    <link rel="stylesheet" href="{!! url('assets/vendor/css/pages/page-misc.css') !!}">
    <!-- Helpers -->
    <script src="{!! url('assets/vendor/js/helpers.js') !!}"></script>
    <script src="{!! url('assets/vendor/js/template-customizer.js') !!}"></script>
    <script src="{!! url('assets/js/js/config.js') !!}"></script>
    <!-- Manual Styles -->
    <style>
        .template-customizer-open-btn {
            visibility: hidden;
        }
    </style>
</head>

<body>

    <!-- Not Authorized -->
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <form action="/" method="get">
                @csrf
                <h2 class="mb-2 mx-2">Confirmation de présence à la réunion</h2>
                @if ($decision == 1)
                    <p class="mb-4 mx-2">Nous vous remercions d'avoir accepté notre invitation et d'avoir confirmé votre
                        présence par :
                        <br><b>Oui, je serai présent.</b>
                    </p>
                @elseif ($decision == 0)
                    <p class="mb-4 mx-2">Nous vous remercions d'avoir accepté notre invitation et d'avoir confirmé votre
                        présence par :
                        <br><b> Non, je ne pourrai pas être présent.</b>
                    </p>
                @endif
                <input type="submit" class="btn btn-primary" value="Cliquer ici pour retourner"></input>
                </div>
            </form>
        </div>
    </div>
    <!-- /Not Authorized -->

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{!! url('assets/vendor/libs/jquery/jquery.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/popper/popper.js') !!}"></script>
    <script src="{!! url('assets/vendor/js/bootstrap.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/hammer/hammer.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/i18n/i18n.js') !!}"></script>
    <script src="{!! url('assets/vendor/libs/typeahead-js/typeahead.js') !!}"></script>
    <script src="{!! url('assets/vendor/js/menu.js') !!}"></script>

    <!-- endbuild -->
    <!-- Main JS -->
    <script src="{!! url('assets/js/js/main.js') !!}"></script>
    <!-- Page JS -->

</body>

</html>
