@extends('layouts.app')
@section('title','Relatórios')

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

                    <div class="row mb-3 d-flex justify-content-end">
                        <!-- <div class="col-12 col-sm-3 ps-4 my-2 text-center text-sm-start d-block">
                           
                        </div> -->
                       
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

                                                <div class="col-12 col-sm-3 col-md-4 col-lg-4">
                                                    <label for="Nome">Nome</label>
                                                    <input type="text" id="name" name="name" class="form-control">
                                                </div>
                                                <div class="col-12 col-sm-3 col-md-4 col-lg-4">
                                                    <label for="Email">Email</label>
                                                    <input type="text" id="email" name="email" class="form-control">
                                                </div>
                                               
                                                <!-- Botões -->
                                                <div class="col-12 col-sm-3 col-md-4 col-lg-4" style="margin-top:30px;">
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
    function loadItems(url = "{{ route('app.relatorios.getItens') }}") {

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
                    text: 'Relatorios carregados com sucesso',
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
                    text: 'Erro ao carregar a lista de relatorios.',
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
        selects.prop('disabled', false);
        $('.datepicker').val('');
        loadItems();
    });

    loadItems();



    // Selects change
    const selects = $('#grupo_id, #bandeira_id, #unidade_id');

    selects.on('change', function() {
        const selectedId = $(this).attr('id');
        const hasValue = $(this).val() !== "";

        if (hasValue) {
            selects.not(this).prop('disabled', true).val("");
        } else {
            selects.prop('disabled', false);
        }
    });

</script>
@endsection