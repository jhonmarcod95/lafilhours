<!DOCTYPE html>
<html lang="en">

  <head>
    @include('layouts.head')
    @yield('headScript')

    <!-- General Scripts -->
    <link href="{{ asset('css/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet">

  </head>

  <body class="fixed-nav sticky-footer" id="page-top">

    @include('layouts.nav')

    @yield('content')


    @include('layouts.jscript')
    <!-- Plugin JavaScript -->
    <script src="{{ asset('js/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('js/datatables/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('script')

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fa fa-angle-up"></i>
    </a>


  </body>
  <footer class="navbar footer fixed-bottom footer-light footer-shadow content container-fluid">

      {{--<span class="text-muted">Call 2121 for Support</span>--}}

  </footer>

</html>
