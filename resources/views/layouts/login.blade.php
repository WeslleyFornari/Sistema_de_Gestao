<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>

  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Font Awesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Nucleo Icons -->
  <link href="{{asset('assets/nucleo-icons.css')}}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link href="{{asset('assets/nucleo-svg.css')}}" rel="stylesheet" />

  @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="container position-sticky z-index-sticky top-0">


    <main class="main-content mt-0">

      <section>
        <div class="page-header min-vh-75">

          <div class="container min-vh-100 d-flex align-items-center justify-content-center">
            <div class="row justify-content-center w-100">
              <div class="col-12 col-md-8 col-lg-6"> <!-- Ajuste as colunas conforme necessário -->
                <div class="card card-plain">
                  <div class="card-header pb-0 text-center bg-transparent"> <!-- Centralizei o texto -->
                    <h3 class="font-weight-bolder text-info text-gradient">Bem vindo!</h3>
                    <p class="mb-0">Acesse com seu login e senha</p>
                  </div>
                  <div class="card-body">
                    @yield('content')
                  </div>
                  <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <p class="mb-4 text-sm mx-auto">
                      Novo por aqui?
                      <a href="javascript:;" class="text-info text-gradient font-weight-bold">Entrar em contato</a>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>
    </main>
  </div>
  <!--   Core JS Files   -->
  <script src="{{asset('/assets/js/core/popper.min.js')}}"></script>
  <script src="{{asset('/assets/js/core/bootstrap.min.js')}}"></script>
  <script src="{{asset('/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <script src="{{asset('/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
  <script src="{{asset('/assets/js/plugins/chartjs.min.js')}}"></script>
  <script src="{{asset('/build/assets/app-4a1a0c12.js')}}"></script>
  <script src="{{asset('/build/assets/app-4a1a0c12.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous"></script>
  <script src="{{asset('/build/assets/app-4a1a0c12.js')}}"></script>



  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('assets/js/soft-ui-dashboard.min.js?v=1.0.7')}}"></script>
  
<script>

document.addEventListener('DOMContentLoaded', function() {
 
    const togglePassword = document.getElementById('toggle_password');
    const passwordInput = document.getElementById('signIn_password');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Alternar classes Font Awesome
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});
</script>
</body>

</html>