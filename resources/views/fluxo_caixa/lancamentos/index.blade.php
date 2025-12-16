@extends('layouts.app')
@section('title','Extratos')
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
                        <div class="col-12 col-sm-7 col-md-8 col-lg-8 col-xl-8 ps-4 my-1">
                                    <h3>Consultar extratos</h3>
                                </div>
                        
                    
                            <div class="col-12 text-center col-sm-5 col-md-4 col-lg-4 col-xl-3 text-sm-end my-2">
                            <!-- Botão para colapsar os filtros e busca -->
                                <button class="btn btn-link toggleColapse" data-target="#filterCollapse" type="button">
                                    <i class="fa-solid fa-filter"></i> Filtros Avançados
                                </button>
                            </div>
                            </div>
                
                        <!-- Filtros e busca -->
                        <div class="collapse" id="filterCollapse">
                            <div class="row mb-3" id="FiltroGeral">
                                <!-- Filtros adicionais -->
                                <div class="col-12 col-md-12 mb-3">
                                    <form id="filterForm" class="float-lg">
                                        <div class="row">
                                            <!-- Filtros por campo e valor -->
                                            <div class="col-12 col-md-12">
                                                
                                                <div class="row">
                                                    <div class="col-12 col-sm-4 col-lg-3">
                                                        <label for="data_inicio">Data Início</label>
                                                        <input type="text" id="data_inicio" name="data_inicio" class="form-control datepicker" placeholder="Data Início">
                                                    </div>
                                                    <div class="col-12 col-sm-4 col-lg-3">
                                                        <label for="data_fim">Data Fim</label>
                                                        <input type="text" id="data_fim" name="data_fim" class="form-control datepicker" placeholder="Data Fim">
                                                    </div>
                                                    <div class="col-12 col-sm-4 col-lg-6">
                                                        <label for="descricao">Descrição</label>
                                                        <input type="text" id="descricao" name="descricao" class="form-control" placeholder="Descrição">
                                                    </div>
                                                    
                                                </div>
                                            </div>

                                            <div class="row">
                                            <div class="col-12 col-sm-4 col-lg-3">
                                                        <label for="categoria">Categoria</label>
                                                        <select id="categoria" name="categoria" class="form-select">
                                                            <option value="">Selecione</option>
                                                            @foreach($categorias as $categoria)
                                                            <option value="{{ $categoria->id }}">{{ $categoria->descricao }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                <div class="col-12 col-sm-4 col-lg-3">
                                                    <label for="conta">Conta</label>
                                                    <select id="conta" name="conta" class="form-select select2">
                                                        <option value="">Selecione</option>
                                                        @foreach($contas as $conta)
                                                        <option value="{{ $conta->id }}">{{ $conta->descricao }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-sm-4 col-lg-3">
                                                    <label for="valor">Valor Mínimo</label>
                                                    <input type="text" id="valor" name="valor_min" class="form-select moneyMask" placeholder="R$ 0,00">
                                                </div>
                                                <div class="col-12 col-sm-4 col-lg-3">
                                                    <label for="pago">Pago</label>
                                                    <select id="pago" name="pago" class="form-select">
                                                        <option value="">Selecione</option>
                                                        <option value="sim">Sim</option>
                                                        <option value="nao">Não</option>
                                                    </select>
                                                </div>

                                                <!-- Botões -->
                                                <div class="col-12 col-sm-4" style="margin-top:30px;">
                                                    <button type="submit" class="btn btn-primary">filtrar</button>
                                                    <button type="button" class="btn btn-danger" id="clearFilterBtn">Limpar</button>
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
    function loadItems(url = "{{ route('app.fluxo-caixa.lancamentos.getItens') }}") {
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
                    text: 'Lancamentos carregados com sucesso',
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
                    text: 'Erro ao carregar a lista de lancamentos.',
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

</script>
@endsection