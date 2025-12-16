@extends('layouts.app')
@section('title','Pagamentos')

@section('assets')
<style>
    #FiltroGeral {
        border: 1px solid rgb(222, 223, 223);
        border-radius: 5px;
    }

    .cabecalho {
        font-family: Calibri, sans-serif;
        font-size: 14px;
        font-weight: bold;
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
          width: 80px;
          overflow: hidden;
          text-overflow: ellipsis;
          display: inline-block;
      }
    } 
</style>
@endsection

@section('content')
<div class="py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-12 col-sm-5 ps-4 mt-3 text-center text-md-start">
                            <h5>Lista</h5>
                        </div>
                        <div class="col-12 col-sm-4 mt-3 text-center text-md-end">
                            <!-- Botão para colapsar os filtros e busca -->
                            <button class="btn btn-link toggleColapse " data-target="#filterCollapse" type="button">
                                <i class="fa-solid fa-filter"></i> Filtros Avançados
                            </button>
                        </div>
                        <div class="col-12 col-sm-3 text-center" style="margin-top:10px;">
                            <a href="{{route('app.pagamentos.geral')}}" class="btn btn-success">Exportar</a>
                        </div>

                    </div>

                    <!-- Filtros e busca -->
                    <div class="collapse" id="filterCollapse">
                        <div class="row mb-3" id="FiltroGeral">
                            <!-- Filtros adicionais -->
                            <div class="col-12 col-md-12 mb-3">
                                <form id="filterForm" class="float-center">
                                    <div class="row mt-3">
                                        <!-- Filtros por campo e valor -->
                                        <div class="col-12">

                                            <div class="row">
                                                <div class="col-12 col-sm-4 col-lg-4">
                                                    <label for="numero">Número</label>
                                                    <input type="text" id="numero" name="numero" class="form-control" placeholder="" style="text-transform: uppercase;">
                                                </div>
                                                <div class="col-12 col-sm-5 col-lg-5">
                                                    <label for="cliente">Cliente</label>
                                                    <input type="text" id="cliente" name="cliente" class="form-control" placeholder="">
                                                </div>

                                                <div class="col-12 col-sm-3 col-lg-3">
                                                    <label for="forma">Forma de Pagamento</label>
                                                    <select name="forma" id="forma" class="form-select">
                                                        <option value="">Selecione</option>
                                                        @foreach($forma_pagto as $value)
                                                        <option value="{{$value->tipo}}">{{$value->tipo}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                           

                                           
                                                <div class="col-12 col-sm-5 col-md-3 col-lg-5">
                                                
                                                    <label for="grupo">Grupo</label>
                                                    <select name="grupo" id="grupo" class="form-select">
                                                        <option value="">Selecione</option>
                                                        @foreach($grupos as $grupo)
                                                        <option value="{{$grupo->descricao}}">{{$grupo->descricao}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-4 col-md-3 col-lg-4">
                                                    <label for="valor">Valor Mínimo</label>
                                                    <input type="text" id="valor_min" name="valor_min" class="form-control moneyMask" placeholder="R$ 0,00">
                                                </div>
                                                <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                                                    <label for="status">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="">Selecione</option>

                                                        <option value="pago">Pago</option>
                                                        <option value="cancelado">Cancelado</option>
                                                        <option value="pendente">Pendente</option>

                                                    </select>
                                                </div>

                                                <!-- Botões -->
                                                <div class="col-12 col-sm-6 col-md-3" style="margin-top:30px;">
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModalPagto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-12"><b>Pedido: <span id="pedidoNumero"></span> </b></div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="conteudo-Pagamento">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
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
    function loadItems(url = "{{ route('app.pagamentos.getItens') }}") {

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
                    text: 'Pagamentos carregados com sucesso',
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
                    text: 'Erro ao carregar a lista de pagamentos.',
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
        loadItems();
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

    loadItems()

    // Show
    $("body").on('click', '.showPagamentos', function() {

        event.preventDefault();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: "GET",

            success: function(response) {


                $('#conteudo-Pagamento').html(response.html);
                $('#pedidoNumero').html(response.numero);
                $("#exampleModalPagto").modal('show')

            },
        });
    });

    //Swal Simples de Exportação
    $("body").on('click', '.exportar', function(e) {

        e.preventDefault();
        url: $(this).attr('href')
        console.log('url')

        $.ajax({
            url: $(this).attr('href'),
            type: "GET",

            success: function(response) {

                swal.fire("Arquivo exportado com sucesso!");

            },
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