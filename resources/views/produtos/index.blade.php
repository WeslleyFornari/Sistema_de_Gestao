@extends('layouts.app')
@section('title','Produtos')

@section('content')
<style>
    .toggle-payment {
        padding: 0 !important;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

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
                        <div class="col-12 col-sm-8 ps-4 mt-2 text-center text-sm-start d-block">
                            <div class="actions-btn">
                                <!-- Botões de ação - Criar e recarregar -->
                                <a href="{{ route('app.produtos.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Adicionar
                                </a>
                                <button id="reloadItems" class="btn btn-secondary">
                                    <i class="fa-solid fa-sync"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 mt-2">
                            <!-- Botão para colapsar os filtros e busca -->
                            <button class="btn btn-link w-100 toggleColapse" data-target="#filterCollapse" type="button">
                                <i class="fa-solid fa-filter"></i> Filtros Avançados
                            </button>
                        </div>
                    </div>

                    <!-- Filtros e busca -->
                    <div class="collapse" id="filterCollapse">
                        <div class="row mb-4" id="FiltroGeral">
                            <!-- Filtros adicionais -->
                            <div class="col-12 col-md-12">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12">
                                            <div class="row my-3">
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                    <div class="mb-3">
                                                        <label for="Descricao" class="form-label">Descricao</label>
                                                        <input type="text" class="form-control" id="descricao" name="descricao" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                    <label for="Grupo" class="form-label">Grupo</label>
                                                    <select name="grupo" id="grupo" class="form-select select2">
                                                        <option value="">Selecione</option>
                                                        @foreach($grupos as $value)
                                                        <option value="{{$value->id}}">{{$value->descricao}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                                    <label for="Categorias" class="form-label">Categorias</label>
                                                    <select name="categoria" id="categoria" class="form-select select2">
                                                        <option value="">Selecione</option>
                                                        @foreach($categorias as $value)
                                                        <option value="{{$value->id}}">{{$value->descricao}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <!-- Botões -->
                                                <div class="col-12 col-sm-6 col-md-4 col-lg-3" style="margin-top:30px;">
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
    function loadItems(url = "{{ route('app.produtos.getItens') }}") {

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
                    text: 'Produtos carregadas com sucesso',
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
                    text: 'Erro ao carregar a lista de produtos.',
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
        loadItems();
    });

    loadItems();



    // Toggle Status
    $(document).on('change', '.toggle-status', function(e) {

        var produtoId = $(this).data('id');
        var statusElement = $(this);

        var url = "{{ route('app.produtos.toggleStatus', ':id') }}".replace(':id', produtoId);

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
        var produtoId = $(this).data('id');

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
                var url = "{{ route('app.produtos.destroy', ':id') }}".replace(':id', produtoId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        Swal.fire(
                            'Excluído!',
                            response.message,
                            'success'
                        ).then(() => {
                            loadItems();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Houve um erro ao excluir o produto.',
                        });
                    }
                });
            }
        });
    });
</script>
@endsection