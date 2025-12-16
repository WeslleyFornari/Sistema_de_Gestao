@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="container-fluid mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
            <form action="{{route('app.produtos.update', ['id'=>$produto->id])}}" id="formUpdate">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Descriçao: *</span>
                        <input type="text" name="descricao" id="descricao" value="{{$produto->descricao}}" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Grupo: *</span>
                        <select class="form-select grupoSelect" name="grupo" required>
                            <option value="{{ $produto->grupo->id ?? '' }}">{{ $produto->grupo->descricao ?? 'Selecione um grupo' }}</option>
                            <option value="">Selecione</option>
                            @foreach($grupos->where('id', '!=', optional($produto->grupo)->id) as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->descricao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-sm-6">
                        <span class="titulo"> Categorias: *</span>
                        <select class="form-select categoriaSelect" name="categoria" required>
                          
                            <option value="{{$produto->categoria->id}}">{{$produto->categoria->descricao ?? 'Selecione uma categoria'}}</option>
                        </select>
                    </div>

                    <div class="form-group col-12 col-sm-4">
                        <span class="titulo"> Valor: *</span>
                        <input type="text" name="valor" id="valor" value="{{getMoney($produto->valor)}}" class="form-control moneyMask" required>
                    </div>

                    <div class="col-12 col-sm-2 mt-4 text-md-center text-md-start">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ATUALIZAR
    $("body").on('submit', '#formUpdate', function(e) {

        e.preventDefault();
        let formData = new FormData($('#formUpdate')[0]);


        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {

                $("#formUpdate")[0].reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                }).then(() => {

                    window.location.href = "{{route('app.produtos.index')}}";

                });
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        $('#' + key + 'Error').text(errors[key][0]);
                        $('#' + key).addClass('is-invalid');
                        if (key.includes('media')) {
                            $('#' + key + 'Error').show();
                            $('input[name="' + key + '"]').addClass('is-invalid');
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro ao cadastrar o produto.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }
        });
    });

    // Select GRUPO
    $("body").on('change', '.grupoSelect', function() {

        var grupoId = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: 'GET',
            url: '{{route("app.produtos.selectGrupo")}}/' + grupoId,

            success: function(data) {

           //     var options = '<option value="" disabled selected>Selecione</option>';
                var options = '<option value="" selected>Selecione</option>';
                $.each(data, function(key, categoria) {
                    options += '<option value="' + categoria.id + '">' + categoria.descricao + '</option>';
                });

                $('.categoriaSelect').html(options);
            }
        });
    });
</script>
@endsection