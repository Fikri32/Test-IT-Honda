<!doctype html>
<!--[if lte IE 9]>     <html lang="en" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en" class="no-focus"> <!--<![endif]-->
    <head>
        @include('layouts/incl_top')
    </head>
    <body>
        <div id="page-container" class="sidebar-o side-scroll page-header-modern main-content-boxed">


            <!-- Sidebar -->
                @include('layouts.sidebar')
            <!-- END Sidebar -->

            <!-- Header -->
                @include('layouts.header')
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="content">
                    @yield('content')
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            @include('layouts.footer')
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Codebase Core JS -->

            @include('layouts/incl_bot')
            {{-- @include('sweetalert::alert') --}}

        @stack('scripts')

    </body>
</html>
