@extends('layouts.app')
@section('title','Contas a pagar')
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
                        <div class="col-12 col-sm-8 ps-2 mt-2 text-center text-sm-start d-block">
                            <div class="actions-btn">
                                <!-- Botões de ação - Criar e recarregar -->
                                <button id="btnAddLancamento" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalLancamento">
                                    Adicionar
                                </button>
                                <button id="reloadItems" class="btn btn-secondary">
                                    <i class="fa-solid fa-sync"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-sm-4 mt-3">
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
                            <div class="col-12 col-md-12 mb-3">
                                <form id="filterForm" class="float-center">
                                    <div class="row">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12 mb-3">
                                            <div class="row">
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="data_inicio">Data Início</label>
                                                    <input type="text" id="data_inicio" name="data_inicio" class="form-control datepicker" placeholder="Data Início">
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="data_fim">Data Fim</label>
                                                    <input type="text" id="data_fim" name="data_fim" class="form-control datepicker" placeholder="Data Fim">
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="descricao">Descrição</label>
                                                    <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição">
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="categoria">Categoria</label>
                                                    <select id="categoria" name="categoria" class="form-select select2">
                                                        <option value="">Selecione</option>
                                                        @foreach($categorias as $categoria)
                                                        <option value="{{ $categoria->id }}">{{ $categoria->descricao }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="conta">Conta</label>
                                                    <select id="conta" name="conta" class="form-select select2">
                                                        <option value="">Selecione</option>
                                                        @foreach($contas as $conta)
                                                        <option value="{{ $conta->id }}">{{ $conta->descricao }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="valor">Valor Mínimo</label>
                                                    <input type="text" id="valor" name="valor_min" class="form-control moneyMask" placeholder="R$ 0,00">
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3">
                                                    <label for="pago">Pago</label>
                                                    <select id="pago" name="pago" class="form-control">
                                                        <option value="">Selecione</option>
                                                        <option value="sim">Sim</option>
                                                        <option value="nao">Não</option>
                                                    </select>
                                                </div>

                                                <!-- Botões -->
                                                <div class="col-12 col-sm-4 col-md-3" style="margin-top:30px;">
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

        <div class="modal fade" id="modalLancamento" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContent">
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('scripts')
        <script>
            $(document).ready(function() {

                $('.moneyMask').mask("#.##0,00", {
                    reverse: true
                });
                $('.datepicker').flatpickr({
                    dateFormat: "Y-m-d",
                    altFormat: "d/m/Y",
                    altInput: true,
                    locale: "pt" // Define para português
                });
            });
        </script>

        <script>
            // Função para carregar itens
            function loadItems(url = "{{ route('app.fluxo-caixa.contas-pagar.getItens') }}") {

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
                            text: 'Contas a pagar carregadas com sucesso',
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
                            text: 'Erro ao carregar a lista de contas a pagar.',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                });
            }

            // Captura as informações de Filtros
            $('#filterForm').submit(function(e) {
                e.preventDefault();
                loadItems();
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

            $("body").on('click', '.pagination .page-link', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                loadItems(url)
            });

            // Create
            $('#btnAddLancamento').click(function() {
                const url = "{{ route('app.fluxo-caixa.contas-pagar.create') }}";

                $('#modalTitle').text('Adicionar Lançamento');

                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        $('#modalContent').html(response);
                        $('.moneyMask').mask("#.##0,00", {
                            reverse: true
                        });
                        $('.datepicker').flatpickr({
                            dateFormat: "Y-m-d",
                            altFormat: "d/m/Y",
                            altInput: true,
                            locale: "pt" // Define para português
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao carregar o formulário: ", error);
                        $('#modalContent').html('<p class="text-danger">Erro ao carregar o formulário.</p>');
                    },
                });
            });

            // Editar
            $(document).on('click', '.btnEditItem', function(e) {
                e.preventDefault();

                const lancamentoId = $(this).data('id');

                let url = "{{ route('app.fluxo-caixa.contas-pagar.edit', ['id' => '__id__']) }}";

                url = url.replace('__id__', lancamentoId);

                $('#modalTitle').text('Editar Lançamento');

                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {
                        $('#modalContent').html(response);
                        $('#modalLancamento').modal('show');
                        $('.moneyMask').mask("#.##0,00", {
                            reverse: true
                        });
                        $('.datepicker').flatpickr({
                            dateFormat: "Y-m-d",
                            altFormat: "d/m/Y",
                            altInput: true,
                            locale: "pt" // Define para português
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro ao carregar o formulário: ", error);
                        $('#modalContent').html('<p class="text-danger">Erro ao carregar o formulário.</p>');
                    },
                });
            });

            // Store e Update
            $(document).on('submit', '#formLancamento', function(e) {
                e.preventDefault();

                const form = $(this);
                const url = form.attr('action');
                const formData = form.serialize();

                const isEdit = form.find('input[name="_method"]').val() === 'PUT';

                if (isEdit) {
                    Swal.fire({
                        title: 'Atualização de Lançamento',
                        text: 'Você deseja atualizar apenas este lançamento ou este e futuros?',
                        icon: 'question',
                        showCancelButton: false,
                        confirmButtonText: 'Apenas este',
                        denyButtonText: 'Este e futuros',
                        showDenyButton: true,
                    }).then((result) => {
                        let extraData = '';

                        if (result.isConfirmed) {
                            extraData = '&apenas_este=true';
                        } else if (result.isDenied) {
                            extraData = '&apenas_este=false';
                        }

                        if (extraData) {
                            $.ajax({
                                url: url,
                                method: "POST",
                                data: formData + extraData,
                                success: function(response) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'Sucesso!',
                                        text: 'Lançamento atualizado com sucesso.',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });

                                    const modal = bootstrap.Modal.getInstance($('#modalLancamento'));
                                    modal.hide();

                                    location.reload();
                                },
                                error: function(xhr, status, error) {
                                    console.error("Erro ao atualizar o lançamento: ", error);
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Oops!',
                                        text: 'Houve um erro ao atualizar seu lançamento.',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });
                                },
                            });
                        }
                    });
                } else {
                    // Novo lançamento (não é edição)
                    $.ajax({
                        url: url,
                        method: "POST",
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Sucesso!',
                                text: 'Lançamento adicionado com sucesso.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            const modal = bootstrap.Modal.getInstance($('#modalLancamento'));
                            modal.hide();

                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error("Erro ao salvar o lançamento: ", error);
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Oops!',
                                text: 'Houve um erro ao adicionar seu lançamento.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                        },
                    });
                }
            });

            // Toggle Payment
            $(document).on('click', '.toggle-payment', function(e) {
                e.preventDefault();

                const lancamentoId = $(this).data('id');
                let url = "{{ route('app.fluxo-caixa.contas-pagar.togglePayment', ':id') }}".replace(':id', lancamentoId);

                const button = $(this);
                const isPaid = button.hasClass('btn-success');

                button.prop('disabled', true);
                button.html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Sucesso!',
                            text: 'Lançamento atualizado com sucesso.',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        loadItems()
                    },
                    error: function(xhr, status, error) {
                        button.prop('disabled', false);
                        console.error('Erro ao alterar status de pagamento:', error);
                        alert('Ocorreu um erro ao tentar alterar o status de pagamento.');
                    },
                });
            });

            // Toggle Status
            $(document).on('change', '.toggle-status', function(e) {

                var contaId = $(this).data('id');
                var statusElement = $(this);

                var url = "{{ route('app.fluxo-caixa.contas-pagar.toggleStatus', ':id') }}".replace(':id', contaId);

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

                var contaId = $(this).data('id');

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
                        var url = "{{ route('app.fluxo-caixa.contas-receber.destroy', ':id') }}".replace(':id', contaId);

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