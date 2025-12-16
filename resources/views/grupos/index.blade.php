@extends('layouts.app')
@section('title','Grupos')
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
                    <div class="col-12 d-flex justify-content-center col-sm-8 col-md-9 col-lg-10 col-xl-9 ps-4 my-2 justify-content-sm-start">
                    <div class="actions-btn">
                                <!-- Botões de ação - Criar e recarregar -->
                                <a href="{{ route('app.grupos.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus"></i> Adicionar
                                </a>
                                <button id="reloadItems" class="btn btn-secondary">
                                    <i class="fa-solid fa-sync"></i>
                                </button>
                            </div>
                    </div>

                    <div class="col-12 col-sm-4 col-md-3 col-lg-2 col-xl-3">
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
                            <div class="col-12 mb-3">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-12 col-sm-4 col-md-4 col-lg-5 col-xl-5">
                                                    <div class="mb-3">
                                                        <label for="fieldSelector" class="form-label">Campo</label>
                                                        <select class="form-select" id="fieldSelector">
                                                            <option value="descricao">Descrição</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                                    <div class="mb-3">
                                                        <label for="filterInput" class="form-label">Valor</label>
                                                        <input type="text" class="form-control" id="filterInput" placeholder="Digite o valor para filtrar">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Botões -->
                                        <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                                            <button type="submit" class="btn btn-primary">filtrar</button>
                                            <button type="button" class="btn btn-danger" id="clearFilterBtn">Limpar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="response">

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Função para carregar itens
    function loadItems(page = 1, searchQuery = '', filterField = '', filterValue = '') {
        $.ajax({
            url: "{{ route('app.grupos.getItens') }}",
            type: "GET",
            data: {
                search: searchQuery,
                field: filterField,
                value: filterValue,
                page: page
            },
            success: function(response) {
                $('#response').html(response.html);
                $('#pagination-links').html(response.pagination);
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Grupos carregados com sucesso',
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
                    text: 'Erro ao carregar a lista de grupos.',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
    }

    loadItems();

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        // let searchQuery = $('#searchField').val();
        // let filterField = $('#fieldSelector').val();
        // let filterValue = $('#filterInput').val();
        loadItems(page);
    });

    // Toggle Status
    $(document).on('change', '.toggle-status', function(e) {

        var grupoId = $(this).data('id');
        var statusElement = $(this);

        var url = "{{ route('app.grupos.toggleStatus', ':id') }}".replace(':id', grupoId);

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

        var grupoId = $(this).data('id');

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
                var url = "{{ route('app.grupos.destroy', ':id') }}".replace(':id', grupoId);

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
                            text: 'Houve um erro ao excluir o grupo.',
                        });
                    }
                });
            }
        });
    });

        // Filtros
        $('#filterForm').submit(function(e) {
        e.preventDefault();
        let filterField = $('#fieldSelector').val();
        let filterValue = $('#filterInput').val();
        loadItems(1, '', filterField, filterValue);
    });

    $('#reloadItems').click(function() {
        loadItems();
    });

    $('#clearFilterBtn').click(function() {
        $('#searchField').val('');
        $('#filterInput').val('');
        $('#fieldSelector').val('name');
        loadItems();
    });
</script>
@endsection