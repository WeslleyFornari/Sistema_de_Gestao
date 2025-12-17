@extends('layouts.app')
@section('title','Colaboradores')

@section('content')
<style>
    #FiltroGeral {
        border: 1px solid rgb(222, 223, 223);
        border-radius: 5px;
    }
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
        width: 100%;
    }

    .pagination li {
        margin: 2px;
    }
    @media (min-width: 576px) and (max-width: 800px) {
      .ellipsis {
          white-space: nowrap;
          width: 60px;
          overflow: hidden;
          text-overflow: ellipsis;
          display: inline-block;
      }
    } 
</style>

<div class="py-4">
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

            <div class="row mb-3">
                    <div class="col-12 col-sm-8 ps-4 my-2 text-center text-sm-start d-block">
                    <div class="actions-btn">
                                <!-- Botões de ação - Criar e recarregar -->
                                <a href="{{ route('app.colaboradores.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Adicionar
                                </a>
                                <button id="reloadItems" class="btn btn-secondary">
                                    <i class="fa-solid fa-sync"></i>
                                </button>
                            </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <!-- Botão para colapsar os filtros e busca -->
                        <button class="btn btn-link w-100 toggleColapse" data-target="#filterCollapse" type="button">
                                <i class="fa-solid fa-filter"></i> Filtros Avançados
                            </button>
                    </div>
                </div>
              <!-- Filtros e busca -->
              <div class="collapse" id="filterCollapse">
                        <div class="row mb-3" id="FiltroGeral">
                            <!-- Filtros adicionais -->
                            <div class="col-12 col-md-12">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12 col-md-12 mb-3">
                                            <div class="row">

                                                <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                                                    <label for="Nome">Nome</label>
                                                    <input type="text" id="name" name="name" class="form-control">
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                                                    <label for="Email">Email</label>
                                                    <input type="text" id="email" name="email" class="form-control">
                                                </div>
                                                <!-- Botões -->
                                                <div class="col-12 col-sm-4 col-md-4 col-lg-4" style="margin-top:30px;">
                                                    <button type="submit" class="btn btn-primary">filtrar</button>
                                                    <button type="button" class="btn btn-danger" id="clearFilterBtn">Limpar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <div id="response"></div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@section('scripts')
<script>
    // Função para carregar itens
    function loadItems(url = "{{ route('app.colaboradores.getItens') }}") {

        var formData = $('#filterForm').serialize();

        $.ajax({
            url: url,
            type: "GET",
            data: formData,

            success: function(response) {
                $('#response').html(response.html);
                $('#pagination-links').html(response.pagination);
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Colaboradores carregados com sucesso',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao carregar a lista de Colaboradores.',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    $('#filterForm').submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            loadItems(url);
        });

        $("body").on('click', '.pagination .page-link', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            loadItems(url)
        });

        $('#reloadItems').click(function() {
            loadItems();
        });

        $('#clearFilterBtn').click(function() {
            $('#filterForm')[0].reset();
            $('.select2').val(null).trigger('change');
            $('.datepicker').val('');
            loadItems();
        });

    loadItems();
        
    // Toggle Status
    $(document).on('change', '.toggle-status', function(e) {

        var userId = $(this).data('id');
        var statusElement = $(this);

        var url = "{{ route('app.colaboradores.toggleStatus', ':id') }}".replace(':id', userId);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao tentar alterar o status.',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    });


    // Exclusão de item
    $(document).on('click', '.btn-destroy', function(e) {
        e.preventDefault();

        var userId = $(this).data('id');

        Swal.fire({
            title: 'Tem certeza?',
            text: "Você não poderá reverter isso!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('app.colaboradores.destroy', ':id') }}".replace(':id', userId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        Swal.fire(
                            'Excluído!',
                            response.message,
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Houve um erro ao excluir a categoria.',
                        });
                    }
                });
            }
        });
    });


    document.querySelector('form').addEventListener('submit', function(event) {
        let select = document.getElementById('grupoSelect');
        if (select.value === "") {
            alert('Por favor, selecione uma opção válida.');
            event.preventDefault();
        }
    });
</script>
@endsection