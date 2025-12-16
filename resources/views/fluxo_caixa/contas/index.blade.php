@extends('layouts.app')
@section('title','Contas')

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
</style>
<div class="py-4">
<div class="row">
    <div class="col-md-12">

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-12 col-sm-8 ps-4 mt-2 text-center text-sm-start d-sm-block">
                    <div class="actions-btn">
                                <!-- Botões de ação - Criar e recarregar -->
                                <a href="{{route('app.fluxo-caixa.contas.create')}}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Adicionar
                                </a>
                                <button id="reloadItems" class="btn btn-secondary">
                                    <i class="fa-solid fa-sync"></i>
                                </button>
                            </div>
                    </div>

                    <div class=" col-12 col-sm-4 mt-3">
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
                            <div class="col-12 col-md-12 mt-2 mb-1">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12 mb-3">
                                            <div class="row justify-content-center">

                                                <div class="col-12 col-sm-4">
                                                    <label for="descricao">Descrição</label>
                                                    <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição">
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <label for="valor">Valor Mínimo</label>
                                                    <input type="text" id="valor" name="valor_min" class="form-control moneyMask" placeholder="R$ 0,00">
                                                </div>
                                                <!-- Botões -->
                                                <div class="col-12 col-sm-4" style="margin-top:30px;">
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
<div>
@endsection

@section('scripts')
<script>
    // Função para carregar itens
    function loadItems(url = "{{ route('app.fluxo-caixa.contas.getItens') }}") {
        console.log('url')
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
                    text: 'Contas carregadas com sucesso',
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
                    text: 'Erro ao carregar a lista de contas.',
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

        var contaId = $(this).data('id');
        var statusElement = $(this);

        var url = "{{ route('app.fluxo-caixa.contas.toggleStatus', ':id') }}".replace(':id', contaId);

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
    $(document).on('click', '.btn-destroy2', function(e) {
      
        e.preventDefault();

        var categoriaId = $(this).data('id');

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
                var url = "{{ route('app.fluxo-caixa.contas.destroy', ':id') }}".replace(':id', categoriaId);

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

</script>
@endsection