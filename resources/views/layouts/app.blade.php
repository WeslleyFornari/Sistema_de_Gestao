<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- TOKEN -->

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>
    @yield('title')
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
    .navbar-vertical .navbar-nav .nav-item .nav-link .icon i {
      color: #1D3857 !important;
      font-size: 15px;
    }

    .navbar-vertical .navbar-nav>.nav-item .nav-link.active .icon {

      background-image: linear-gradient(310deg, rgb(229, 228, 228) 0%, rgb(229, 228, 228) 100%);
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 40px;
      height: 20px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      transition: 0.4s;
      border-radius: 34px;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 14px;
      width: 14px;
      border-radius: 50%;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: 0.4s;
    }

    input:checked+.slider {
      background-color: #00194A;
    }

    input:checked+.slider:before {
      transform: translateX(20px);
    }

    .img-thumbnail {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 8px;
      border: 1px solid #ddd;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" style="background: none!important;">

    @include('layouts._aside')
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg " style="overflow-x: hidden;">

    <div class="row pt-4 mx-3">

      <div class="col-sm-3 pt-4">
        <h5 class="font-weight-bolder mb-0">@yield('title')</h5>
      </div>

      <!-- Alerts -->
      <div class="col-0 col-sm-7 pt-4 px-5 d-flex justify-content-start">
        @if(session('info'))
       <div id="auto-close-alert" 
     class="alert alert-primary border-0 shadow-sm text-white text-center" 
     role="alert" 
     style="background-color: #0d6efd !important; background-image: none !important; opacity: 1 !important;">
    {{ session('info') }}
</div>
        @endif

        @if(session('success'))
        <div id="auto-close-alert" class="alert alert-success border-0 shadow-sm text-white text-center" role="alert" style="background-color: #198754 !important;">
          {{ session('success') }}
        </div>
        @endif
      </div>

      <div class="col-sm-2 pt-4 text-end">
        @if(Auth::check())
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-right-from-bracket mx-1"></i>Sair</button>
          @endif
        </form>
      </div>

    </div>




    <!-- CONTEUDO -->
    <div class="container-fluid">
      @yield('content')
    </div>

  </main>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
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
    // $(document).ready(function() {
    //   $('#menuToggle').on('click', function(e) {
    //     e.preventDefault();
    //     $('#collapseMenu').collapse('toggle');
    //   });
    // });
  </script>

  <script>
    $(document).ready(function() {
      $('body').on('click', '.toggleColapse', function() {
        const filterCollapse = $(this).data('target');
        $(filterCollapse).toggleClass('show');

      });

      // MASCARAS
      var SPMaskBehavior = function(val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
          onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
          }
        };

      $('.cpfMask').mask('000.000.000-00', {
        reverse: true
      });
      $('.cnpjMask').mask('00.000.000/0000-00', {
        reverse: true
      });


      // jQuery COLLAPSE  
      $("body").on('click', '.tooglegeCollapse', function(e) {

        e.preventDefault();
        var alvo = $(this).data('target');
        $(".collapse").not(alvo).removeClass('show');
        $(alvo).toggleClass('show')
      })

      // DESTROY
      $(".btn-destroy").click(function(e) {
        var url = $(this).attr('href');
        e.preventDefault();
        $(this).closest('tr').addClass("remove-row");
        $(this).closest('.row').addClass("remove-row");
        swal({
          title: "Você tem certeza?",
          text: "Você removerá permanentemente este item",
          icon: "warning",
          dangerMode: true,
          buttons: {
            cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            confirm: {
              text: "OK",
              value: true,
              visible: true,
              className: "",
              closeModal: true
            }
          }
        }).then(willDelete => {
          if (willDelete) {

            $.ajax({
              url: url,
              type: 'GET',
              success: function(data) {
                if (willDelete) {
                  $(".remove-row").remove();
                  swal("Sucesso!", "Item removido com sucesso", "success");
                }
              },
              error: function(err) {
                var erro = err.responseJSON
                swal("Atenção!", erro.error, "error");
              }
            });


          }
        });
      })

      // Get CNPJ
      $('#cnpj').on('change', function() {

        var cnpjInput = this.value;

        if (validaCNPJ(cnpjInput)) {

          return true

        } else {

          swal.fire({
            title: "CNPJ inválido!",
            icon: "error",
          }).then(function() {

            $('#cnpj').val('');
          });
        }
      });

      function validaCNPJ(cnpj) {
        var b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]
        var c = String(cnpj).replace(/[^\d]/g, '')

        if (c.length !== 14)
          return false

        if (/0{14}/.test(c))
          return false

        for (var i = 0, n = 0; i < 12; n += c[i] * b[++i]);
        if (c[12] != (((n %= 11) < 2) ? 0 : 11 - n))
          return false

        for (var i = 0, n = 0; i <= 12; n += c[i] * b[i++]);
        if (c[13] != (((n %= 11) < 2) ? 0 : 11 - n))
          return false

        return true
      }


      // Get CPF
      $('#cpf').on('change', function() {

        var cpfInput = this.value;

        if (validaCPF(cpfInput)) {

          return true

        } else {

          swal.fire({
            title: "CPF inválido!",
            icon: "error",
          }).then(function() {

            $('#cpf').val('');
          });
        }
      });

      function validaCPF(cpf) {
        var Soma = 0
        var Resto

        var strCPF = String(cpf).replace(/[^\d]/g, '')

        if (strCPF.length !== 11)
          return false

        if ([
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999',
          ].indexOf(strCPF) !== -1)
          return false

        for (i = 1; i <= 9; i++)
          Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (11 - i);

        Resto = (Soma * 10) % 11

        if ((Resto == 10) || (Resto == 11))
          Resto = 0

        if (Resto != parseInt(strCPF.substring(9, 10)))
          return false

        Soma = 0

        for (i = 1; i <= 10; i++)
          Soma = Soma + parseInt(strCPF.substring(i - 1, i)) * (12 - i)

        Resto = (Soma * 10) % 11

        if ((Resto == 10) || (Resto == 11))
          Resto = 0

        if (Resto != parseInt(strCPF.substring(10, 11)))
          return false

        return true
      }

    });
  </script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('auto-close-alert');
        if (alert) {
            setTimeout(() => {
              
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                
                setTimeout(() => {
                    alert.remove();
                }, 500); 
            }, 5000);
        }
    });
</script>

  @yield('scripts')
</body>

</html>