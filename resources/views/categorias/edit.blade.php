@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="container-fluid mt-5">
    <div class="card w-100">
        <div class="card-header">
            <h3>Editar</h3>
        </div>
        <div class="card-body">
        <form action="{{route('app.categorias.update', ['id'=>$categoria->id])}}" id="formUpdate">
                @csrf
                <div class="row mt-2">

                    <div class="form-group col-12 col-sm-5 col-md-5 col-lg-5 col-xl-5">
                        <span class="titulo"> Descriçao: *</span>
                        <input type="text" name="descricao" id="descricao" value="{{$categoria->descricao}}" class="form-control" required>
                    </div>

                    <div class="form-group col-12 col-sm-4 col-md-4 col-lg-5 col-xl-5">
                        <span class="titulo"> Grupo: *</span>
                        <select class="form-select" id="grupoSelect" name="grupo">

                            <option value="{{ $categoria->grupo->id ?? ''}}">{{ $categoria->grupo->descricao ?? ''}}</option>
                            <option value="">Selecione</option>
                            @foreach($grupos->where('id', '!=', optional($categoria->grupo)->id) as $grupo)
                                <option value="{{ $grupo->id }}">{{ $grupo->descricao }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-sm-3 col-md-3 col-lg-2 col-xl-2 mt-4">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
<script>
    $(document).ready(function() {

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

                        window.location.href = "{{ route('app.categorias.index') }}";

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
                            text: 'Erro ao cadastrar a categoria.',
                            toast: true,
                            position: 'top-end',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        });
    });
</script>
@endsection