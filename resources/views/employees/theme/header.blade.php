<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PRD - @yield('title')</title>
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
        <link rel="stylesheet" href="{!! url('assets/css/datatable/datatables.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/css/datatable/datatables.min.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/apex-charts/apex-charts.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/sweetalert2/sweetalert2.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/flatpickr/flatpickr.css') !!}" />
    <link rel="stylesheet" href="{!! url('assets/vendor/libs/select2/select2.css') !!}" />

    <!-- Page CSS -->

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
    @yield('manualstyle')
</head>
