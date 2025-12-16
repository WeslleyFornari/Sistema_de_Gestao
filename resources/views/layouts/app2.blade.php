<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- TOKEN -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    DCA Eventos
  </title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{asset('assets/nucleo-icons.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/40b7169917.js" crossorigin="anonymous"></script>
  <link href="{{asset('vendors/fontawesome-free-6.6.0-web/css/all.min.css')}}" rel="stylesheet" />

  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <!-- Estilos CSS / TOGGLE-->
  <!-- <link rel="stylesheet" href="{{asset('css/toggle_Switch.css')}}"> -->
  <link rel="stylesheet" href="{{asset('css/toggle_Switch02.css')}}">
  <link rel="stylesheet" href="{{asset('css/estilos.css')}}">
  <link href="{{asset('assets/nucleo-svg.css')}}" rel="stylesheet" />
  <!-- CSS do SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])


  @yield('assets')
  <style>
  body{
    display:flex;
    justify-content: center;
    align-items: center;
  }

  .card-redefinir{

    margin-top: 120px;
  }
  .card-reset{

  margin-top: 100px;
  }

  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">


  @yield('content')


  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

  <!-- Account MOney -->
  <script src="{{asset('assets/js/accounting.min.js')}}"></script>

  <!--   Core JS Files   -->
  <script src="{{asset('/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>

  <!-- Flatpickr JS -->
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script><!-- Custom Javascript -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script src="https://kit.fontawesome.com/40b7169917.js" crossorigin="anonymous"></script>

  <script>


  </script>


</body>

</html>