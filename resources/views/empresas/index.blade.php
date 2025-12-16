@extends('layouts.app')
@section('title','Empresas')

@section('content')
<style>
    #FiltroGeral {
        border: 1px solid rgb(222, 223, 223);
        border-radius: 5px;
    }
</style>
<div class="py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex pb-0">
                    <div class="col-9">
                        <div class="actions-btn">
                        @if(Auth::user()->role == 'master')
                            <!-- Botões de ação - Criar e recarregar -->
                            <a href="{{ route('app.empresas.new') }}" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Adicionar
                            </a>

                            <button id="reloadItems" class="btn btn-secondary">
                                <i class="fa-solid fa-sync"></i>
                            </button>
                        @endif
                        </div>
                    </div>
                 @if(Auth::user()->role == 'master')
                    <div class="col-3 ">
                        <!-- Botão para colapsar os filtros e busca -->
                        <button class="btn btn-link w-100 toggleColapse" data-target="#filterCollapse" type="button">
                            <i class="fa-solid fa-filter"></i> Filtros Avançados
                        </button>
                    </div>
                @endif
                </div>
                <div class="card-body p-4">
                    <!-- Filtros e busca -->
                    <div class="collapse" id="filterCollapse">
                        <div class="row mb-4" id="FiltroGeral">
                            <!-- Filtros adicionais -->
                            <div class="col-12 col-md-12">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12 col-md-12">
                                            <div class="row" style="margin-left:60px;">
                                                <div class="col-4">
                                                    <div class="mb-2">
                                                        <label for="fieldSelector" class="form-label">Campo</label>
                                                        <select class="form-select" id="fieldSelector">
                                                            <option value="nome">Nome</option>
                                                            <option value="nome_contato">Responsável</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="filterInput" class="form-label">Valor</label>
                                                        <input type="text" class="form-control" id="filterInput" placeholder="Digite o valor para filtrar">
                                                    </div>
                                                </div>

                                                <!-- Botões -->
                                                <div class="col-4" style="margin-top:30px;">
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

<div class="modal fade" id="ModalEmpresa" tabindex="-1" aria-labelledby="ModalEmpresa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalEmpresaLabel">Empresa</h5>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="container row">
                    <input type="hidden" name="id">
                    <div class="col-12 col-sm-6 mt-2">
                        <strong>Nome:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="nome"></p>
                    </div>
                    <div class="col-sm-4 col-12 mt-2">
                        <strong>Contato:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="contato"></p>
                    </div>
                    <div class="col-sm-6 col-12 mt-2">
                        <strong>Telefone:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="telefone"></p>
                    </div>
                    <div class="col-sm-6 col-12 mt-2">
                        <strong>CNPJ:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="cnpj"></p>
                    </div>
                    <div class="col-12 mt-2">
                        <strong>E-mail:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="email"></p>
                    </div>
                    <hr>
                    <h5>Endereço</h5>
                    <div class="col-sm-3 col-12 mt-2">
                        <strong>CEP:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="cep"></p>
                    </div>
                    <div class="col-sm-6 col-12 mt-2">
                        <strong>Endereço:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="endereco"></p>
                    </div>
                    <div class="col-sm-2 col-12 mt-2">
                        <strong>Número:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="numero"></p>
                    </div>
                    <div class="col-sm-4 col-12 mt-2">
                        <strong>Complemento:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="complete"></p>
                    </div>
                    <div class="col-sm-6 col-12 mt-2">
                        <strong>Cidade:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="cidade"></p>
                    </div>
                    <div class="col-sm-2 col-12 mt-2">
                        <strong>Estado:</strong><Br>
                        <p class="text-xs font-weight-bold mb-0" id="estado"></p>
                    </div>
                </div>
                <div class="mt-5">
                    <div class="col-12 text-end">
                        <a href="#" class="btn btn-primary"><i class="fa fa-pencil"></i> Editar</a>
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
            url: "{{ route('app.empresas.getItens') }}",
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
                    text: 'Empresas carregadas com sucesso',
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
                    text: 'Erro ao carregar a lista de Usuarios.',
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

        var empresaId = $(this).data('id');
        var statusElement = $(this);

        var url = "{{ route('app.empresas.toggleStatus', ':id') }}".replace(':id', empresaId);

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

        var empresaId = $(this).data('id');

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
                var url = "{{ route('app.empresas.destroy', ':id') }}".replace(':id', empresaId);

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
                            text: 'Houve um erro ao excluir o banner.',
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