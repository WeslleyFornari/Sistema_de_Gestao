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

    <div class="row pt-4 mx-5">

          <div class="col-8 col-sm-4 col-md-5 col-lg-5 col-xl-5 pt-4">

            <h5 class="font-weight-bolder mb-0">@yield('title')</h5>
            <a href="{{Route('app.empresas.empresa')}}" class="text-decoration-underline"><small class="text-bold"> {{Auth::user()->empresa->nome}}  </small></a>
          </div>

          <div class="col-0 col-sm-4 col-md-4 col-lg-4 col-xl-4 pt-4 text-end px-5 d-none d-sm-block">
              <b><span class="mb-0">Olá, {{ Auth::user()->name}}</span></b>  <br>
              @if(Auth::user()->role == 'admin')
                <span class="mb-0">{{ Auth::user()->grupo->descricao ?? 'Administrador'}}</span>
              @endif
          </div>

          <div class="col-4 col-sm-4 col-md-3 col-lg-3 col-xl-3 pt-4 pe-4 text-end">
              @if(Auth::check())
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-right-from-bracket mx-1"></i>Sair</button>
              @endif
        </div>

    </div>

    <!-- Side bar xs sm -->
    <div class="container-fluid d-md-none justify-content-center">
      <div class="card mt-3">
            <div class="row mb-3">
                <div class="col-12 mt-3 ms-3">
                    <a href="#collapseMenu" class="text-primary toggleColapse" id="menuToggle" data-bs-toggle="collapse" role="button" aria-expanded="false"
                      aria-controls="collapseMenu">Explorar ...</a>
                  </div>
            </div>
            <div class="collapse" id="collapseMenu">
              <!-- <div class="card card-body"> -->
              @if(Auth::user()->role === 'admin')
                  <div class="row px-3">
                      @include('layouts.menu._admin')
                  </div>
              @elseif(Auth::user()->role === 'grupo')
                  <div class="row px-3">
                  @include('layouts.menu._grupo')
                  </div>
            @endif
            </div>
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

      // Status Off-line
      $("body").on('change', '.form-switch .form-check-input', function() {

        if ($(this).is(':checked')) {
          $(this).siblings('label').html('Ativo')
          $(this).val('ativo');
        } else {
          $(this).siblings('label').html('Inativo')
        }
      })

      function getMoney(numero) {
        return new Intl.NumberFormat('pt-BR', {
          style: 'currency',
          currency: 'BRL'
        }).format(numero).replace('R$', '');
      }

      // MASCARAS
      var SPMaskBehavior = function(val) {
          return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        spOptions = {
          onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments), options);
          }
        };

      $('.phoneMask').mask(SPMaskBehavior, spOptions);
      $('.moneyMask').mask("#.##0,00", {
        reverse: true
      });
      $('.cepMask').mask('00000-000');
      $('.cpfMask').mask('000.000.000-00', {
        reverse: true
      });
      $('.cnpjMask').mask('00.000.000/0000-00', {
        reverse: true
      });
      $('.creditCardMask').mask('0000 0000 0000 0000');
      $('.expirationDateMask').mask('00/00');
      $('.celMask').mask('(00) 00000-0000');

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

      function buscaCep(cep) {
        $.getJSON("https://viacep.com.br/ws/" + cep + "/json/?callback=?", function(dados) {
          $("input[name='endereco']").val(dados.logradouro)
          $("input[name='bairro']").val(dados.bairro)
          $("input[name='cidade']").val(dados.localidade)
          $("input[name='estado']").val(dados.uf)

        });
      }
      $("#buscaCep").change(function() {
        buscaCep($(this).val())
      });

      $("#searchCep").click(function(e) {
        e.preventDefault();
        buscaCep($("#buscaCep").val())
      })

      // GetMoney e SaveMoney
      function saveMoney($value) {

        if ($value === null) {
          return 0.00;
        }
        var $money = $value.replace(".", "");

        $money = $money.replace(",", ".", $money);

        return $money;
      }

      function getMoney($value) {

        if ($value === null) {
          return '';
        }

        return accounting.formatMoney($value, '', 2, ".", ",");
      }


      // CPF VALIDADOR
      $('#cpf').on('change', function() {

        var cpfInput = this.value;

        if (validaCPF(cpfInput)) {

          return true

        } else {

          swal({
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

  @yield('scripts')
</body>

</html>