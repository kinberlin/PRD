<!DOCTYPE html>
<html lang="fr" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="{!! url('assets') !!}/" data-template="vertical-menu-template">

@include('admin.theme.header')

<body>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- Menu -->
            @include('admin.theme.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                @include('admin.theme.navbar')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    @yield('mainContent')
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('admin.theme.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>

    @include('admin.theme.utils')
    @yield('scriptContent')
</body>

</html>
